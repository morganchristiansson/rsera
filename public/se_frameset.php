<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : se_frameset.php
  Desc.   : Search Engine Management frameset

  Version : 0.3
  Author  : Matthijs Koot

  History : 01-05-03 - file created
            30-07-03 - changed constants to uppercase
	          05-08-03 - creuzerm - creuzerm@users.sourceforge.net
			                 added isset()s
            21-08-03 - removed PHP short tags
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
*/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title><?php echo $TITLE?></title>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<frameset rows="300,*" border="0" framespacing="0" frameborder="no">
	<frame src="se_top.php?se=<?php echo isset($HTTP_GET_VARS["se"])? $HTTP_GET_VARS["se"] : '' ?>&phrase=<?php echo isset($HTTP_GET_VARS["phrase"])? $HTTP_GET_VARS["phrase"] : '' ?>" name="regmenu" noresize scrolling="no">
	<frame src="blank.php" name="regresult" noresize>
  <noframes>
    <body>
    </body>
  </noframes>
</frameset>
</html>
