<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : overview.php
  Desc.   : overview of all ranking reports

  Version : 0.3
  Author  : Matthijs Koot

  History : 29-04-03 - file created
            05-05-03 - changed layout
            11-05-03 - added comments
            19-06-03 - added dropdown w/websites and rankdates 
            30-07-03 - added ADODB layer, in hope for dbms independence...
                       translations completed
            05-08-03 - creuzerm - creuzerm@users.sourceforge.net
                       checked to see if $debugmsg is set before it is echo-ed out
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            17-10-03 - added language items
            21-01-04 - fixed uninitialized $ws_id_last
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/





if (isset($HTTP_GET_VARS["delete"]) && is_numeric($HTTP_GET_VARS["delete"])) {
  $sql_delreport = "DELETE FROM $DB_Reports WHERE mt_id=".$HTTP_GET_VARS["delete"];
  $sql_delrules = "DELETE FROM $DB_Reportrules WHERE mt_id=".$HTTP_GET_VARS["delete"];

  $delreport = $db->Execute($sql_delreport);
  $delrules = $db->Execute($sql_delrules);

  if (!$delrules||!$delreport) {
    $msg = "Error occured while attempting to delete the report. Please verify the removal of report [".$HTTP_GET_VARS["delete"]."] manually.";
  } else {
    $msg = "Report [".$HTTP_GET_VARS["delete"]."] and all it's rules are deleted.";
  }
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript1.2">
<!--
  var sepdates = null;
  <?php
    $sql_mt = "SELECT DISTINCTROW ws_id, date_format(rankdate, '%m') AS rankmonth, date_format(rankdate, '%Y') AS rankyear FROM $DB_Reports ORDER BY ws_id, rankdate";
    $result_mt = $db->Execute($sql_mt);
  
  /* initialize javascript arrays for each website 
     (without this, javascript errors may occur when
     dealing with websites that have no reports yet) */
  while ($o = $result_mt->FetchNextObject()) 
      echo "var site".$o->WS_ID." = new Array();\n";
  
    
  $result_mt->MoveFirst();

  /* fill the arrays with rankdates */
    $ws_id=0; $ws_id_last = 0; //dummy
    while ($o = $result_mt->FetchNextObject()) {
      if ($o->WS_ID != $ws_id_last) {
        $i = 0;
        $ws_id_last = $o->WS_ID;
      }
      echo "site".($o->WS_ID)."[".$i."] = new ranking(\"$o->RANKYEAR"."-"."$o->RANKMONTH\");\n";
      $i++;
    } 
  ?> 
  
  function loadDates(){
    selectedsite=document.formv.ws_id.value;
    rd=eval("site"+selectedsite);
    rdcount = rd.length;
  
    document.formv.rankdates.options.length = 0;
 
    /* loop all keywords for this website*/ 
    for(i=0; i < rdcount; i++){
      document.formv.rankdates.options[i] = new Option(rd[i].rankdate,rd[i].rankdate);
      document.formv.rankdates.options[i].selected=true;
    }
  }
  
  function ranking(yyyymm) {
    this.rankdate=yyyymm;
  }

  function openReport() {
    if (document.formv.ws_id.selectedIndex != 0) { 
      newurl='showreport.php?ws_id='+document.formv.ws_id.value+'&rankdate='+document.formv.rankdates.options[document.formv.rankdates.selectedIndex].value;
      location.href=newurl;
    } else alert('Select a website!')
  }

  function openBadRankings() {
    if (document.formv.ws_id.selectedIndex != 0) { 
      newurl='showreport2.php?ws_id='+document.formv.ws_id.value+'&rankdate='+document.formv.rankdates.options[document.formv.rankdates.selectedIndex].value;
      location.href=newurl;
    } else alert('Select a website!')
  }

//-->	
</script>




</head>
<body>
<h3><?php echo $lang["overview"]["overview"]?></h3>
<?php if (isset($debugmsg)) echo $debugmsg; ?>
<?php if (isset($msg)) { echo "<h3>$msg</h3>"; } ?>
<table>
 <tr>
  <td>
    <fieldset>
        <h3><?php echo $lang["overview"]["monthreport"]?></h3>
        <?php echo $lang["overview"]["monthreport_info"]?>
        <table cellpadding="0">
          <tr style="height:1px;">
            <td colspan="2"><img src="images/spacer.gif" alt="" width="500" height="1"></td>
          </tr>
          <tr>
            <td valign="top" align="right">
              <?php
                $sql_ws = "SELECT DISTINCTROW 
                             $DB_Websites.ws_id, 
                             url 
                           FROM 
                             $DB_Websites INNER JOIN $DB_Reports ON 
                             $DB_Websites.ws_id = $DB_Reports.ws_id";

                $result_ws = $db->Execute($sql_ws);
             
                if (!$result_ws->EOF) {
                  echo "<form name=formv action=$PHP_SELF>\n";
                  echo "<select name=ws_id onchange=\"if (this.selectedIndex!=0) loadDates(this.value)\">\n";
                    echo "<option value=0>".$lang["overview"]["selectwebsite"]."</option>\n";
                  while ($o = $result_ws->FetchNextObject()) 
                    echo "<option value=$o->WS_ID>$o->URL</option>\n";
               ?>
                    </select>
                    <select name=rankdates size=12 style=width:150px></select>
                    </form>
                  </td>
                  <td align=left>
                    <input type=button name=show value='<?php echo $lang["showreport"]["goodrankings"]?>' onclick="javascript:openReport()"><br>
                    <input type=button name=show value='<?php echo $lang["showreport"]["badrankings"]?>' onclick="javascript:openBadRankings()">
              <?php } else { ?>
                  <h2>No available websites</h2>
              <?php } ?>
            </td>        
          </tr>
        </table>     
        <hr>
        <h3><?php echo $lang["overview"]["singlereport"]?></h3>
        <?php echo $lang["overview"]["singlereport_info"]?>
      
      
      
      
      
<?php
/*******************************************************
  1) LOAD ALL REPORTS 
********************************************************/
  $sql_rp = "SELECT 
               mt_id, 
               name,
               rankdate,
               url,
               $DB_Reports.ws_id
             FROM
               $DB_Reports,$DB_Websites 
             WHERE 
               $DB_Reports.ws_id = $DB_Websites.ws_id 
             ORDER BY 
               rankdate DESC";

  $result_rp = $db->Execute($sql_rp);

  if (!$result_rp->EOF) {
  ?>
        <table cellpadding="0">
          <tr style="height:1px;">
            <td><img src="images/spacer.gif" alt="" width="40" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="80" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="200" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="100" height="1"></td>
            <td colspan="2"><img src="images/spacer.gif" alt="" width="100" height="1"></td>
          </tr>     
          <tr>
            <th>&nbsp;</th>
            <th>Date:</th>
            <th>Website:</th>
            <th>Name:</th>
            <th>Rankings:</th>
            <th>&nbsp;</th>
          </tr>
  <?php
  /*******************************************************
    1-a) DISPLAY REPORT SUMMARY
  ********************************************************/
    while ($o = $result_rp->FetchNextObject()) {
      echo "<tr>";
      echo "  <td><a href=\"showreport.php?mt_id=$o->MT_ID&ws_id=$o->WS_ID&rankdate=$o->RANKDATE&name=$o->NAME\">show</a></td>";
      echo "  <td>" . $o->RANKDATE. "</td>";
      echo "  <td>" . $o->URL . "</td>";
      echo "  <td>" . $o->NAME . "</td>";

      $sql_rr = "SELECT COUNT(zt_id) AS rrcount FROM $DB_Reportrules WHERE mt_id=$o->MT_ID";
      $result_rr = $db->Execute($sql_rr);  
       
      $o_rule = $result_rr->FetchObject();
      echo "  <td colspan=\"4\">".$o_rule->RRCOUNT." rule(s)</td>";
      echo "  <td><a href=\"overview.php?delete=$o->MT_ID\" onclick=\"return confirm('Are you sure you want to delete this report from $o->RANKDATE?')\">delete</a></td>";
      echo "</tr>";    
      
      
    }
    ?>
        </table>
    <?php } else { ?>
          <table cellpadding="10">
            <tr>
              <td>&nbsp;&nbsp;<b>No ranking reports available!</b>&nbsp;&nbsp;</td> 
            </tr>
          </table>
    <?php } ?>
    </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
