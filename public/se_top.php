<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : se_top.php
  Desc.   : try regular expressions on a search result page

  Version : 0.3
  Author  : Matthijs Koot

  History : 01-05-03 - file created
            07-05-03 - added DHTML mouseover help
            28-05-03 - delete action now involves a 2-step model 
            29-05-03 - DIVs w/help text not behind selectbox anymore
            29-07-03 - added ADODB layer, in hope for dbms independence...
            30-07-03 - translations completed
            01-08-03 - fixed broken replacement of "[__KEYPHRASE__]" by $phrase
	    05-08-03 - creuzerm - creuzerm@sourceforge.net
                       inserted a lot of isset() ? : ternary statements to remove the Notice messages from displaying on the page and break the javascript
            21-08-03 - fixed broken echo's; removed PHP short tags
            09-09-03 - fix: couldn't add new search engines d'oh!
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            11-12-03 - added separate field to enter a keyphrase
                       changed javascript seraTagIsPresent() to seraTagIsNotPresent() to support the new field
            19-01-04 - added utf8_before_urlencode fix for Excite.fr
            04-02-04 - added Polish language, changed if-then-else structure
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
                       renamed 'separator' to 'hit_separator' because of MySQL-4.1 reserved words
*/


  /*  
    phpSERA mostly uses a 2-step method for delete actions:
    1) The first step is the immediate deletion of the object
    requested to be deleted.
    2) The second step is the optional deletion of all data
    solely related to the deleted object.
  */
  if (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "delete") {
    if (!isset($HTTP_GET_VARS["se"]) || !is_numeric($HTTP_GET_VARS["se"]))
      err("Received search engine ID is invalid!");
    else 
      $se = $HTTP_GET_VARS["se"];

    $sql_delete_se = "DELETE FROM $DB_Searchengines WHERE zm_id=".$se;
    $sql_delete_rr = "DELETE FROM $DB_Reportrules WHERE zm_id=".$se;

    if ($HTTP_GET_VARS["step"] == 1) {
      $sql_zm = "SELECT * FROM $DB_Reportrules WHERE zm_id=$se";
      $result_tmp= $db->Execute($sql_zm);
      if (!$result_tmp->EOF) $i = $result_tmp->RecordCount();
      else $i = 0;

      $db->Execute($sql_delete_se);

      if ($i > 0) {
        $msg = "Search Engine with ID $se deleted. $i ranking records were found which were related solely the deleted search engine. Therefore, you may want to <a href=$PHP_SELF?action=delete&step=2&se=$se>execute the following delete query</a> to delete this data: <p style='font-weight:bold'>$sql_delete_rr</p>";
      } else {
        $msg = " No related analysis data found, so you're done.";
      }
    } elseif ($HTTP_GET_VARS["step"] == 2) {
      $db->Execute($sql_delete_rr);
      $msg = "All related records were deleted.";
    } else {
      $msg = "Uhm... you are trying to delete, but no valid step number was received.";
    }
  } elseif (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "add") {
    if (strlen($HTTP_GET_VARS["title"])==0)
      err("No search engine title (name) was given!", $HTTP_GET_VARS["title"]);  
    $sql_add_se = "INSERT INTO $DB_Searchengines (title, datetag_insert, datetag_lastupdate) VALUES ('".addslashes($HTTP_GET_VARS["title"])."', now(), now())";

    $db->Execute($sql_add_se);
    $msg = "Search Engine [".$HTTP_GET_VARS["title"]."] added. Please select it from the combobox and update it's settings!";
    $se = $db->getone("SELECT MAX(zm_id) FROM $DB_Searchengines");

  } else {
    if (isset($HTTP_GET_VARS["se"]) && strlen($HTTP_GET_VARS["se"]) > 0 && is_numeric($HTTP_GET_VARS["se"])) {
      /* visit from a "debug" link from an analysis result */
      $sql_zm = "SELECT startkey, endkey, hit_separator, host, script, data, langcode, noresult, utf8_support FROM $DB_Searchengines WHERE zm_id = ".$se;
      $result_zm = $db->Execute($sql_zm);
      if (!$result_zm->EOF) { 
        $o = $result_zm->FetchObject();
        $startkey    = $o->STARTKEY;
        $endkey      = $o->ENDKEY;
        $separator   = $o->HIT_SEPARATOR;
        $host        = $o->HOST;
        $script      = $o->SCRIPT;
        $data        = $o->DATA;
        $langcode    = $o->LANGCODE;
        $noresult    = $o->NORESULT;
        $utf8_support = $o->UTF8_SUPPORT;

      } else
        $msg = "No search engine with such ID ($se)!";
    }
  }

if (!isset($se)) $se = 0; // fix 'undefined' messages :)
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
<script language="javascript">
  function seraTagIsNotPresent(f) {
    sData = f.data.value;
    if (sData.indexOf('[__KEYPHRASE__]') < 0) {
      return true;
    } else return false;
  }
</script>
</head>
<body>
<form name="help"><input type="hidden" name="loc" value=""></form>
<div id="divHelp" class="help" style="width: 437px; height: 38px; z-Index: 1"></div>
<script language="javascript">
<!--
  var pos = "";
  var nav = (document.layers); 
  var iex = (document.all);
  var skn = (nav) ? document.divHelp : divHelp.style;
  if (nav) document.captureEvents(Event.MOUSEMOVE);
  document.onmousemove = get_mouse;
  
  function showHelp(content, pos) {
  	if (pos=="top") {
  		document.help.loc.value="top";
  	} else {
  		document.help.loc.value="";
  	}
  
  	var begin = "<table width='300' border='0' cellspacing='0' cellpadding='1' bgcolor='#000000'><tr><td><table width='300' border='0' cellspacing='0' cellpadding='4' bgcolor='#ffffff'><tr><td>";
  	var end = "</td></tr></table></td></tr></table>";
  	var content = begin + content + end;
  
  	if (nav) { 
      	skn.document.write(content); 
  	  	skn.document.close();
  	  	skn.visibility = "visible";
  	} else if (iex) {
  	  	document.all("divHelp").innerHTML = content;
  	  	skn.visibility = "visible";  
    	}
  }
  
  function get_mouse(e) {
  	var x = (nav) ? e.pageX : event.x+document.body.scrollLeft; 
  	var y = (nav) ? e.pageY : event.y+document.body.scrollTop;
  	
  	if (document.help.loc.value=="top") {
  		skn.left = x + 30;
  		skn.top  = y - 20;
  	} else {
  		skn.left = x - 60;
  		skn.top  = y + 20;
  	}
  	
  }
  
  function hideHelp() { 
  	document.help.loc.value="";
  	skn.visibility = "hidden"; 
  }
//-->
</SCRIPT>
<h3>Search Engine Management </h3>
<?php if(isset($msg)) echo $msg; ?>
<table>
 <tr>
  <td>
    <fieldset>
      <legend><b><?php echo $lang["se"]["select_a_se"]?></b> 
              (<a href="" 
                  onClick="return false;"
                  onMouseOver="showHelp('To add a search engine to phpSERA, all you have to do is figure out a correct set of <b>six variables</b>, and optionally set the NoResult-tag and language.<p>If all values are correct and you click \'Test these settings\', the lower frame will show a nice, unnumbered list with separated search results. Congratulations, you just started competing with the pro\'s!<p>If a regex or separator is wrong, the list won\'t make sense and you may receive an error message.', 'top');" 
                  onMouseOut="hideHelp();">help</a>)</legend>
      <table>
        <tr>
          <td>
            <form action="<?php echo $PHP_SELF?>" method="get" name="seform">
            <select name="se" style="width:250px" onchange="if (selectedIndex>0) submit();">
              <option value="">
            <?php
               /*******************************
                 LOAD ALL SEARCH ENGINES
               *******************************/
                 $sql_se     = "SELECT zm_id, title FROM $DB_Searchengines ORDER BY title";
                 $result_se  = $db->Execute($sql_se);
                
                 while ($o = $result_se->FetchNextObject()) {
                   echo "<option value=\"$o->ZM_ID\"";
                   if ($o->ZM_ID == $se) echo " selected";
                   echo ">$o->TITLE</option>\n";
                 }
              ?>
            </select>
            </form>
          </td>
          <td>
            <form action="<?php echo $PHP_SELF?>" method="GET" name="delform">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="step" value="1">
              <input type="hidden" name="se" value="<?php echo $se?>">
              <input type="submit" name="delete" value="<?php echo $lang["se"]["delete"]?>" onclick="if ((se.value>0) && confirm('Are you sure you want to delete this search engine? All rankings related to this SE will be deleted as well.')) { this.disabled=true; submit(); } else return false; ">
            </form>
          </td>
          <td>
            <form action="<?php echo $PHP_SELF?>" method="GET" name="addform" onsubmit="document.addform.title.value=prompt('What is the name of the Search Engine?','Google (worldwide)');">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="title" value="">
              <input type="submit" name="add" value="<?php echo $lang["se"]["new"]?>">
            </form>
          </td>

        </tr>
      </table>
      <table>
        <form action="se_result.php" method="post" name="testform" target="regresult">
          <tr> 
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('The language of the search engine. Only use \'international\' for search engines for which you defined settings to search a worldwide index. <p>Google default\'s to Search the Web, not to (e.g.) Search within Dutch pages. In such case, you could add 2 versions of a search engine, one set to international, the other set to Dutch.', '');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["language"]?></a>:
            </td>
            <td> 
              <select name="langcode">
                <option></option>
            <?php
               /*******************************
                 LOAD ALL LANGUAGES
               *******************************/
                 $languages = array("en","nl","de","fr","it","sv","es","no","fi","da","pl","@@");
                 foreach ($languages as $language) {
                   echo "<option value=\"".$language."\"";
                   if (isset($langcode) && $language == $langcode) echo " selected";
                   echo ">".$lang["lc"][$language]."</option>\n";
                 }
              ?>
              </select>
            </td>
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('A regular expression to mark the beginning of the actual result list, i.e. \'<\\/td>\\n\\n&amp;lt;ol>&amp;lt;li>\'<p><small>Note that this regex should be compatible with the PHP preg_match() function, see www.php.net!</small>', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["startkey"]?></a>:
            </td>
            <td> 
              <input type="text" name="regexp_begin" size="30">
              
            </td>
          </tr>
          <tr> 
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('Path to the script which handles the query, i.e. \'/cgi-bin/pursuit\'', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["script_path"]?></a>:
            </td>
            <td> 
              <input type="text" name="path" value="<?php echo (isset($script)) ? $script : '' ?>">
            </td>
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('A regular expression to mark the end of the actual result list, i.e. \'<\\/ol>\'<p><small>Note that this regex should be compatible with the PHP preg_match() function, see www.php.net!</small>', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["endkey"]?></a>:
            </td>
            <td> 
              <input type="text" name="regexp_end" size="30">
            </td>
          </tr>
          <tr> 
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('All data after the script part, without the \'?\', i.e. \'query=campings&pp=30\'. <small><b>Before you save the settings, make sure you replace the value of the keyword parameter (e.g., if you put \'q=mp3\' here, replace the \'mp3\' part) to the reserved term [__KEYPHRASE__]!</b></small>', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["data"]?></a>:
            </td>
            <td> 
              <input type="text" name="data" value="<?php (isset($data)) ? $data : '' ?>">
            </td>
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('A string to use as a result separator to extract the individual search results from the list, i.e. \'&amp;lt;li>\'<p><small>Note that this can NOT be a regular expression!</small>', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["separator"]?></a>:</td>
            <td> 
              <input type="text" name="separator">
            </td>
          </tr>
          <tr>
            <td nowrap>
              <a href="javascript:return false();"  
                 onClick="return false;"
                 onMouseOver="showHelp('DNS hostname or IP address of the host on which the query is executed by the SE, i.e. \'search.lycos.com\'', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["host"]?></a>:
            </td>
            <td>
              <input type="text" name="host" value="<?php echo (isset($host)) ? $host : '' ?>">
            </td>
            <td nowrap >
              <a href=""
                 onClick="return false;"
                 onMouseOver="showHelp('Enter a keyphrase/keyword you want to test with. This value is not stored in the database!', 'top');"
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["testphrase"]?></a>:
             </td> 
            <td>
              <input type="text" name="phrase" value="<?php echo (isset($phrase)) ? $phrase : '' ?>">
            </td> 
          </tr>
          <tr>
            <td nowrap>
              <a href=""  
                 onClick="return false;"
                 onMouseOver="showHelp('A piece of HTML or text which (only) appears when this Search Engine has an empty result list. Leave empty you\'re not sure about it!', 'top');" 
                 onMouseOut="hideHelp();"><?php echo $lang["se"]["noresults"]?></a>:
            </td>
            <td colspan="2">
              <input type="text" name="noresult" size="40">
            </td> 
            <td>
              <input type="checkbox" name="utf8_support"<?php if (isset($utf8_support) && $utf8_support == true) echo " checked"; ?>>UTF-8 support
            </td>
          </tr>     
          <tr> 
            <td colspan=4> 
            <input type="hidden" name="se" value="<?php echo $se?>">
            <input type="hidden" name="action" value="start">
              <table align="right">
                <tr> 
                  <td align="right"><?php echo $lang["se"]["test_settings"]?>!</td>
                  <td> 
                    <input type='image' name='submit' value='start' src='images/pijlredrights.gif' border='0' onclick='document.testform["action"].value="start"; if (seraTagIsNotPresent(document.testform)) { alert("The tag [__KEYPHRASE__] MUST be present in the Querydata field! This is a requirement for getting sane testresults."); return false; }'>
                  </td>
                  <td>&nbsp;&nbsp;&nbsp;</td>
                  <td align="right"><?php echo $lang["se"]["showrawhtml"]?>!</td>
                  <td> 
                    <input type='image' name='submit' value='showrawhtml' src='images/pijlredrights.gif' border='0' onclick='document.testform["action"].value="showrawhtml"; if (seraTagIsNotPresent(document.testform)) { alert("The tag [__KEYPHRASE__] MUST be present in the Querydata field! This is a requirement for getting sane testresults."); return false; }'>
                  <td>&nbsp;&nbsp;&nbsp;</td>
                <?php if (isset($se)) { ?>
                  <td align="right"><?php echo $lang["se"]["save_settings"]?></td>
                  <td> 
                    <input type='image' name='submit' value='start' src='images/pijlredrights.gif' border='0' onclick='document.testform["action"].value="save"; if (seraTagIsNotPresent(document.testform)) { alert("The tag [__KEYPHRASE__] MUST be present in the Querydata field!"); return false; }'>
                  </td>
                </tr>
                <?php } ?>
              </table>
            </td>
          </tr>
        </form>
      </table>
      <script type="text/javascript">
        /*
          This is the only easy way to deal with HTML input,
          as PHP's htmlentities() and addslashes() will NOT
          suffice -in all cases-. This will.
        */

        document.testform.regexp_begin.value='<?php echo addslashes($startkey)?>';
        document.testform.regexp_end.value='<?php echo addslashes($endkey)?>';
        document.testform.separator.value='<?php echo addslashes($separator)?>';
        document.testform.data.value='<?php echo addslashes($data)?>';
        document.testform.noresult.value='<?php echo addslashes($noresult)?>';
      </script>
    </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
