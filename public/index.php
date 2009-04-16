<?php
include_once "../global.inc.php";

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : index.php
  Desc.   : main frameset

  Version : 0.3
  Author  : Matthijs Koot

  History : 28-04-03 - file created
            30-07-03 - changed $TITLE to $TITLE
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
<frameset cols="150,*" border="0" framespacing="0" frameborder="no">
	<frame src="leftmenu.php" name="leftmenu" noresize>
	<frame src="welcome.php" name="content" noresize>
  <noframes>
    <body>
    </body>
  </noframes>
</frameset>
</html>
