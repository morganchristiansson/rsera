<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : showreport.php
  Desc.   : list all rankings made during the last report
            that was compiled for the given website, month 
            and year

  Version : 0.3
  Author  : Matthijs Koot

  History : 19-06-03 - file created
            30-07-03 - added ADODB layer, in hope for dbms independence...
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            07-10-03 - added listing of inactive/unavailable keyphrases
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

// only available if user request a single report 
  $mt_id = isset($HTTP_GET_VARS["mt_id"])?$HTTP_GET_VARS["mt_id"]:"";
  $name = isset($HTTP_GET_VARS["name"])?$HTTP_GET_VARS["name"]:"";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<h1><?php echo $lang["showreport"]["goodrankings"]; ?></h1>

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


  // build SQL statement
  if (is_numeric($mt_id) && $mt_id > 0) {
    /* list all rankings from the selected report */
               //AND $DB_Reportrules.ranking > 0
    $sql_rp = "SELECT
               $DB_Reportrules.ranking,
               $DB_Reportrules.indexed_page,
               $DB_Searchengines.title,
               $DB_Keyphrases.keyphrase,
               $DB_Keyphrases.langcode,
               $DB_Keyphrases.zt_id,
               $DB_Reportrules.mt_id
             FROM
               $DB_Reports,
               $DB_Keyphrases,
               $DB_Reportrules,
               $DB_Searchengines
             WHERE
               $DB_Reports.mt_id = $mt_id
               AND $DB_Reportrules.mt_id = $DB_Reports.mt_id
               AND $DB_Keyphrases.zt_id = $DB_Reportrules.zt_id
               AND $DB_Reportrules.zm_id = $DB_Searchengines.zm_id
             ORDER BY
               $DB_Reportrules.ranking";
    ?>
    <h2><?php echo $lang["showreport"]["singlereport"]." $mt_id"?></h2>
    <p><?php echo $lang["showreport"]["done_by"]." $name".", $rankdate"?></p>
    <h3><?php echo $ws_url?></h3>
    <?php
  } else {
    /* list all rankings registered during the last 
       report in this month.  
    */
    $sql_rp = "SELECT
               $DB_Reportrules.ranking,
               $DB_Reportrules.indexed_page,
               $DB_Searchengines.title,
               $DB_Keyphrases.keyphrase,
               $DB_Keyphrases.langcode,
               $DB_Keyphrases.zt_id,
               MAX($DB_Reports.mt_id) AS latestid,
               $DB_Reportrules.mt_id
             FROM
               $DB_Reports,
               $DB_Keyphrases,
               $DB_Reportrules,
               $DB_Searchengines
             WHERE
               $DB_Reports.ws_id = $ws_id
               AND month ($DB_Reports.rankdate) = $rankmonth
               AND year ($DB_Reports.rankdate) = $rankyear
               AND $DB_Keyphrases.zt_id = $DB_Reportrules.zt_id
               AND $DB_Reportrules.zm_id = $DB_Searchengines.zm_id
             GROUP BY
               $DB_Reportrules.mt_id,
               $DB_Reportrules.ranking,
               $DB_Reportrules.indexed_page,
               $DB_Searchengines.title,
               $DB_Keyphrases.keyphrase
             HAVING
               $DB_Reportrules.mt_id = latestid
             ORDER BY
               $DB_Reportrules.ranking";

    ?>
    <h2><?php echo $lang["showreport"]["monthreport"]." ".$rankyear."-".$rankmonth?></h2>
    <h3><?php echo $ws_url?></h3>
    <i><?php echo $lang["showreport"]["lastonly"]?></i>
    <?php
  }

  $result_rp = $db->Execute($sql_rp);

  $reportid = 0;
  if (!$result_rp->EOF) {
  ?>

        <table cellpadding="3" cellspacing="0">
          <tr style="height:1px;">
            <td><img src="images/spacer.gif" alt="" width="20" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="40" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="150" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="150" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="20" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="20" height="1"></td>
          </tr>
          <tr>      
          <tr>
            <th>&nbsp;</th>
            <th><?php echo $lang["showreport"]["ranking"]?>:</th>
            <th><?php echo $lang["showreport"]["se"]?>:</th>
            <th><?php echo $lang["showreport"]["keyphrase"]?>:</th>
            <th>&nbsp;</th>
            <th><?php echo $lang["analyze"]["indexed_page"]?>:</th>
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

      /* only show 'real' rankings */
      if ($o->RANKING < 1) continue;

      if ($o->LANGCODE == "@@") $flag = "all"; else $flag = $o->LANGCODE;
      /* show the ranking */
      $style=1+($style%2); /* flip row CSS */
      echo "<tr>";
      echo "  <td>&nbsp;</td>";
      echo "  <td class='td$style' align='center'><b>" . $o->RANKING . "</b></td>";
      echo "  <td class='td$style'>" . $o->TITLE . "</td>";
      echo "  <td class='td$style'>" . $o->KEYPHRASE . "&nbsp;&nbsp;</td>";
      echo "  <td class='td$style'><img src='images/flags/$flag.gif' alt='".$lang["lc"][$o->LANGCODE]."'></td>";
      echo "  <td class='td$style'>" . $o->INDEXED_PAGE. "</td>";
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
