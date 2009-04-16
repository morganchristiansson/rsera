<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : showreport2.php
  Desc.   : list keyphrases and searchengines which gave a "no listing" (ranking=0) analysis result in the given month

  Version : 0.3
  Author  : Matthijs Koot

  History : 07-10-03 - file created
            17-10-03 - added language items
            21-01-04 - fixed bug in listing of these-were-not-analyzed keyphrases
            22-01-04 - flag images are shown besides the keyphrases from now on
            15-04-04 - now displaying $ranking["indexed_page"]
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/

$ws_id = isset($HTTP_GET_VARS["ws_id"])?$HTTP_GET_VARS["ws_id"]:0;
$rankdate = isset($HTTP_GET_VARS["rankdate"])?$HTTP_GET_VARS["rankdate"]:0;

$rankmonth = substr($rankdate, 5, 2);
$rankyear = substr($rankdate, 0, 4);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<h1><?php echo $lang["showreport"]["badrankings"]; ?></h1>
<table>
 <tr>
  <td>
    <fieldset>
<?php
/*******************************************************
  1) LOAD SELECTED REPORT, OR THE LAST REPORT FOR THE SELECTED MONTH
********************************************************/

  // get website URL
  $sql_ws = "SELECT url FROM $DB_Websites WHERE ws_id=$ws_id";
  $result_ws = $db->Execute($sql_ws);
 
  if (!$result_ws->EOF) { 
    $o = $result_ws->FetchObject();
    $ws_url = $o->URL;
  }

  $sql_zt = "SELECT count(*) AS phrasecount FROM $DB_Keyphrases WHERE ws_id=$ws_id";
  $result_zt = $db->Execute($sql_zt);

  $total_phrasecount = 0;
  if (!$result_zt->EOF) {
    $o = $result_zt->FetchObject();
    $total_phrasecount = $o->PHRASECOUNT;
  } else
    err("Error: no keyphrases available for this website?");


    /* list all rankings registered during the last 
       report in this month.  
    */
               //AND $DB_Reportrules.ranking > 0
    $sql_rp = "SELECT 
                 $DB_Searchengines.title, 
                 $DB_Keyphrases.keyphrase,
                 $DB_Keyphrases.langcode,
                 $DB_Reportrules.ranking,
                 $DB_Reportrules.zt_id
               FROM 
                 $DB_Reportrules,
                 $DB_Reports,
                 $DB_Searchengines,
                 $DB_Keyphrases
               WHERE
                 $DB_Reports.ws_id = $ws_id
                 AND $DB_Reports.mt_id = $DB_Reportrules.mt_id
                 AND month ($DB_Reports.rankdate) = $rankmonth
                 AND year ($DB_Reports.rankdate) = $rankyear
                 AND $DB_Keyphrases.zt_id = $DB_Reportrules.zt_id
                 AND $DB_Searchengines.zm_id = $DB_Reportrules.zm_id
               GROUP BY
                 $DB_Searchengines.title,
                 $DB_Reportrules.zt_id
               ORDER BY
                 title,
                 keyphrase";

    //echo $sql_rp;


    ?>
    <h2><?php echo $lang["showreport"]["monthreport"]." ".$rankyear."-".$rankmonth?></h2>
    <h3><?php echo $ws_url?></h3>
    <i><?php echo $lang["showreport"]["lastonly"]?></i>
    <?php

  $result_rp = $db->Execute($sql_rp);

  $reportid = 0;
  if (!$result_rp->EOF) {
  ?>

        <table cellpadding="3" cellspacing="0">
          <tr style="height:1px;">
            <td><img src="images/spacer.gif" alt="" width="20" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="100" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="150" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="150" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="20" height="1"></td>
          </tr>
          <tr>      
          <tr>
            <th>&nbsp;</th>
            <th><?php echo $lang["showreport"]["ranking"]?>:</th>
            <th><?php echo $lang["showreport"]["se"]?>:</th>
            <th><?php echo $lang["showreport"]["keyphrase"]?>:</th>
            <th>&nbsp;</th>
          </tr>
  <?php
  /*******************************************************
    1-a) DISPLAY REPORT SUMMARY
  ********************************************************/
    $analyzed_keyphrases=array(); $style=0;
    while ($o = $result_rp->FetchNextObject()) 
    {
      /* add to list of keyphrases that were analyzed for this report */
      if (!in_array($o->ZT_ID, $analyzed_keyphrases))
        array_push($analyzed_keyphrases, $o->ZT_ID);

      if ($o->RANKING == 0) continue;

      if ($o->LANGCODE == "@@") $flag = "all"; else $flag = $o->LANGCODE;
      /* show the ranking */
      $style=1+($style%2); /* flip row CSS */
      echo "<tr>";
      echo "  <td>&nbsp;</td>";
      echo "  <td class='td$style' align='center'>(not found)</td>";
      echo "  <td class='td$style'>" . $o->TITLE . "</td>";
      echo "  <td class='td$style'>" . $o->KEYPHRASE . "&nbsp;&nbsp;</td>";
      echo "  <td class='td$style'><img src='images/flags/$flag.gif' alt='".$lang["lc"][$o->LANGCODE]."'></td>";
      echo "</tr>\n";
    }
    ?>
        </table>
    <?php
      if (sizeof($analyzed_keyphrases) < $total_phrasecount) { 
        echo "<p>These keyphrases were not analyzed (they were either inactive or added after this report was created):</p>";
        $reportid = $o->LATESTID;

        $comma_separated_ids = implode(",", $analyzed_keyphrases);
        $sql_phrasenrs = "SELECT 
                            keyphrase 
                          FROM 
                            keyphrases 
                          WHERE 
                            ws_id=$ws_id 
                            AND zt_id NOT IN ($comma_separated_ids) 
                          ORDER BY keyphrase";

        $result_na = $db->Execute($sql_phrasenrs);

        echo "<ul>";
        while ($o = $result_na->FetchNextObject())
          echo "<li>$o->KEYPHRASE</li>";
        echo "</ul>";

      } 
      
    ?>
    <?php } else { ?>
          <table cellpadding="10">
            <tr>
              <td>&nbsp;&nbsp;<b>No ranking details available! Possible database inconsistency?</b>&nbsp;&nbsp;</td> 
            </tr>
          </table>
    <?php } ?>
    <br>
    </fieldset>
  </td>
 </tr>
</table>
<a href="overview.php">Back</a>
</body>
</html>
