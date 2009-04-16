<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : websites.php
  Desc.   : Website Management

  Version : 0.3
  Author  : Matthijs Koot

  History : 24-07-03 - file created
            28-07-03 - delete action now involves a 2-step model
            29-07-03 - completed translations
            30-07-03 - added ADODB layer, in hope for dbms independence...
            05-08-03 - creuzerm - creuzerm@users.sourceforge.net
                       added a lot of isset()s
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            05-10-03 - added a datetag field, which is set on INSERT
            26-01-04 - while deleting a website, keyphrases are deleted as well from now on
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/

if (isset($HTTP_GET_VARS["ws"]) && !is_numeric($HTTP_GET_VARS["ws"]))
  err("Received website ID is invalid!");
else {
  $ws = isset($HTTP_GET_VARS["ws"])?$HTTP_GET_VARS["ws"] :'';
  
  $i = 0;

  /*
    phpSERA mostly uses a 2-step method for delete actions:
    1) The first step is the immediate deletion of the object
    requested to be deleted.
    2) The second step is the optional deletion of all data
    solely related to the deleted object.
   */
  if (isset($HTTP_GET_VARS["action"]) && ($HTTP_GET_VARS["action"] == "delete") ) {
    $sql_delete_ws = "DELETE FROM $DB_Websites WHERE ws_id=$ws";
    $sql_delete_zt = "DELETE FROM $DB_Keyphrases WHERE ws_id=$ws";
    $sql_delete_rp = "DELETE FROM $DB_Reports WHERE ws_id=$ws";

    $sql_rp = "SELECT mt_id FROM $DB_Reports WHERE ws_id=$ws";
    $result_rp = $db->Execute($sql_rp);

    if (!$result_rp->EOF) {
      while (!$result_rp->EOF) {
        $i++; 
        if ($i==1) $collection = $result_rp->fields[mt_id];
        else $collection .= ",".$result_rp->fields[mt_id];
        $result->MoveNext();
      } 

      $sql_delete_rr = "DELETE FROM $DB_Reportrules WHERE mt_id IN ($collection)";
    }
 

    if ($HTTP_GET_VARS["step"] == 1) { 
      $db->Execute($sql_delete_ws);
      $db->Execute($sql_delete_zt);
      $msg = "Website with ID $ws and related keyphrases deleted.";
      if ($i > 0) {
        $msg .= " There are $i analysis reports which were related solely to the deleted website. Therefore, you may want to <a href=$PHP_SELF?action=delete&step=2&ws=$ws>execute the following delete queries</a>: <p style='font-weight:bold'>$sql_delete_rp<br>$sql_delete_rr</p>";
      } else {
        $msg .= " No related analysis data was found, so you're done.";
      }
    } elseif ($HTTP_GET_VARS["step"] == 2) {
      $db->Execute($sql_delete_rr);
      $db->Execute($sql_delete_rp);
      $msg = "All related analysis data deleted.";
    } else {
      $msg = "Uhm... you are trying to delete, but no valid step number was received.";
    }

  } elseif (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "add") {
    if (strlen($HTTP_GET_VARS["url"])==0)
      err("No website URL was given!");  
  
    $sql_add_ws = "INSERT INTO $DB_Websites (url, datetag) VALUES ('".addslashes($HTTP_GET_VARS["url"])."', now())";

    $db->Execute($sql_add_ws);
    echo "Website \"".$HTTP_GET_VARS["url"]."\" added. Go and add <a href=keyphrases.php>keyphrases</a>!";
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
<h3>Website Management </h3>
<?php if(isset($msg)) echo $msg; ?>
<table>
 <tr>
  <td>
    <fieldset>
      <legend><b><?php echo $lang["websites"]["add_a_website"]?></b></legend>
      <form action="<?php echo $PHP_SELF?>" method="get" name="wsform">
        <input type="text" name="url" value="www.">
        <input type="hidden" name="action" value="add">
        <input type="submit" value="<?php echo $lang["websites"]["add"]?>">
      </form>

      <table>
        <tr>
          <th><?php echo $lang["websites"]["website_url"]?>:</th>
          <th>Added:</th>
          <th>&nbsp;</th>
        </tr>
      <?php
        $sql_ws = "SELECT ws_id, url, datetag FROM $DB_Websites";
        $result_ws = $db->Execute($sql_ws);
        if (!$result_ws->EOF) {
          while ($o = $result_ws->FetchNextObject()) {
            $deletehtml = "<a href=$PHP_SELF?action=delete&step=1&ws=".$o->WS_ID." onclick=\"return confirm('Are you SURE you want to delete \'".$o->URL."\'? Previous analysis data related to this specific website will become useless.');\">".$lang["websites"]["delete"] ."</a>"; 

            echo "<tr>";
            echo "<td>$o->URL</td>";
            echo "<td>$o->DATETAG</td>";
            echo "<td>$deletehtml</td>";
            echo "</tr>";
          }
        }
      ?>
      </table>


    </td>
  </tr>
</table>
<h2><?php echo $lang["newser"]["explanation_header"]?></h2>
<p><?php echo $lang["websites"]["usage_info"]?></p>
</body>
</html>
