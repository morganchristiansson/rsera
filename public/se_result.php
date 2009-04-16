<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : se_result.php
  Desc.   : output of the query from se_top.php, displaying 
            the separated result items which were extracted
            using the given regex's and separator key.

  Version : 0.3
  Author  : Matthijs Koot

  History : 05-05-03 - file created
            11-05-03 - added comments
            30-07-03 - added ADODB layer, in hope for dbms independence...
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            11-12-03 - added 'Show raw HTML'
            19-01-04 - added utf8_before_urlencode fix for e.g. Excite.fr
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
                       renamed 'separator' to 'hit_separator' because of MySQL-4.1 reserved words
*/

/*******************************************************
  1) INPUT VALIDATION
********************************************************/
  if ($HTTP_POST_VARS["host"]=="") { $error .= "- No hostname was given<br>"; }
  if ($HTTP_POST_VARS["path"]=="") { $error .= "- No script path was given<br>"; }
  if ($HTTP_POST_VARS["data"]=="") { $error .= "- No query data was given<br>"; }
  if ($HTTP_POST_VARS["regexp_begin"]=="") { $error .= "- No regexp was given to identify the beginning of the resultlist<br>"; }
  if ($HTTP_POST_VARS["regexp_end"]=="") { $error .= "- No regexp was given to identify the end of the resultlist<br>"; }
  if ($HTTP_POST_VARS["separator"]=="") { $error .= "- No result separator key was given<br>"; }
  if ($HTTP_POST_VARS["langcode"]=="") { $error .= "- No language code was given<br>"; }
  if (isset($error)) { err ($error, $HTTP_POST_VARS); }
  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="css/content.css">
</head>
<body>
<h3>Output</h3>
<table>
 <tr>
  <td>
    <fieldset>

<?php
/*******************************************************
  PROCESS QUERY, DISPLAY OUTPUT
********************************************************/

/*
  Convert all POST/GET data if magic_quotes_gpc is on.
  Note that magic_quotes_gpc cannot be used with ini_set()!
*/
if (get_magic_quotes_gpc() == 1){
    switch ($REQUEST_METHOD)
    {
        case "POST":
        while (list ($key, $val) = each ($HTTP_POST_VARS)){
            $$key = stripslashes($val);
        }
        break;

        case "GET":
        while (list ($key, $val) = each ($HTTP_GET_VARS)){
            $$key = stripslashes($val);
        }
        break;
    }
}

/*******************************************************
   'TEST THESE SETTINGS'
********************************************************/
  if ($HTTP_POST_VARS["action"]=="start") {

    /* assert that a testing phrase was given */
    if ($phrase=="") 
      err( "- No test phrase<br>" );

    if (!isset($HTTP_POST_VARS["utf8_support"])) $phrase = utf8_decode($phrase);
    $querydata  = str_replace("[__KEYPHRASE__]", urlencode($phrase), $data); /* querydata */ 

    /* request page */
      $html = getPage($host, "GET", $path, $querydata);
      if ($html["exit"] == -1) die("<h2>".$html["message"]."</h2>");

    /* parse html */
      if (preg_match("/".$regexp_begin."(.+)".$regexp_end."/is", $html,$templist)) {

        echo "<h1>MATCH - regular expressions seem correct</h1>";
        echo "<h4>Now, make sure that each result item DOESN'T contain the domain name of the next search result!</h4>";
        $searchresults = explodei($separator, $templist[1]);
        
        // start list with result items (as extracted by the given regexp's and separator)
        echo "<ol>";
        for ($i=0; $i < sizeof($searchresults); $i++){
          echo "<hr><li><pre>".htmlentities($searchresults[$i])."</pre></li>";
        }
        echo "</ol>";
      } else {
        echo "<h1>NO MATCH - try other regular expressions</h1>"; 
        echo "<h2>Parsed data</h2><pre>".htmlentities($html)."</pre>";
      }

/*******************************************************
       'SHOW RAW HTML'
********************************************************/
  } else if ($HTTP_POST_VARS["action"]=="showrawhtml") {

    /* assert that a testing phrase was given */
    if ($phrase=="")
      err( "- No test phrase<br>" );

    if (!isset($HTTP_POST_VARS["utf8_support"])) $phrase = utf8_decode($phrase);
    $querydata  = str_replace("[__KEYPHRASE__]", urlencode($phrase), $data); /* querydata */

    /* request page */
      $html = getPage($host, "GET", $path, $querydata);
      echo "<a href=\"http://$host$path?$querydata\">http://$host$path?$querydata</a>";
      echo "<pre>";
      print_r(htmlentities($html));
      echo "</pre>";

/*******************************************************
       'SAVE THESE SETTINGS'
********************************************************/
  } else if ($HTTP_POST_VARS["action"]=="save") {
    $sql_update = "UPDATE $DB_Searchengines 
                   SET
                     datetag_lastupdate = now(), 
                     startkey  = '". addslashes($regexp_begin) ."',
                     endkey    = '". addslashes($regexp_end)   ."',
                     hit_separator = '". addslashes($separator)    ."',
                     noresult  = '". addslashes($noresult)     ."',
                     host      = '". addslashes($host)         ."', 
                     script    = '". addslashes($path)         ."', 
                     utf8_support = '". isset($HTTP_POST_VARS["utf8_support"])     ."',
                     langcode  = '". addslashes($langcode)     ."', 
                     data      = '". addslashes($data)         ."'
                  WHERE zm_id = ". $se;
       echo "<pre>".htmlentities($sql_update)."</pre>";

     if (!$db->Execute($sql_update)) {
       echo "<h2>Error in query</h2>";
       echo "<pre>".htmlentities($sql_update)."</pre>";
     } else echo "<h2>Saved in database</h2>";
   }
?>
    </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
