<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : errorlog.php
  Desc.   : Show all failed analysis

  Version : 0.3
  Author  : Matthijs Koot

  History : 21-08-03 - file created
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            17-10-03 - added GROUP BY in SQL statement to provide more useful information
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
  .help { 
    position   : absolute; 
    visibility : hidden; 
    color      : #004499; 
  }
//-->
</style>
</head>
<body>
<h3>Last 50 Analysis Failures</h3>
<p>This page is provided as a means for you (and us) to maintain high quality analysis data. When regex errors occur, you (and we) need to update the settings for that search engine. A search engine will only be displayed once per ranking date.</p>
<?php if (isset($msg)) echo $msg; ?>
<table>
 <tr>
  <td>
      <?php
        $sql_err = "SELECT
  $DB_Searchengines.zm_id,
  $DB_Searchengines.title, 
  $DB_Reportrules.ranking, 
  $DB_Keyphrases.keyphrase, 
  $DB_Reports.rankdate 
FROM 
  $DB_Reports,
  $DB_Searchengines,
  $DB_Reportrules,
  $DB_Keyphrases 
WHERE
  $DB_Keyphrases.zt_id = $DB_Reportrules.zt_id 
  AND $DB_Searchengines.zm_id = $DB_Reportrules.zm_id 
  AND $DB_Reportrules.ranking < 0 
  AND $DB_Reportrules.mt_id = $DB_Reports.mt_id
GROUP BY
  $DB_Reports.rankdate,
  $DB_Searchengines.title
ORDER BY
  $DB_Reports.rankdate DESC,
  $DB_Searchengines.title ASC
LIMIT 0,50";


        $result_err = $db->Execute($sql_err);
        if (!$result_err->EOF) {
      ?>
    
      <table>
          <tr style="height:1px;">
            <td><img src="images/spacer.gif" alt="" width="90" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="120" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
          </tr>
        <tr>
          <th><?php echo $lang["newser"]["date"]?>:</th>
          <th><?php echo $lang["showreport"]["se"]?>:</th>
          <th><?php echo $lang["showreport"]["keyphrase"]?>:</th>
          <th>Error</th>
        </tr>
      <?php
        while ($o = $result_err->FetchNextObject()) {
          switch ($o->RANKING) {
            case -3: $errormsg = "regex error (but URL found)"; break;
            case -2: $errormsg = "connect error"; break; 
            case -1: $errormsg = "regex error"; break;
            default: $errormsg = "-";
          }

          echo "<tr>\n";
          echo "  <td>$o->RANKDATE</td>\n";
          echo "  <td>$o->TITLE</td>\n";
          echo "  <td>$o->KEYPHRASE</td>\n";
          echo "  <td>$errormsg &nbsp;";
          echo "    <a href=\"se_frameset.php?se=".$o->ZM_ID."&phrase=".urlencode($o->KEYPHRASE)."\">debug</a>";
          echo "</td>\n";
          echo "</tr>\n";
        }
      ?>
      </table>
<?php
    } else {
      echo "<table cellpadding=10><tr><td><b>No failures recorded... yet :)</b></td></tr></table>\n";
    }
?>

    </td>
  </tr>
</table>
</body>
</html>
