<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : graph.php 
  Desc.   : Display SEP statistics graph

  Version : 0.3
  Author  : Matthijs Koot

  History : 19-06-03 - file created
            04-08-03 - salvaged by backup, added ADODB 
            08-08-03 - added isset() checks
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
            04-05-04 - searchengines now sorted by title
                       implemented filter on keyphrase priority
                       implemented filter on keyphrase language
*/

  if (!isset($_GET["ws_id"]) || !is_numeric($_GET["ws_id"]))
    err("No valid [ws_id] given!");
 
  $sql_ws = "SELECT url FROM $DB_Websites WHERE ws_id=".$_GET["ws_id"];
  $result_ws = $db->Execute($sql_ws);
 
  if (!$result_ws->EOF) {
    $ob = $result_ws->FetchObject();
    $url = $ob->URL;
  } else $url = "";
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width='300' cellspacing='5'>
  <tr>
    <td>
      <p>
        <fieldset>
          <table width='300' cellspacing='5'>
            <tr>
              <td align="center">
                <h2><?php echo $lang["trends"]["trends"]?></h2>
              </td>
            </tr>
            <tr>
              <td align="center">
                <h3><?php echo $url.", ".$_GET["rankyear"]?></h3>
              </td>
            </tr>
            <tr>
              <td align="center">
<?php
  $sql_zm = "SELECT zm_id, title FROM $DB_Searchengines ORDER BY title";
  $result_zm = $db->Execute($sql_zm);
  
  if (!$result_zm->EOF) {
    if (isset($_GET["zm_id"]))
      $currzm = $_GET["zm_id"];
    else
      $currzm = "";

    // keyphrase priority filter
    $priority = (isset($_GET["priority"]) && is_numeric($_GET["priority"]))?$_GET["priority"]:"";

    // keyphrase language filter
    $langcode = (isset($_GET["langcode"]) && $_GET["langcode"]!="")?$_GET["langcode"]:"";

    echo "<form name=zm action=$PHP_SELF method=get>\n";
    echo "<select name=zm_id onchange=\"javascript:if (this.selectedIndex!=0) { newurl='$PHP_SELF?zm_id='+this.value+'&ws_id=$ws_id&rankyear=$rankyear&priority=$priority&langcode=$langcode';location.href=newurl;}\">\n";
      echo "<option value=0>".$lang["graph"]["select_a_se"]."</option>";
    while ($ob = $result_zm->FetchNextObject()) {
      echo "<option value=$ob->ZM_ID";
      if ($ob->ZM_ID == $currzm) echo " selected";
      echo ">$ob->TITLE</option>\n";
    }
    echo "</select>\n</form>\n";

    echo "Filter by keyphrase priority:";
    echo " <select name=priority style=width:80px onchange=\"javascript:location.href='$PHP_SELF?zm_id=".$currzm."&ws_id=$ws_id&rankyear=$rankyear&langcode=$langcode&priority='+this.value\">\n";
    // Keyphrase priority filter
    for ($i=0; $i < 4; $i++)
      if ($priority == $i)
        echo "<option value=\"$i\" selected>".$lang["keyphrases"]["priorities"][$i]."</option>\n";
      else
        echo "<option value=\"$i\">".$lang["keyphrases"]["priorities"][$i]."</option>\n";

    echo "</select>\n<br>";
   
    echo "Filter by keyphrase language:";
    echo " <select name=langcode style=width:80px onchange=\"javascript:location.href='$PHP_SELF?zm_id=".$currzm."&ws_id=$ws_id&rankyear=$rankyear&priority=$priority&langcode='+this.value\">\n";
    echo " <option value=''></option>\n";

         /*******************************
            LOAD ALL LANGUAGES
         *******************************/
           $languages = array("en","nl","de","fr","it","sv","es","no","fi","da","pl");
           for ($l = 0; $l < count($languages); $l++)
           {
             $sel = ($languages[$l] == $langcode) ? " selected" : "";
             echo "<option value=\"".$languages[$l]."\"".$sel.">".$lang["lc"][$languages[$l]]."</option>\n";
           }
    echo "</select>\n";

  } else {
    echo "<h2>No available search engines</h2>\n";
  }
?>
              </td>
            </tr>
<?php if (isset($_GET["zm_id"]) && is_numeric($_GET["zm_id"])) { ?>
            <tr>
              <td nowrap>
                <img src="send_year_graph.php?zm_id=<?php echo $_GET["zm_id"]?>&ws_id=<?php echo $_GET["ws_id"]?>&rankyear=<?php echo $_GET["rankyear"]?>&priority=<?php echo $priority?>&langcode=<?php echo $langcode?>">
              </td>
            </tr>
            <tr>
              <td nowrap>
<!--                <img src="send_30day_graph.php?zm_id=<?php echo $_GET["zm_id"]?>&ws_id=<?php echo $_GET["ws_id"]?>&rankyear=<?php echo $_GET["rankyear"]?>&priority=<?php echo $priority?>&langcode=<?php echo $langcode?>">-->
<script type="text/javascript" src="/javascripts/swfobject.js"></script>
      <div id="flash_content_l4hO4iis"></div>
      <script type="text/javascript">
      swfobject.embedSWF("/open-flash-chart.swf", "flash_content_l4hO4iis", "800", "700", "9.0.0", "expressInstall.swf",{"data-file":"%2Ftest_it%2Fgraph_code%3Fzm_id%3D<?php echo $_GET["zm_id"]?>%26ws_id%3D<?php echo $_GET["ws_id"]?>%26rankyear%3D<?php echo $_GET["rankyear"]?>%26priority%3D<?php echo $priority?>%26langcode%3D<?php echo $langcode?>"}, {"wmode":"transparent"});
      </script>
      <noscript>
        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="600" height="600" id="chart_l4hO4iis" align="middle" wmode="transparent">
          <param name="allowScriptAccess" value="sameDomain" />
          <param name="movie" value="/open-flash-chart.swf?data=%2Ftest_it%2Fgraph_code%3Fzm_id%3D8" />
          <param name="quality" value="high" />

          <param name="bgcolor" value="#FFFFFF" />
          <embed src="/open-flash-chart.swf?data=%2Ftest_it%2Fgraph_code%3Fzm_id%3D8" quality="high" bgcolor="#FFFFFF" width="600" height="600" name="chart_l4hO4iis" align="middle"  allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" id="chart_l4hO4iis" />
        </object>
      </noscript>
              </td>
            </tr>
<?php } ?>
          </table>		
        </fieldset>
      </p>
    </td>
  </tr>
</table>
<a href="trends.php">Back</a>
</body>
</html>
