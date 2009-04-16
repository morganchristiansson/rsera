<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : trends.php
  Desc.   : links to graphs w/plotted trends by website and SE

  Version : 0.3
  Author  : Matthijs Koot

  History : 19-06-03 - file created
            30-07-03 - added ADODB layer, in hope for dbms independence...
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<h3><?php echo $lang["trends"]["trends"]?></h3>
<h4>Under construction</h4>
<h5>The graphs may look silly or incorrect if you don't have much analysis data available. The X-axis of the 30-day graph is a serial number, not a real day-of-the-month. I am working on it!</h5>

<?php if (isset($msg)) { echo "<h3>$msg</h3>"; } ?>
<table>
 <tr>
  <td>
    <fieldset>
      <legend><b>Ranking trends</b></legend>
<?php
/*******************************************************
  1) DISPLAY ALL WEBSITES
********************************************************/
  $sql_ws = "SELECT ws_id, url FROM $DB_Websites ORDER BY url";
  $result_ws = $db->Execute($sql_ws);

  if (!$result_ws->EOF) {
    echo "<table>\n";
    while ($o = $result_ws->FetchNextObject()) {
      echo "<tr>\n";
      echo "  <td><a href=\"graph.php?ws_id=$o->WS_ID&rankyear=2009\">$o->URL 2009</a></td>\n";
      echo "</tr>\n";
    }
    echo "</table>\n";
  } else { ?>
          <table cellpadding="10">
            <tr>
              <td>&nbsp;&nbsp;<b>No websites available!</b>&nbsp;&nbsp;</td> 
            </tr>
          </table>
<?php } ?>
    </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
