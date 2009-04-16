<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : newser.php
  Desc.   : starting a new analysis

  Version : 0.3
  Author  : Matthijs Koot

  History : 29-04-03 - file created
            05-05-03 - added DHTML tree menu from DynamicDuo for the country stuff
                       list w/SEs is now automatically generated by country
            11-05-03 - code layout
            17-05-03 - divided keyphrases in seperate languages
            28-07-03 - switch invert: changed "Be gentle", default CHECKED, to "Rampage mode", 
                       default UNCHECKED; changed all POST to GET
            29-07-03 - added support for international keyphrases 
                       added support for worldwide search engines
                       fixed Javascript error for new installations
                       changed "GET" back to "POST" (HTTP GET has some awkward limitations which
                       make it unusable here)
                       fixed nested UL and LI elements
            30-07-03 - added ADODB layer, in hope for dbms independence...
            05-08-03 - creuzerm - creuzerm@users.sourceforge.net
                       put a conditional around the debug output
                       insererted missing quotes the variables in $lang 
                       declared $i = 0; before it was being used so it 
                       didn't raise a Notice within generating the javasript
            21-08-03 - removed PHP short tags
            09-09-03 - fixed bug in code for displaying keywords
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            05-10-03 - added "WHERE is_active = 1" condition so "inactive" keyphrases 
                       won't be displayed, as they won't be analyzed anyway by analyze.php
            21-01-04 - fixed 'smart folding menu' for Mozilla
            26-01-04 - replaced javascript for Search Engine selection (again)
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
            04-05-04 - fixed annoying Javascript bug for filtering keyphrases 
*/


// check for availability of at least one website,
// to avoid Javascript errors

  $sql_ws = "SELECT ws_id FROM $DB_Websites";
  $result_ws = $db->Execute($sql_ws);

  $ws_array = array();

  if ($result_ws->EOF)
    err("Please add a <a href=websites.php>website</a> first!");
  else
    while ($o = $result_ws->FetchNextObject())
      array_push($ws_array, $o->WS_ID);
  

  $sql_zt = "SELECT
               id,
               ws_id,
               keyword,
               langcode
             FROM
               $DB_Keyphrases
             WHERE 
               is_active = 1
             ORDER BY
               ws_id,
               langcode,
               keyword";
  $result_zt = $db->Execute($sql_zt);

  if ($result_zt->EOF) 
    err("Please add a <a href=keyphrases.php>keyphrase</a> first!");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css">
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript1.2">
<!--  
  var keyw = null;
  <?php
  /* initialize javascript arrays for each website 
     (without this, javascript errors may occur when
     dealing with websites that have no keyphrases) */
  foreach ($ws_array as $id) 
      echo "var site".$id." = new Array();\n";
  
    
  /* fill the arrays with keywords */
    $ws_id=0;      //dummy
    $ws_id_last=0; //dummy
    $i = 0;
    while ($o = $result_zt->FetchNextObject()) {
      if ($o->WS_ID != $ws_id_last) {
        $i = 0;
        $ws_id_last = $o->WS_ID;
      }

      // handle reserved language code for intl. keyphrases
      if ($o->LANGCODE == "@@") $langcode = "all";
      else $langcode = $o->LANGCODE;

      echo "site".($o->WS_ID) . "[".$i."] = new phrase(\"$o->ID\",\"$o->KEYWORD\", \"$langcode\");\n";
      $i++;
    } 
  ?> 
  
  function loadPhrases() {
    selectedsite=document.form1.website.value;
    keyw=eval("site"+selectedsite);
    keywcount = keyw.length;
  
    document.form1["phrases[]"].options.length = 0;
 
    /* loop all keywords for this website*/ 
    j=0; // index for listbox w/keyphrases
    for(i=0; i < keywcount; i++){
      /* check if this keyword should be displayed */
      langbox = eval(document.form1["lang_"+keyw[i].langcode]);
      if (langbox.checked) {
        //alert ("lang_"+keyw[i].langcode);
        document.form1["phrases[]"].options[j] = new Option(keyw[i].phrase, keyw[i].ztid);
        document.form1["phrases[]"].options[j].selected=true;
        j=j+1;
      }
    }
  }
  
  function phrase(ztid,phrase,langcode) {
    this.ztid=ztid; 
    this.phrase=phrase;
    this.langcode=langcode;
  }
  
  function checkAll(containerID, doit) {
    var container = document.getElementById(containerID);
    var curobj = container.getElementsByTagName("input");
    for (var i = 0; i < curobj.length; i++)
      if (curobj[i].type == "checkbox")
        curobj[i].checked = doit;
  }

function flip(e) {
    if (document.getElementById(e).style.display == 'none')
      show(e);
    else
      hide(e);
}

function hide(e) {
  document.getElementById(e).style.display = 'none';
}

function show(e) {
  document.getElementById(e).style.display = 'block';
}

function hideall() {
	var Nodes = document.getElementsByTagName('div')
	var max = Nodes.length
	for(var i = 0;i < max;i++) {
	  var nodeObj = Nodes.item(i)
          if (nodeObj.getAttribute('tag') == 'selist')
	    nodeObj.style.display = 'none';
	}
}

function showall() {
        var Nodes = document.getElementsByTagName('div')
        var max = Nodes.length
        for(var i = 0;i < max;i++) {
          var nodeObj = Nodes.item(i)
          if (nodeObj.getAttribute('tag') == 'selist')
            nodeObj.style.display = 'block';
        }
}
  
//-->	
</script>
<style type="text/css">
langcheck {
   display:inline;
   border: solid #333 1px;
}
</style>
</head>
<body onload="loadPhrases(); hideall();">
<h3><?php echo $lang["newser"]["new_analysis"]?></h3>
<?php if (isset($debugmsg))  echo $debugmsg;    ?>
<table>
 <tr>
  <td>
    <fieldset>
      <legend><b><?php echo $lang["newser"]["analysis"]?></b></legend>
        <form action="analyze.php" method="POST" name="form1">
      <table>
        <tr>
          <td><?php echo $lang["newser"]["type"]?>:</td>
          <td>
            <input type="checkbox" name="type" value="real"><?php echo $lang["newser"]["saveindb"]?>
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo $lang["newser"]["date"]?>:</td>
          <td><?php echo date("d-m-Y");?></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo $lang["newser"]["name"]?>:</td>
          <td><input type="text" name="name" value="tester"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>URL:</td>
          <td>
            <select name="website" style="width:250px" onchange="loadPhrases();">
            <?php
               /*******************************
                 1) LOAD ALL WEBSITES
               *******************************/
                 $sql_ws     = "SELECT ws_id, url FROM $DB_Websites";
                 $result_ws  = $db->Execute($sql_ws);

                 while ($o_ws = $result_ws->FetchNextObject()) 
                     echo "<option value=\"$o_ws->WS_ID\">$o_ws->URL</option>\n";
                 
              ?>
            </select>
          </td>
          <td><a href="websites.php"><?php echo $lang["newser"]["adminlink"]?></a></td>
        </tr>
        <tr><td colspan="3"><hr></td></tr>
        <tr>
          <td valign="top">
            <?php echo $lang["newser"]["keyword"]?>: <br>
            <a href="#" onclick="checkAll('language',true); loadPhrases(); return false;"><?php echo $lang["newser"]["select_all"]?></a> |
            <a href="#" onclick="checkAll('language',false); loadPhrases(); return false;"><?php echo $lang["newser"]["select_none"]?></a>
          </td>
          <td valign="top">
            <table>
              <tr>
                <td>
                  <div id="language">
                  <?php
                   /*******************************
                     2) LOAD LANGUAGE CHECKBOXES
                   *******************************/
                  $languages = array("en","nl","de","fr","it","sv","es","no","fi","da","pl","@@");
                  foreach ($languages as $langcode) {
                    if ($langcode == "@@") {
                      $flag = "all";
                      $size = ""; // globe icon may change
                    } else { 
                      $flag = $langcode;
                      $size = "width=15 height=9";
                    } 

                    echo "<input type=checkbox name=lang_$flag class=langcheck onclick=\"loadPhrases();\" value=$flag checked>\n"; 
                    echo "<img src=images/flags/$flag.gif $size alt='".$lang["lc"][$langcode]."'><br>\n";

                  }
                  ?>
                  </div>
                </td>
                <td><select name="phrases[]" size="12" style="width:250px" multiple><option></option></select></td>
              </tr>
            </table>
          </td>
          <td><a href="keyphrases.php"><?php echo $lang["newser"]["adminlink"]?></a></td>
        </tr>              
        <tr>
          <td style="font-size: 10px;"><?php echo $lang["newser"]["rampage_mode"]?></td>
          <td style="font-size: 10px;">
            <input type="checkbox" name="rampage" value="true"><?php echo $lang["newser"]["rampage_info"]?>
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr><td colspan="3"><hr></td></tr>
        <tr>
          <td valign="top">
            <?php echo $lang["newser"]["searchengines"]?>:<br>
            <a href="#" onclick="checkAll('secheckboxes',true); showall(); return false;"><?php echo $lang["newser"]["select_all"]?></a> |
            <a href="#" onclick="checkAll('secheckboxes',false); return false;"><?php echo $lang["newser"]["select_none"]?></a>
          </td>
          <td>
          <div id="secheckboxes"><!-- required by checkAll() -->
<?php

 /*******************************************************
   2) LOAD ALL SEARCH ENGINES
 ********************************************************/

      /* 
        Generating a DHTML menu from the known search engines 
         - since [searchengines].[langcode] is "@@" for international SEs,
           we first gather all available values for "langcode" (ISO-639 language code)
           and base the menu on THAT, rather than hardcoding each 
           country in this PHP script
         - note that support for Australia/NZ will require some
           change in this method! (just reserve "$$" for it, e.g.)
      */

     echo $lang["quick"]["selectse"]."<br>\n";

     $languages = array("en","nl","de","fr","it","sv","es","no","fi","da","pl","@@");

     foreach ($languages as $langcode) {
        $flag = ($langcode == "@@")?"all":$langcode;
        /************************************
          2-a) LOOP THROUGH LANGUAGES
        ************************************/
          if ($lang["lc"][$langcode]=="")
            err("</ul><h2>Error: translation for [".$langcode."] not set in data.inc!</h2>");

          ?>
              <a href="#" onclick="checkAll('<?php echo $langcode ?>',true); show('<?php echo $langcode?>'); return false;"><?php echo $lang["newser"]["select_all"]?></a> | 
              <a href="#" onclick="checkAll('<?php echo $langcode ?>',false); return false;"><?php echo $lang["newser"]["select_none"]?></a>
          <?php
          echo "&nbsp;<a href=\"javascript:flip('$langcode')\">";
          echo "<img src=\"images/flags/$flag.gif\" alt=\"".$lang["lc"][$langcode]."\">\n";
          echo $lang["lc"][$langcode];
          echo "</a><br>\n";
          echo "<div tag=\"selist\" id=\"$langcode\" style=\"display:none;\">\n";

          /* show all search engines for the current language */
            $sql_zm = "SELECT zm_id, title, url FROM $DB_Searchengines WHERE langcode='$langcode' ORDER BY title";

          $result_zm = $db->Execute($sql_zm);
   
          while ($o = $result_zm->FetchNextObject()) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"images/flags/$flag.gif\" alt=\"".$lang["lc"][$langcode]."\">\n";
            echo "<input type=\"checkbox\" name=\"zm[".$o->ZM_ID."]\" value=\"1\">".$o->TITLE."<br>\n";
          }

          echo "</div>\n";
        } // end loop through all languages
?>
              </div>
         </td>
          <td valign="top"><a href="searchengines.php"><?php echo $lang["newser"]["adminlink"]?></a></td>
        </tr>              
        <tr>
          <td colspan=3 align="right">
              <table>
                <tr>
                  <td align="right"><?php echo $lang["newser"]["start"]?></td>
                  <td>
                    <input type='image' name='arrow' src='images/pijlredrights.gif' border='0'>
                  </td>
                </tr>
              </table>
          </td>
        </tr>        
      </table>
      </form>
    </fieldset>
  </td>
 </tr>
</table>
<h2><?php echo $lang["newser"]["explanation_header"]?></h2>
<p><?php echo $lang["newser"]["explanation"]?></p>

</body>
</html>
