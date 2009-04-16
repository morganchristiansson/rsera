<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : quick.php
  Desc.   : quick ranking on a single url and keyphrase

  Version : 0.3
  Author  : Matthijs Koot

  History : 01-05-03 - file created
            28-07-03 - added support for worldwide SEs
            30-07-03 - added ADODB layer, in hope for dbms independence...
            05-08-03 - creuzerm - creuzerm@users.sourceforge.net
			                 added checks for non-existant _GET varaibles 
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            17-10-03 - added dropdown list w/known websites
            19-01-04 - added utf8_before_urlencode fix for Excite.fr
            21-01-04 - fixed 'smart folding menu' for Mozilla
            26-01-04 - changed GET to POST, replaced javascript for Search Engine selection (again)
            15-04-04 - now displaying the experimental $ranking["indexed_page"] value
                       (exact page associated with the hit, i.e. '/' or '/products.html')
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
                       renamed 'separator' to 'hit_separator' because of MySQL-4.1 reserved words
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript1.2">
<!--
  function checkAll(containerID, doit) {
    var container = document.getElementById(containerID);
    var curobj = container.getElementsByTagName("input");
    for (var i = 0; i < curobj.length; i++)
      if (curobj[i].type == "checkbox")
        curobj[i].checked = doit;
  }

function flip(e) {
    if (document.getElementById(e).style.display == 'none')
      show(e);
    else
      hide(e);
}

function hide(e) {
  document.getElementById(e).style.display = 'none';
}

function show(e) {
  document.getElementById(e).style.display = 'block';
}

function hideall() {
	var Nodes = document.getElementsByTagName('div')
	var max = Nodes.length
	for(var i = 0;i < max;i++) {
 	  var nodeObj = Nodes.item(i)
          if (nodeObj.getAttribute('tag') == 'selist')
  	    nodeObj.style.display = 'none';
	}
}

function showall() {
        var Nodes = document.getElementsByTagName('div')
        var max = Nodes.length
        for(var i = 0;i < max;i++) {
          var nodeObj = Nodes.item(i)
          if (nodeObj.getAttribute('tag') == 'selist')
            nodeObj.style.display = 'block';
        }
}

//-->
</script>
</head>
<body <?php //echo (!isset ($HTTP_POST_VARS["actie"]))?"onload=\"checkAll('secheckboxes', true);\"":"onload=\"hideAll();\""?>>

<h3><?php echo $lang["analyze"]["quick"]?></h3>
<?php
if ( (isset ($HTTP_POST_VARS["actie"]) && $HTTP_POST_VARS["actie"]!="start") || (!(isset ($HTTP_POST_VARS["actie"]))) ) { // if "actie" is set and is not "start" or if it is not set
  /*******************************************************
    1) DISPLAY MAIN PAGE
  ********************************************************/
?>
  
  <table>
   <tr>
    <td>
      <fieldset>
        <legend><b><?php echo $lang["newser"]["analysis"]?></b></legend>
          <form action="<?php echo $PHP_SELF?>" method="POST" name="form1">
        <table>
          <tr>
            <td>Debug:</td>
            <td><input type="checkbox" name="debug">Show debug</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>URL:</td>
            <td>
              <table>
                <tr>
                  <td><input type="radio" name="source" value="url" onclick="url.disabled=false;knownurl.disabled=true" checked></td>
                  <td><input type="text" name="url" value="http://"></td>
                </tr>
                <tr>
                  <td><input type="radio" name="source" value="knownurl" onclick="url.disabled=true;knownurl.disabled=false"></td>
                  <td>
                    <select name="knownurl" style="width:250px" disabled>
            <?php
               /*******************************
                 1) LOAD ALL WEBSITES
               *******************************/
                 $sql_ws     = "SELECT ws_id, url FROM $DB_Websites";
                 $result_ws  = $db->Execute($sql_ws);

                 while ($o = $result_ws->FetchNextObject()) {
                   echo "<option value=\"$o->URL\"";
                   echo ">$o->URL</option>\n";
                 }
              ?>
                    </select>
                  </td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo $lang["newser"]["keyphrase"]?>:</td>
            <td><input type="text" name="keyphrase" value=""></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">
              <?php echo $lang["newser"]["searchengines"]?>: <br>
              <a href="#" onclick="checkAll('secheckboxes',true); showall(); return false;"><?php echo $lang["newser"]["select_all"]?></a> | 
              <a href="#" onclick="checkAll('secheckboxes',false); return false;"><?php echo $lang["newser"]["select_none"]?></a>
            </td>
            <td>
              <div id="secheckboxes"><!-- required by checkAll() -->
<?php

 /*******************************************************
   2) LOAD ALL SEARCH ENGINES
 ********************************************************/

      /* 
        Generating a DHTML menu from the known search engines 
         - since [searchengines].[langcode] is "@@" for international SEs,
           we first gather all available values for "langcode" (ISO-639 language code)
           and base the menu on THAT, rather than hardcoding each 
           country in this PHP script
         - note that support for Australia/NZ will require some
           change in this method! (just reserve "$$" for it, e.g.)
      */

     echo $lang["quick"]["selectse"]."<br>\n";

     $languages = array("en","nl","de","fr","it","sv","es","no","fi","da", "pl", "@@");

     foreach ($languages as $langcode) {
        $flag = ($langcode == "@@")?"all":$langcode;
        /************************************
          2-a) LOOP THROUGH LANGUAGES
        ************************************/
          if ($lang["lc"][$langcode]=="")
            err("</ul><h2>Error: translation for [".$langcode."] not set in data.inc!</h2>");

          ?>
              <a href="#" onclick="checkAll('<?php echo $langcode ?>',true); show('<?php echo $langcode?>'); return false;"><?php echo $lang["newser"]["select_all"]?></a> | 
              <a href="#" onclick="checkAll('<?php echo $langcode ?>',false); return false;"><?php echo $lang["newser"]["select_none"]?></a>
          <?php
          echo "&nbsp;<a href=\"javascript:flip('$langcode')\">";
          echo "<img src=\"images/flags/$flag.gif\" alt=\"".$lang["lc"][$langcode]."\">\n";
          echo $lang["lc"][$langcode];
          echo "</a><br>\n";
          echo "<div tag=\"selist\" id=\"$langcode\" style=\"display:none;\">\n";

          /* show all search engines for the current language */
            $sql_zm = "SELECT zm_id, title, url FROM $DB_Searchengines WHERE langcode='$langcode' ORDER BY title";

          $result_zm = $db->Execute($sql_zm);
   
          while ($o = $result_zm->FetchNextObject()) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"images/flags/$flag.gif\" alt=\"".$lang["lc"][$langcode]."\">\n";
            echo "<input type=\"checkbox\" name=\"zm[".$o->ZM_ID."]\" value=\"1\">".$o->TITLE."<br>\n";
          }

          echo "</div>\n";
        } // end loop through all languages
?>
              </div>
            </td>
            <td valign="top">
            </td>
          </tr>
          <tr>
            <td colspan=3 align="right">
              <table>
                <tr>
                  <td align="right"><?php echo $lang["quick"]["start"]?></td>
                  <td>
                    <input type='image' name='arrow' src='images/pijlredrights.gif' border='0'>
                    <input type="hidden" name="actie" value="start">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
          </form>
      </fieldset>
    </td>
   </tr>
  </table>
  <h2><?php echo $lang["quick"]["explanation_header"]?></h2>
  <p><?php echo $lang["quick"]["explanation"]?></p>

<?php
} else {
  /*******************************************************
    2) DISPLAY RESULT PAGE
  ********************************************************/
    if (isset ($HTTP_POST_VARS["debug"]) && $HTTP_POST_VARS["debug"]=="on") $verbose=true; else $verbose=false;

    /*******************************************************
      2-a) INPUT VALIDATION AND VERIFICATION
    ********************************************************/
      /* verify selection of search engines */
        if (sizeof($HTTP_POST_VARS["zm"]) < 1) err("No search engines selected!");
        
      /* verify keyphrase */
        if (strlen($HTTP_POST_VARS["keyphrase"]) < 1) err("No keyphrase!");

      /* verify URL */
        $checkurl = "";
        if ($HTTP_POST_VARS["source"]=="url")
          if (strlen($HTTP_POST_VARS["url"]) < 1) err("No URL!");
          else $checkurl = $HTTP_POST_VARS["url"];
        elseif ($HTTP_POST_VARS["source"]=="knownurl")
          if (strlen($HTTP_POST_VARS["knownurl"]) < 1) err("No URL!");
          else $checkurl = $HTTP_POST_VARS["knownurl"];
         
      
  
    ?>
    
    <table>
      <tr>
        <th><?php echo $lang["analyze"]["website"]?></th>
        <td nowrap><?php echo $checkurl ?></td>
      </tr>
      <tr>
        <th><?php echo $lang["newser"]["keyphrase"]?></th>
        <td nowrap><?php echo $HTTP_POST_VARS["keyphrase"]?></td>
      </tr>
      <tr>
        <th><?php echo $lang["analyze"]["type"]?></th>
        <td nowrap><?php echo"<font color=\"green\" size=\"+1\"><b>".$lang["analyze"]["quick"]."</b></font>"; ?></td>
      </tr>
    </table>
    <br>
    <h2><?php echo $HTTP_POST_VARS["keyphrase"]?></h2>
    <table cellpadding="5">
      <tr>
        <th style="width:30px"></th>
        <th style="width:200px"><?php echo $lang["analyze"]["searchengine"]?></th>
        <th style="width:100px"><?php echo $lang["analyze"]["position"]?></th>
        <th style="width:200px"><?php echo $lang["analyze"]["indexed_page"]?></th>
      </tr>
    </table>
      <?php
        /*******************************************************
          2-b-1) LOOP THROUGH THE SEARCH ENGINES
        ********************************************************/
          foreach($HTTP_POST_VARS["zm"] as $se_id => $value) {
            $sql_zm = "SELECT title, url, startkey, endkey, hit_separator, host, script, data, langcode, noresult, utf8_support FROM $DB_Searchengines WHERE zm_id = ".$se_id;
            $result_zm = $db->Execute($sql_zm);
          
            /* create a search engine for getRanking() */
            if ($result_zm->EOF)
              err("No Search Engine found with such ID [$se_id]");

              $o = $result_zm->FetchObject();
              $se[0] = $o->TITLE;     // title (displayed by getRanking() in debug mode)
              // $se[1] = $rs[?];    // <reserved>
              $se[1] = $o->UTF8_SUPPORT; 
              $se[2] = $o->URL;     // url
              $se[3] = $o->STARTKEY;     // startkey
              $se[4] = $o->ENDKEY;     // endkey
              $se[5] = $o->HIT_SEPARATOR;     // separator
              $se[6] = $o->HOST;     // host
              $se[7] = $o->SCRIPT;     // script
              $se[8] = $o->DATA;     // data
              $se[9] = $o->LANGCODE;     // langcode
              $se[10] = $o->NORESULT;    // noresult
         
            /* actually get the ranking */
              $ranking = getRanking($checkurl, $HTTP_POST_VARS["keyphrase"], $se, $verbose);

              if ($ranking["position"] > 0) {
                /* URL was listed and ranking determined */
                $image = "check.gif";
                $msg   = $ranking["position"];
              } elseif ($ranking["position"]==-1) { 
                /* regex error or not found - please check manually*/
                $image = "error.gif";
                $msg = "<font color=\"red\"><b>".$ranking["message"]."</b></font>";
                $msg.= "&nbsp;<a href=\"se_frameset.php?se=".$se_id."&phrase=".urlencode($HTTP_POST_VARS["keyphrase"])."\">debug</a>";
              } elseif ($ranking["position"]==-2) { 
                 /* host connect error OR incorrect [noresult] message */
                $image = "error.gif";
                $msg   = "<font color=\"red\"><b>".$ranking["message"]."</b></font>";
                $msg.= "&nbsp;<a href=\"se_frameset.php?se=".$se_id."&phrase=".urlencode($HTTP_POST_VARS["keyphrase"])."\">debug</a>";
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
                   <td><img src="images/spacer.gif" alt="" width="200" height="1"></td>
                 </tr>
                 <tr>
                   <td style="width:30px">
                     <img src="images/<?php echo $image?>">
                   </td>
                   <td style="width:200px" nowrap>
                     <a href="http://<?php echo $o->HOST.$o->SCRIPT."?".str_replace("[__KEYPHRASE__]", urlencode(($se[1])?$HTTP_POST_VARS["keyphrase"]:utf8_decode($HTTP_POST_VARS["keyphrase"])), $o->DATA)?>"><?php echo $o->TITLE?></a>
                   </td>
                   <td style="width:100px">
                     <?php echo $msg?>
                   </td>
                   <td style="width:200px" nowrap>
                     <?php echo $ranking["indexed_page"]; ?>
                   </td>
                 </tr>
               </table>
            <?php /* END RESULT TABLE */
         
            /* immediately flush it to the browser */
              flush();
          } // end SEs loop 
          
          
        /*******************************************************
          3) FINISH THE HTML RESULT PAGE
        ********************************************************/
        ?>
        <hr>
        <h2><?php echo $lang["analyze"]["endofanalysis"]?></h2>

<?php
} // end if($HTTP_POST_VARS["actie"]==...)
?>
</body>
</html>
