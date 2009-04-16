<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : leftmenu.php
  Desc.   : left frame (menu)

  Version : 0.3
  Author  : Matthijs Koot

  History : 28-04-03 - file created
            19-06-03 - added link to trends.php
            30-07-03 - added ADODB layer, in hope for dbms independence...
            21-08-03 - removed PHP short tags, added link to errorlog
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/
?>
<html>
<head>
  <title><?php echo $TITLE?></title>
  <link rel="stylesheet" href="css/leftmenu.css">
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<!-- phpSERA logo and information -->
<table style="padding-left:0px; padding-top:0px; width:145px; height:30px;" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/sera-logo.png" height="30">&nbsp;</td>
  </tr>
  <tr>
    <td class="release">&nbsp;Release v<?php echo $VERSION?>, <?php echo $RELEASE?></td>
  </tr>
  <tr>
    <td class="release">&nbsp;Updates: <a href="http://phpsera.sourceforge.net/" target="_top">website</a></td>
  </tr>
</table>
<br>
<!-- phpSERA menu structure -->
<table>
  <tr>
    <td width="150" valign="top">
      <!-- Home -->
      &nbsp;<span class="menukop"><a href="welcome.php" target='content'><?php echo $lang["leftmenu"]["home"]?></a></span><br><br>

      <!-- Rankings -->
      &nbsp;<span class="menukop"><?php echo $lang["leftmenu"]["rankings"]?></span><br>
      &nbsp;<a class="menu" href="newser.php" target='content'><?php echo $lang["leftmenu"]["rankings_new"]?></a><br>
      &nbsp;<a class="menu" href="quick.php" target='content'><?php echo $lang["leftmenu"]["rankings_quick"]?></a><br>
      &nbsp;<a class="menu" href="overview.php" target='content'><?php echo $lang["leftmenu"]["rankings_overview"]?></a><br>
      &nbsp;<a class="menu" href="trends.php" target='content'><?php echo $lang["leftmenu"]["rankings_trends"]?></a><br><br>

      <!-- Admin -->
      &nbsp;<span class="menukop"><?php echo $lang["leftmenu"]["admin"]?></span><br>
      &nbsp;<a class="menu" href="keyphrases.php" target='content'><?php echo $lang["leftmenu"]["admin_keyphrases"]?></a><br>
      &nbsp;<a class="menu" href="websites.php" target='content'><?php echo $lang["leftmenu"]["admin_websites"]?></a><br>
      &nbsp;<a class="menu" href="se_frameset.php" target='content'><?php echo $lang["leftmenu"]["admin_searchengines"]?></a><br>
      &nbsp;<a class="menu" href="errorlog.php" target='content'><?php echo $lang["leftmenu"]["admin_errorlog"]?></a><br>

    </td>
  </tr>
</table>
</body>
</html>
