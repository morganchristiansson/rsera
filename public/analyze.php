<?php

include_once "../global.inc.php";

if(!strstr($HTTP_SERVER_VARS['HTTP_REFERER'], "newser.php")){ 
    err("Sorry, no direct access", $HTTP_SERVER_VARS['HTTP_REFERER']);
} 

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : analyze.php
  Desc.   : main analysis page - get search result pages and parse them

  Version : 0.3
  Author  : Matthijs Koot

  History : 28-04-03 - file created
            05-05-03 - flush output directly to browser
            11-05-03 - some optimizations and code layout
            28-07-03 - switch invert: changed "langmatch" to "rampage"
                       changed "POST" to "GET"
            29-07-03 - added support for international keyphrases
                       added support for worldwide search engines
                       changed "GET" back to "POST", I forgot that GET has limits
            30-07-03 - added ADODB layer, in hope for dbms independence...
            21-08-03 - removed PHP short tags
            09-09-03 - fixed bug in link to SE result page (change faulty $o_zm->URL to $o_zm->HOST)
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            05-10-03 - "inactive" keyphrases won't be analyzed from now on
            19-01-04 - added utf8_before_urlencode fix for Excite.fr
            21-01-04 - change $_POST["ws"] into $_POST["website"], some strange Mozilla behaviour
                       change $_POST["name"] into $_POST["username"], some strange Mozilla behaviour
            15-04-04 - now displaying and storing the experimental $ranking["indexed_page"] value
                       (exact page associated with the hit, i.e. '/' or '/products.html')
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
                       renamed 'separator' to 'hit_separator' because of MySQL-4.1 reserved words
*/

$regexp_debug=0; //


$ws_id       = isset($HTTP_POST_VARS["website"])?$HTTP_POST_VARS["website"]:0;      // ID of website
$phrases     = isset($HTTP_POST_VARS["phrases"])?$HTTP_POST_VARS["phrases"]:0; // IDs of phrases (array)
$se_selected = isset($HTTP_POST_VARS["zm"])?$HTTP_POST_VARS["zm"]:0;      // IDs of searchengines (array)
$name        = isset($HTTP_POST_VARS["username"])?$HTTP_POST_VARS["username"]:0;    // custom username


/*******************************************************
  1) INPUT VALIDATION AND VERIFICATION
********************************************************/

  /* verify ranking type */
     if (isset($HTTP_POST_VARS["type"]) && ($HTTP_POST_VARS["type"]=="real") )
       $real = true;
     else 
       $real = false;
     

  /* verify if keyphrase/SE language match should be used */
     if (isset($HTTP_POST_VARS["rampage"]) )
       $lang_must_match = false;
     else
       $lang_must_match = true;
     
  
  /* verify selection of search engines */
    if (sizeof($se_selected) < 1) err("No search engines selected!", $se_selected);
  
  /* verify selection of phrases */
    if (sizeof($phrases) < 1) err("No keywords/phrases selected!", $phrases);
  
  /* verify the website ID */
    if (!is_numeric($ws_id)) err("Invalid website ID. Should be numeric!", $is_numeric);
    $sql_ws = "SELECT url FROM $DB_Websites WHERE ws_id=".$ws_id;
    $result_ws = $db->Execute($sql_ws);
    if ($result_ws->EOF) 
      err ("No website with such ID!", $ws_id);
    
  /* get website URL to look for */
    $o_ws = $result_ws->FetchObject();
    $ws_url = $o_ws->URL;
  
  /* verify all phrase IDs */
    for ($l=0; $l < sizeof($phrases); $l++) {
      if (!is_numeric($phrases[$l])) err("At count $l in the keyphrases loop, a non-numeric keyphrase ID was found!", $phrases);
      $sql_zt    = "SELECT id, keyword, langcode FROM $DB_Keyphrases WHERE ws_id=".$ws_id." AND id=".$phrases[$l]." AND is_active=1";
      $result_zt = $db->Execute($sql_zt);

      if ($result_zt->EOF) 
        err("At count $l in the keyphrases loop, a non-existing keyphrase ID was used!");
      $o_zt = $result_zt->FetchObject();
      $phrasestxt[$l][0] = $o_zt->ID; // phrase ID
      $phrasestxt[$l][1] = $o_zt->KEYWORD; // phrase contents
      $phrasestxt[$l][2] = $o_zt->LANGCODE; // phrase language code
    }

/*******************************************************
  2) BUILD HTML RESULT PAGE
********************************************************/
?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
  <html>
  <head>
  <title><?php echo $TITLE?></title>
  <link rel="stylesheet" href="css/content.css">
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head>
  <body>
  <h3><?php echo $lang["analyze"]["report"]?></h3>
  <?php if(isset($debugmsg)) echo $debugmsg; ?>
  <table>
    <tr>
      <th><?php echo $lang["analyze"]["website"]?></th>
      <td nowrap><?php echo $ws_url?></td>
    </tr>
    <tr>
      <th><?php echo $lang["analyze"]["phrase(s)"]?></th>
      <td nowrap>| <?php for ($m=0;$m<sizeof($phrasestxt);$m++){ echo $phrasestxt[$m][1] . " | "; } ?></td>
    </tr>
    <tr>
      <th><?php echo $lang["analyze"]["type"]?></th>
      <td nowrap><?php
        if ($lang_must_match) $mm = ", language match ON";
        else $mm = ", language match OFF";
                   if ($real) {
                     echo"<font color=\"blue\" size=\"+1\"><b>".$lang["analyze"]["real"]."$mm</b></font>";
                   } else {
                     echo"<font color=\"green\" size=\"+1\"><b>".$lang["analyze"]["test"]."$mm</b></font>";
                   }
                 ?></td>
    </tr>
  </table>
  <br>
<?php
  /*******************************************************
    2-a) INSERT NEW REPORT (optionally)
  ********************************************************/
    if ($real) {
      /* insert a new report */
        $sql_real = "INSERT INTO $DB_Reports (ws_id,name,rankdate) VALUES ('$ws_id','".strip_tags(urldecode($name))."','".date("Y-m-d")."');";
        $result_ins = $db->Execute($sql_real);
    
      /* determine this report's (autoincremented) ID */
        $currentid = $db->Insert_ID($result_ins);
        
        if (!isset($currentid) || !is_numeric($currentid))
          err("The ID for the new report could not be retrieved", $currentid);
    }
  

  /*******************************************************
    2-b) LOOP THROUGH THE KEYPHRASES
  ********************************************************/
    for ($k=0; $k < (sizeof($phrasestxt)); $k++) {
      $zt_id  = $phrasestxt[$k][0]; // phrase ID
      $phrase = $phrasestxt[$k][1]; // phrase content
      $phrase_lang = $phrasestxt[$k][2]; // phrase language

        /*******************************************************
          2-b-1) LOOP THROUGH THE SEARCH ENGINES FOR THIS PHRASE
        ********************************************************/
          $count=1; /* SE loop counter */
          foreach($se_selected as $se_id => $value) {
            $sql_zm = "SELECT title, url, startkey, endkey, hit_separator, host, script, data, langcode, noresult, utf8_support FROM $DB_Searchengines WHERE zm_id = ".$se_id;
            $result_zm = $db->Execute($sql_zm);
          
            /* create a search engine for getRanking() */
              $o_zm = $result_zm->FetchObject();
              $se[0] = $o_zm->TITLE;     // title (displayed by getRanking() in debug mode)
              $se[1] = $o_zm->UTF8_SUPPORT;
              $se[2] = $o_zm->URL;     // url
              $se[3] = $o_zm->STARTKEY;     // startkey
              $se[4] = $o_zm->ENDKEY;     // endkey
              $se[5] = $o_zm->HIT_SEPARATOR;     // separator
              $se[6] = $o_zm->HOST;     // host
              $se[7] = $o_zm->SCRIPT;     // script
              $se[8] = $o_zm->DATA;     // data
              $se[9] = $o_zm->LANGCODE;     // langcode
              $se[10] = $o_zm->NORESULT;    // noresult

              /*
                Determine whether to fetch a ranking or not

                WARNING: if the langcode of a search engine is set to '@@',
                         ALL keyphrases will be used to query. This is a
                         manual override for the usually required language 
                         match.
              */
              if (($lang_must_match) &&       /* if lang match is required */
                  ($se[9]!=$phrase_lang) &&   /* AND no lang match */
                  ($se[9]!="@@") &&           /* AND not a worldwide SE */
                  ($phrase_lang!="@@")        /* AND not an intl. keyphrase */
                 ) {
                //$debug_langmatch .= "Language mismatch between '".$se[0]."' (".$lang[lc][$se[9]].") and '$phrase' ($phrase_lang). Skipped...<br>";
                continue;
              }

              if ($count==1) {
                $count++;
              ?>
                <h2><?php echo $phrase." (".$lang["lc"][$phrase_lang].")"?></h2>
                <table cellpadding="5"> 
                  <tr style="height:1px">
                    <td><img src="images/spacer.gif" alt="" width="30" height="1"></td>
                    <td><img src="images/spacer.gif" alt="" width="200" height="1"></td>
                    <td><img src="images/spacer.gif" alt="" width="100" height="1"></td>
                 </tr>
                 <tr>
                   <th style="width:30px"></th>
                   <th style="width:200px"><?php echo $lang["analyze"]["searchengine"]?></th>
                   <th style="width:100px"><?php echo $lang["analyze"]["position"]?></th>
                   <th style="width:100px"><?php echo $lang["analyze"]["indexed_page"]?></th>
                 </tr>
               </table>
            <?php
           }
            /* actually get the ranking */
              $ranking = getRanking($ws_url, $phrase, $se);
              if ($ranking["position"] > 0) {
                /* URL was listed and ranking determined */
                $image = "check.gif";
                $msg   = $ranking["position"];
              } elseif ($ranking["position"]==-1) { 
                /* regex error or not found - please check manually*/
                $image = "error.gif";
                $msg = "<font color=\"red\"><b>".$ranking["message"]."</b></font>";
                $msg.= "&nbsp;<a href=\"se_frameset.php?se=".$se_id."&phrase=".urlencode($phrase)."\">debug</a>";
              } elseif ($ranking["position"]==-2) { 
                 /* host connect error OR incorrect [noresult] message */
                $image = "error.gif";
                $msg   = "<font color=\"red\"><b>".$ranking["message"]."</b></font>";
                $msg.= "&nbsp;<a href=\"se_frameset.php?se=".$se_id."&phrase=".urlencode($phrase)."\">debug</a>";
              } else { 
                /* URL not listed in search results */
                $image = "nocheck.gif";
                $msg   =  $lang["analyze"]["notfound"];
              }
            
            /* BEGIN RESULT TABLE */  ?>
               <table cellpadding="0">
                 <tr style="height:1px;">
                   <td><img src="images/spacer.gif" alt="" width="30" height="1"></td>
                   <td><img src="images/spacer.gif" alt="" width="200" height="1"></td>
                   <td><img src="images/spacer.gif" alt="" width="100" height="1"></td>
                   <td><img src="images/spacer.gif" alt="" width="100" height="1"></td>
                 </tr>
                 <tr>
                   <td style="width:30px">
                     <img src="images/<?php echo $image?>">
                   </td>
                   <td style="width:200px" nowrap>
                     <a href="http://<?php echo $o_zm->HOST.$o_zm->SCRIPT."?".str_replace("[__KEYPHRASE__]", urlencode($phrase), $o_zm->DATA)?>"><?php echo $o_zm->TITLE?></a>
                   </td>
                   <td style="width:100px">
                     <?php echo $msg?>
                   </td>
                   <td style="width:100px">
                     <?php echo $ranking["indexed_page"] ?>
                   </td>
                 </tr>
               </table>
            <?php /* END RESULT TABLE */
         
            /* add the result to the report (optionally) */
              if ($real) {   
                $sql_rule = "INSERT INTO $DB_Reportrules 
                               (mt_id,zt_id,zm_id,ranking,indexed_page) 
                             VALUES 
                               ($currentid,$zt_id,$se_id,".$ranking["position"].",'".$ranking["indexed_page"]."');";
                if(!$db->Execute($sql_rule)) 
                  err("Error adding this result to the report!", $sql_rule);
              }
            
            /* immediately flush it to the browser */
              flush();
          } // end SEs loop 
    } // end phrases loop 

/*******************************************************
  3) FINISH THE HTML RESULT PAGE
********************************************************/
?>
<h2><?php echo $lang["analyze"]["endofanalysis"]?></h2>
<?php
//echo $debug_langmatch;
?>
</body>
</html>
