<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : keyphrases.php
  Desc.   : Keyphrase Management

  Version : 0.3
  Author  : Matthijs Koot

  History : 24-07-03 - file created
            28-07-03 - delete now involves 2-step model, added language override option
            29-07-03 - added ADODB layer, in hope for dbms independence...
            30-07-03 - translation completed
            05-08-03 - creuzerm - creuzerm@users.sourceforge.net
                       corrected an incorrect variable name
                       added isset() to several $HTTP_GET_VARS to check for empty variabels
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            05-10-03 - added option to "activate" and "inactivate" keyphrases
                       added datetag, which is set on INSERT (use it to keep track of which keyphrases
                       were added at what point of time)
                       fixed incorrect display of 'special' characters in keyphrases
            19-01-04 - $lang["keyphrases"]["default_lang"] is now used for selected the default language
            21-01-04 - fixed several uninitialized variables
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
            04-05-04 - implemented a TEXTAREA to add multiple keyphrases at once
                       added keyphrase priority setting (for trend graphs)
*/

if (isset($HTTP_GET_VARS["zt"]) && !is_numeric($HTTP_GET_VARS["zt"]))
  err("Received keyphrase ID is invalid!");
elseif (isset($HTTP_GET_VARS["ws"]) && !is_numeric($HTTP_GET_VARS["ws"]))
  err("Received website ID is invalid!");
else {
  $zt = (isset($HTTP_GET_VARS["zt"])) ? $HTTP_GET_VARS["zt"] : '';
  $ws = (isset($HTTP_GET_VARS["ws"])) ? $HTTP_GET_VARS["ws"] : '';

  /*
    phpSERA mostly uses a 2-step method for delete actions:
    1) The first step is the immediate deletion of the object
    requested to be deleted.
    2) The second step is the optional deletion of all data
    solely related to the deleted object.
   */
  if ((isset($HTTP_GET_VARS["action"])) && ($HTTP_GET_VARS["action"] == "delete")) {
    $sql_delete_rr = "DELETE FROM $DB_Reportrules WHERE zt_id=$zt";
    $sql_delete_zt = "DELETE FROM $DB_Keyphrases WHERE id=$zt";

    if ($HTTP_GET_VARS["step"] == 1) {
      $sql_zt = "SELECT * FROM $DB_Reportrules WHERE zt_id=$zt";
      $result_tmp= $db->Execute($sql_zt);
      if (!$result_tmp->EOF) $i = $result_tmp->RecordCount();
      else $i=0;

      $db->Execute($sql_delete_zt);
      $msg = "Keyphrase with ID $zt deleted.";
      if ($i > 0) {
        $msg .= " There are $i records which were solely related to this keyphrase. Therefore, you may want to <a href=$PHP_SELF?action=delete&step=2&zt=$zt&ws=$ws>execute this delete query</a> to delete them: <p style='font-weight:bold'>$sql_delete_rr</p>";
      } else {
        $msg .= " No related analysis data found, so none was deleted.";
      }
    } elseif ($HTTP_GET_VARS["step"] == 2) {  
      $db->Execute($sql_delete_rr);
      $msg = "All related analysis data deleted.";
    } else {
      $msg = "Uhm... you are trying to delete, but no valid step number was received.";
    }

  } elseif (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "add") {
    if (strlen($HTTP_GET_VARS["ws"])==0)
      err("No website ID was given!");  

    if (isset($HTTP_GET_VARS["lang_override"]) ) 
      $langcode = "@@";
    else {
      if (strlen($HTTP_GET_VARS["langcode"])==2)
        $langcode = $HTTP_GET_VARS["langcode"];
      else
        err("No valid ISO-639 language code was given!");  
    }
  
    $keyphrases = str_replace("\r", "", addslashes($HTTP_GET_VARS["keyphrase"])); // Windows clients
    $keyphrases = explode("\n", $keyphrases);
    $msg = "";

    foreach($keyphrases as $value) {
      $value = trim($value);
      $sql_add_zt = "INSERT INTO $DB_Keyphrases (ws_id, langcode, keyphrase, datetag) VALUES ($ws,'$langcode', '$value', now())";

      $db->Execute($sql_add_zt);
      $msg .= "Keyphrase [".htmlentities($value)."] added.<br>";
    }
  } elseif (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "set_inactive") {
    $sql_inactivate = "UPDATE keyphrases SET is_active=0 WHERE id=".$zt;
    $db->Execute($sql_inactivate); 
  } elseif (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "set_active") {
    $sql_activate = "UPDATE keyphrases SET is_active=1 WHERE id=".$zt;
    $db->Execute($sql_activate); 
  } elseif (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "prioritize") {
    if (isset($HTTP_GET_VARS["s"]) && is_numeric($HTTP_GET_VARS["s"])) {
      $sql_prioritize = "UPDATE keyphrases SET priority=".$HTTP_GET_VARS["s"]." WHERE id=".$zt;
      $db->Execute($sql_prioritize); 
    }
    else
      err("No valid priority was given!");
  }
} 

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
<h3>Keyphrase Management</h3>
<?php if (isset($msg)) echo $msg; ?>
<table>
 <tr>
  <td>
      <form action="<?php echo $PHP_SELF?>" method="get" name="changews">
            <select name="ws" style="width:250px" onchange="if (selectedIndex>0) submit()">
              <option value=""><?php echo $lang["keyphrases"]["select_a_website"]?></option>
            <?php
               /*******************************
                 1) LOAD ALL WEBSITES
               *******************************/
                 $sql_ws     = "SELECT ws_id, url FROM $DB_Websites";
                 $result_ws  = $db->Execute($sql_ws);

                 while ($o = $result_ws->FetchNextObject()) {
                   echo "<option value=\"$o->WS_ID\"";
                   if ($o->WS_ID == $ws) { echo " selected"; $thisurl=$o->URL; }
                   echo ">$o->URL</option>\n";
                 }
              ?>
            </select>
      </form>
<?php
  if ($ws) {
?>
    <fieldset>
      <legend><b><?php echo $lang["keyphrases"]["add_a_keyphrase"]?></b></legend>
      <form action="<?php echo $PHP_SELF?>" method="get" name="wsform" onsubmit="if (this.keyphrase=='') { alert('Enter keyphrase first'); return false; } else if (this.langcode.selectedIndex==0 && !this.lang_override.checked) { alert ('Select a language'); return false; }">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="ws" value="<?php echo $ws?>">
          <!-- <input type="text" name="keyphrase">-->
          <?php echo $lang["keyphrases"]["keyphrase"]?>: <textarea type="text" name="keyphrase"></textarea> (one per line)<br>
          <?php echo $lang["keyphrases"]["language"]?>: <select name="langcode">
            <option value=""></option>
            <?php
               /*******************************
                 LOAD ALL LANGUAGES
               *******************************/
                 $languages = array("en","nl","de","fr","it","sv","es","no","fi","da","pl");
                 for ($l = 0; $l < count($languages); $l++) 
                 {
                   $sel = ($languages[$l] == $lang["keyphrases"]["default_lang"]) ? " selected" : "";
                   echo "<option value=\"".$languages[$l]."\"".$sel.">".$lang["lc"][$languages[$l]]."</option>\n";
                 }
            ?>
        </select><br>
        <div style="font-size: 10px">
        <input type="checkbox" name="lang_override" value="1" onclick="if (this.checked) { langcode.disabled = true; langcode.style.background = '#CCCCCC'; } else { langcode.disabled = false; langcode.style.background = '#FFFFFF'; } blur();"><?php echo $lang["keyphrases"]["intlkeyphrase"]?></div>
        <input type="submit" value="<?php echo $lang["keyphrases"]["add"]?>">
      </form>
      </fieldset>
      <fieldset>
        <legend><b><?php echo $lang["keyphrases"]["current_keyphrases"]." ($thisurl)"?></b></legend>
      <?php
        $sql_zt = "SELECT id, keyphrase, langcode, is_active, datetag, priority FROM $DB_Keyphrases WHERE ws_id=$ws ORDER BY langcode, keyphrase";
        $result_zt = $db->Execute($sql_zt);
        if (!$result_zt->EOF) {
      ?>
    
      <table>
          <tr style="height:1px;">
            <td><img src="images/spacer.gif" alt="" width="150" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
            <td><img src="images/spacer.gif" alt="" width="50" height="1"></td>
          </tr>
        <tr>
          <th><?php echo $lang["keyphrases"]["keyphrase"]?>:</th>
          <th><?php echo $lang["keyphrases"]["language"]?>:</th>
          <th>Status:</th>
          <th>Added:</th>
          <th>Priority:</th>
          <th>&nbsp;</th>
        </tr>
      <?php
        while ($o = $result_zt->FetchNextObject()) {
          if ($o->LANGCODE == "@@") $flag = "all"; else $flag = $o->LANGCODE;

          /* handle keyphrase status: 
             is_active == true --> keyphrase will be analyzed for next ranking report
             is_active != true --> keyphrase will NOT be analyzed for next ranking report
          */
          if ($o->IS_ACTIVE) 
            $statushtml = "<a href=$PHP_SELF?action=set_inactive&zt=$o->ZT_ID&ws=$ws>inactivate</a>";
          else 
            $statushtml = "<a href=$PHP_SELF?action=set_active&zt=$o->ZT_ID&ws=$ws>activate</a>";

          $deletehtml = "<a href=$PHP_SELF?action=delete&step=1&zt=$o->ZT_ID&ws=$ws onclick=\"return confirm('Are you SURE you want to delete \'$o->KEYPHRASE\'? All analysis data related to this keyphrase will become useless.')\">".$lang["keyphrases"]["delete"]."</a>";

          echo "<tr>\n";

          if ($o->PRIORITY == 1)
            echo "  <td><b>".$o->KEYPHRASE."</b></td>\n";
          else
            echo "  <td>".$o->KEYPHRASE."</td>\n";

          echo "  <td><img src=images/flags/$flag.gif alt='".$lang["lc"][$o->LANGCODE]."'></td>\n";
          echo "  <td>$statushtml</td>\n";
          echo "  <td>$o->DATETAG</td>\n";
          echo "  <td>\n";
          echo " <select name=priority style=width:80px onchange=\"javascript:location.href='$PHP_SELF?action=prioritize&zt=$o->ZT_ID&ws=$ws&s='+this.value\">\n";

                 // Handle keyphrase priorities (used in trend graphs)
                 for ($i=0; $i < 4; $i++) 
                     if ($o->PRIORITY == $i)
                       echo "<option value=\"$i\" selected>".$lang["keyphrases"]["priorities"][$i]."</option>\n";
                     else
                       echo "<option value=\"$i\">".$lang["keyphrases"]["priorities"][$i]."</option>\n";

          echo "</select>\n";
          echo "  </td>\n";
          echo "  <td>$deletehtml</td>\n";
          echo "</tr>\n";
        }
      ?>
      </table>
<?php
    } else {
      echo "<table cellpadding=10><tr><td><b>No keyphrases yet!</b></td></tr></table>\n";
    }
    echo "</fieldset>\n";
  } 
?>

    </td>
  </tr>
</table>
<h2><?php echo $lang["newser"]["explanation_header"]?></h2>
<p><?php echo $lang["keyphrases"]["usage_info"]?></p>
</body>
</html>
