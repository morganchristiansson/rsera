<?php
include_once "../global.inc.php"; 

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : welcome.php 
  Desc.   : "welcome" homepage

  Version : 0.3
  Author  : Matthijs Koot

  History : 28-04-03 - file created
            30-07-03 - added ADODB layer, in hope for dbms independence...
            05-08-03 - creuzerm creuzerm@users.sourceforge.net
                       fixed incorrect index -missing quotes- for the $lang constant in several instances
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/

/* get the number of search engines in database */
  $sql_zm = "SELECT zm_id FROM $DB_Searchengines";
  $result_zm = $db->Execute($sql_zm);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $TITLE?></title>
<link rel="stylesheet" href="css/content.css" />
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width='300' cellspacing='5'>
  <tr>
    <td align="center">
      <h1>phpSERA</h1>
    </td>
  </tr>
  <tr>
    <td>
      <center><a href="http://www.eurocampings.net/" target="_top"><img src="images/acsi-logo.gif" alt="Originally built for Eurocampings.net" border="0" width="114" height="98"></a></center>
<?php
  
if (!function_exists('utf8_decode'))
  echo "<h4 style='color:red'>Warning: PHP function utf8_decode() is not available! phpSERA will not function correctly without it. 
Recompile PHP with XML support enabled.</h4>";                      
if (!function_exists('imagettfbbox'))           
  echo "<h4 style='color:red'>Warning: PHP function imagettfbbox() is not available! Trend graphs won't be displayed. Recompile PHP w
ith support for both the GD library and the FreeType library.</h4>";                                                                
  
?>
      <p>
        <fieldset>
          <table width='300' cellspacing='5'>
            <tr>
              <td align="center">
                <h3>Search Engine Ranking Analyzer</h3>
                <h4>Release v<?php echo $VERSION.", ".$RELEASE?></h4>
                <h4><?php echo $lang["welcome"]["nr_of_engines"].": ".$result_zm->RecordCount()?></h4> 
                <?php echo $lang["welcome"]["supported_languages"]?>
                <table>
                <?php
                  $languages = array("en","nl","de","fr","it","sv","es","no","fi","da","pl");
                  foreach ($languages as $flag) {
                    echo "<tr><td style='font-size:10px'><img src=images/flags/$flag.gif alt='".$lang["lc"][$flag]."' width=15 height=10>".$lang["lc"][$flag]."</td></tr>\n";
                  }
                ?>
                </table>
                </div>
              </td>
            </tr>
          </table>		
        </fieldset>
      </p>
    </td>
  </tr>
  <tr>
    <td nowrap style="font-size: 9px">
      phpSERA is licensed under <a href="http://www.gnu.org/licenses/gpl.html" target="_top">GNU GPL</a>
    </td>
  </tr>
</table>
</body>
</html>
