<?
include_once "../global.inc.php";

/*
  filename: stuff/examples/example_cronjob.php

  
  THIS FILE SHOULD BE PLACED IN THE DOCUMENTROOT OF PHPSERA
  (because of include()'s)

  Matthijs, 2004-05-03 20:25

  This is an example script for automated analysis through
  Unix cronjobs (or alike).

  This script will perform a full analysis of a website, for
  all keywords and search engines, and saves the report. 
  Language check defaults to ON.

  Note that this script periodically generates output, to 
  avoid timeouts when using clients like Lynx. You may
  redirect the output to /dev/null. If you're experiencing
  problems using Lynx from cron, you need to download a
  newer release of Lynx:

  http://lynx.isc.org/release/lynx2.8.4.tar.gz 

  # EXAMPLE SHELL COMMAND
    lynx -dump -auth=<user>:<password> 'http://server/example_cronjob.php' 2>&1 > /dev/null
  (you may discard the -auth param if you don't use .htaccess
  authentication)

*/ 

/*----CONFIG-----*/ 
  $ws_id          = 1; // the ws_id of the website
  $report_author  = "auto"; // just a name like 'robot', 'auto' or whatever
  $email_on_error = ""; // your e-mail address
/*----CONFIG-----*/ 



//----------------------------
// RAMPAGE MODE
//----------------------------
// If the variable below is set to TRUE, no ranking analysis  
// is performed if the keyphrase language doesn't match the 
// searchengine language.
//
// This saves a LOT of bandwidth. But on the other hand you 
// might overlook unexpected rankings which may be of interest
// to you. E.g., you won't know you're #1 at the Fireball.de 
// on searches for "du pain, monsieur". 
//
// An incorrect language setting for either keywords or SEs
// in the database will obviously lead to missing rankings!
//
// You should NOT disable this!
//
$lang_must_match = true;  




/*******************************************************
  1) INPUT VALIDATION AND VERIFICATION
********************************************************/

  $sql_ws = "SELECT url FROM websites WHERE ws_id=$ws_id"  ;
  $result_ws = $db->Execute($sql_ws);
  if ($result_ws->EOF)
    err("No website with such ID [$ws_id]");

  $o = $result_ws->FetchObject();
  $ws_url = $o->URL; 

  $sql_zt = "SELECT zt_id, keyphrase, langcode FROM keyphrases WHERE ws_id=$ws_id"  ;
  $result_zt = $db->Execute($sql_zt);
  

  if ($result_zt->EOF)
    err("No keyphrases for website with ID [$ws_id]");


  $l=0;
  while ($o = $result_zt->FetchNextObject()) { 
    $phrasestxt[$l][0] = $o->ZT_ID; // phrase ID
    $phrasestxt[$l][1] = $o->KEYPHRASE; // phrase contents
    $phrasestxt[$l][2] = $o->LANGCODE; // phrase language code
    $l++;
  }

  /*******************************************************
    2-a) INSERT NEW REPORT
  ********************************************************/
      /* insert a new report */
        $sql_real = "INSERT INTO reports (ws_id,name,rankdate) VALUES ('$ws_id','".strip_tags(urldecode($report_author))."','".date("Y-m-d")."');";
        $db->Execute($sql_real);
    
      /* determine this report's (autoincremented) ID */
        $currentid = $db->Insert_ID();
        
        if (!isset($currentid) || !is_numeric($currentid))
          mail("koot@koot.biz", "SERA cronjob fout", "The ID for the new report could not be retrieved", "");
  

  /*******************************************************
    2-b) LOOP THROUGH THE KEYPHRASES
  ********************************************************/
    for ($k=0; $k < (sizeof($phrasestxt)); $k++) {
      $zt_id  = $phrasestxt[$k][0]; // phrase ID
      $phrase = $phrasestxt[$k][1]; // phrase content
      $phrase_lang = $phrasestxt[$k][2]; // phrase language


    /********* DO NOT REMOVE ********/
      echo "<h1>$phrase</h1>\n";
      flush();
    /********* /DO NOT REMOVE *******/


        /*******************************************************
          2-b-1) LOOP THROUGH THE SEARCH ENGINES FOR THIS PHRASE
        ********************************************************/
        $sql_zm = "SELECT zm_id, title, url, startkey, endkey, separator, host, script, data, langcode, noresult, utf8_support FROM searchengines";
        $result_zm = $db->Execute($sql_zm);

        while ($o = $result_zm->FetchNextObject()) { 
          $se_id = $o->ZM_ID;
          $title = $o->TITLE;
          $url   = $o->URL;
          $startkey = $o->STARTKEY;
          $endkey = $o->ENDKEY;
          $separator = $o->SEPARATOR;
          $host  = $o->HOST;
          $script = $o->SCRIPT;
          $data = $o->DATA;
          $langcode = $o->LANGCODE;
          $noresult = $o->NORESULT;
          $utf8_support = $o->UTF8_SUPPORT


          /********* DO NOT REMOVE ********/
            echo "$se_id, $title<br>\n";
            flush();
          /********* /DO NOT REMOVE *******/


            /* create a search engine for getRanking() */
              $se[0] = $title;     // title (displayed by getRanking() in debug mode)
              $se[1] = $utf8_support;
              $se[2] = $url;     // url
              $se[3] = $startkey;     // startkey
              $se[4] = $endkey;     // endkey
              $se[5] = $separator;     // separator
              $se[6] = $host;     // host
              $se[7] = $script;     // script
              $se[8] = $data;     // data
              $se[9] = $langcode;     // langcode
              $se[10] = $noresult;    // noresult
         
             if (($lang_must_match) &&     // IF language match is required
                 ($se[9]!=$phrase_lang) && // AND no match
                 ($se[9]!="@@") &&         // AND no worldwide SE
                 ($phrase_lang!="@@")      // AND no international keyphrase
                ) continue;                // THEN SKIP!
        
            /* actually get the ranking */
              $ranking = getRanking($ws_url, $phrase, $se);
           
              if ($ranking["position"]<0) {
                logerr("getRanking returns ranking \"".$ranking["position"]."\" for search engine \"".$se[0]."\" on keyphrase \"$phrase\", looking for \"$ws_url\"", $se); 
              } else {
                $sql_rule = "INSERT INTO reportrules 
                             (mt_id,zt_id,zm_id,ranking,indexed_page) 
                           VALUES 
                             ($currentid,$zt_id,$se_id,".$ranking["position"].",'".$ranking["indexed_page"]."');";
                $db->Execute($sql_rule);
              }
            
          } // end SEs loop 
    } // end phrases loop 

?>
