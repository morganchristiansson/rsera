<?php
include_once "../global.inc.php";

error_reporting('E_NONE');

/*

  Search Engine Ranking Analysis
  ------------------------------
  File    : send_30day_graph.php
  Desc.   : return PNG width plotted graph of rankings of last 30 days

  Version : 0.3
  Author  : Matthijs Koot

  History : 25-07-03 - file created
            30-07-03 - added ADODB layer, in hope for dbms independence...
            21-08-03 - removed PHP short tags
            22-09-03 - replace sql tablenames with vars from global.inc.php nikl@spielepsychatrie.de
            01-10-03 - Stephan Häuslschmid improved colors and legend position
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with 
            04-05-04 - added utf8_decode() before displaying keyphrases on the graph
                       implemented keyphrase priority filter
                       implemented keyphrase language filter

  Todo    : improve algorithm (ugly loop structure)

*/


##-- This function may not be needed for your application --#
##-- Its purpose is to figure out how big to create an image so that we can fit a text block inside --#
##-- Also we want to know the X & Y coordinates of where to start writing the text --#
##-- It returns a hash with 4 keys for ..... WIDTH, HEIGHT, X_POS, Y_POS
function GetImageSizeForTextBlock($rotateDegrees, $TextString, $fontLocation, $fontSize){

	##-- This will add a small margin area around the text block.  	--##
	##-- The margin grows with the font size.			--##
	$margin = intval(0.25 * $fontSize);

	##-- Convert the angle so it is always a number between 0 and 359 --##
	if($rotateDegrees > 359 || $rotateDegrees < -359){
		$rotateDegrees = intval($rotateDegrees % 360);
	}
	if($rotateDegrees < 0){
		$rotateDegrees = 360 + $rotateDegrees;
	}

	#-- Clean out all of the "new line" characters.  --#
	#-- The following function expects the special code of "-br-" for line breaks.  --#
	$TextString = preg_replace("/(\n|\r)/", "", $TextString);


	##-- Get the total size of the text block so that we know how big to create the Image --##
	$TextBoxSize = ImageTTFBBox ($fontSize, $rotateDegrees, $fontLocation, preg_replace("/-br-/", "\n\r", $TextString));


	##-- Put the variables into reader-friendly variables --##
	$TxtBx_Lwr_L_x = $TextBoxSize[0];
	$TxtBx_Lwr_L_y = $TextBoxSize[1];
	$TxtBx_Lwr_R_x = $TextBoxSize[2];
	$TxtBx_Lwr_R_y = $TextBoxSize[3];
	$TxtBx_Upr_R_x = $TextBoxSize[4];
	$TxtBx_Upr_R_y = $TextBoxSize[5];
	$TxtBx_Upr_L_x = $TextBoxSize[6];
	$TxtBx_Upr_L_y = $TextBoxSize[7];


	##-- The Text Box coordinates are relative to the font, regardless of the angle 		--##
	##-- We need to figure out the height and width of the text box accounting for the rotation 	--##
	if($rotateDegrees <= 90 || $rotateDegrees >= 270 ){
		$TextBox_width = max($TxtBx_Lwr_R_x, $TxtBx_Upr_R_x) - min($TxtBx_Lwr_L_x, $TxtBx_Upr_L_x);
		$TextBox_height = max($TxtBx_Lwr_L_y, $TxtBx_Lwr_R_y) - min($TxtBx_Upr_R_y, $TxtBx_Upr_L_y);

		##-- This figures out where the coordinates of the first letter in the text block starts	--##
		##-- It is roughly the lower left-hand corner letter 					--##
		$Start_Text_Coord_x = -(min($TxtBx_Upr_L_x, $TxtBx_Lwr_L_x));
		$Start_Text_Coord_y = -(min($TxtBx_Upr_R_y, $TxtBx_Upr_L_y));
	}
	else{
		$TextBox_width = max($TxtBx_Lwr_L_x, $TxtBx_Upr_L_x) - min($TxtBx_Lwr_R_x, $TxtBx_Upr_R_x);
		$TextBox_height = max($TxtBx_Upr_R_y, $TxtBx_Upr_L_y) - min($TxtBx_Lwr_L_y, $TxtBx_Lwr_R_y);

		$Start_Text_Coord_x = -(min($TxtBx_Lwr_R_x,$TxtBx_Upr_R_x));
		$Start_Text_Coord_y = -(min($TxtBx_Lwr_L_y, $TxtBx_Lwr_R_y));
	}


	##-- We need to add our margin to the coordinates of the first letter --##
	$Start_Text_Coord_x += $margin;
	$Start_Text_Coord_y += $margin - 2;  //Don't forget to account for the '0th' pixel at Y-coord 0


	##-- We are going to make the image just big enough to hold our text block... accounting for the rotation and font size. --##
	##-- We times the Margin by 2 so that there is a margin on all 4 sides   	--##
	$TotalImageWidth = $TextBox_width + $margin *2;
	$TotalImageHeight = $TextBox_height + $margin *2;

	##-- Send back a hash with our calculations --#
	return array("WIDTH"=>$TotalImageWidth, "HEIGHT"=>$TotalImageHeight, "X_POS"=>$Start_Text_Coord_x, "Y_POS"=>$Start_Text_Coord_y);
}


function perror($errmsg) {
  global $FONTFILE;

  $textsize=12;
  $rotateDegrees=0;

  Header("Content-type: image/png");


  $ImageSizeInfoArr = GetImageSizeForTextBlock($rotateDegrees, $errmsg, $FONTFILE, $textsize);


  $im = imagecreate ($ImageSizeInfoArr["WIDTH"], $ImageSizeInfoArr["HEIGHT"]);

  $blue = ImageColorAllocate($im, 0x2c,0x6D,0xAF);
  $black = ImageColorAllocate($im, 0,0,0);
  $white = ImageColorAllocate($im, 255,255,255);

  ImageTTFText($im, $textsize, 0, 2, 15, $white, $FONTFILE, $errmsg);

  ImagePNG($im);
  ImageDestroy($im);
  exit();
}

$ws_id=$HTTP_GET_VARS["ws_id"]; /* website ID */
$zm_id=$HTTP_GET_VARS["zm_id"]; /* search engine ID */
$rankyear=$HTTP_GET_VARS["rankyear"];

/* get SE data */
  $sql_se = "SELECT title FROM $DB_Searchengines WHERE zm_id=$zm_id";

  $result_se = $db->Execute($sql_se);
  if ($result_se->EOF)
    perror ("No search engine with such ID [".$se_id."]!");


  /* get report ID to look for */
  $o = $result_se->FetchNextObject();
  $se_title = $o->TITLE;

/* get website data */
  $sql_ws = "SELECT url FROM $DB_Websites WHERE ws_id=$ws_id";

  $result_ws = $db->Execute($sql_ws);
  if ($result_ws->EOF)
    perror ("No website with such ID [".$ws_id."]!");

  /* get report ID to look for */
  $o = $result_ws->FetchNextObject();
  $ws_url = $o->URL;


/* get report data */
  $sql_rp = "SELECT mt_id FROM $DB_Reports WHERE ws_id =$ws_id";

  $result_rp = $db->Execute($sql_rp);
  if ($result_rp->EOF)
    perror ("No history yet for website ID ".$ws_id."!");

    /* get graph data for all keywords (this website, SE and last 30 days) */
    $sql_rr="SELECT
               $DB_Reportrules.zt_id,
               $DB_Reportrules.ranking,
               DAYOFMONTH($DB_Reports.rankdate) AS rankday,
               $DB_Keyphrases.keyphrase
             FROM
               $DB_Reports,
               $DB_Keyphrases,
               $DB_Reportrules
             WHERE
               $DB_Reports.ws_id = $ws_id
               AND TO_DAYS(NOW()) - TO_DAYS(rankdate) <= 30
               AND $DB_Reportrules.zm_id = $zm_id
               AND $DB_Reportrules.ranking > 0
               AND $DB_Reportrules.mt_id = $DB_Reports.mt_id
               AND $DB_Keyphrases.zt_id = $DB_Reportrules.zt_id";


      $priority = (isset($HTTP_GET_VARS["priority"]) && is_numeric($HTTP_GET_VARS["priority"]))?$HTTP_GET_VARS["priority"]:"";
      if (is_numeric($priority) && $priority > 0)
        $sql_rr.=" AND $DB_Keyphrases.priority='$priority'";

      $langcode = (isset($HTTP_GET_VARS["langcode"]) && $HTTP_GET_VARS["langcode"]!="")?$HTTP_GET_VARS["langcode"]:"";
      if ($langcode!="")
        $sql_rr.=" AND $DB_Keyphrases.langcode='$langcode'";

      $result_rr = $db->Execute($sql_rr);
      if ($result_rr->EOF)
        perror ("No useful data for [SE=$zm_id, YEAR=$rankyear, PRIORITY=$priority, LANG=$langcode]!");


      /* set arrays in order for the plot to succeed */
      while ($o = $result_rr->FetchNextObject()) {
        $graphdata[$o->ZT_ID][0]=utf8_decode($o->KEYPHRASE);
        for ($i=1;$i<=30;$i++) { 
          $graphdata[$o->ZT_ID][$i]="-"; // default to NULL plot value
        }
      }

      /* reset the loop, since we re-run it */
      $result_rr->MoveFirst();

      /* loop through all rankings */
      while ($o = $result_rr->FetchNextObject()) {
        if ($o->RANKING < 1) $ranking=0; else $ranking = $o->RANKING;
        $graphdata[$o->ZT_ID][$o->RANKDAY] = $ranking;
      }  


/* <debug> contains all data to be plotted in the graph */
#print_r($graphdata);



include "jpgraph.php";
include "jpgraph_line.php";

// Callback to negate the argument
function _cb_negate($aVal) {
    return round(-$aVal);
}

// Setup the graph
$graph = new Graph(800,700, "auto");
//$graph = new Graph(500,350);
$graph->SetMarginColor('white');
$graph->SetScale("textlin");
$graph->SetAxisStyle(AXSTYLE_BOXOUT);
$graph->img->SetMargin(260,50,60,40);    


$graph->title->Set($se_title.", last 30 days");

$graph->yaxis->HideZeroLabel();
$graph->yaxis->SetLabelFormatCallback("_cb_negate");

$graph->xgrid->Show();
$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#BBCCFF@0.5');

$graph->xaxis->SetTickSide('SIDE_TOP');
$graph->xaxis->SetPos("max"); 

$colors = array("navy", 
                "aquamarine3", 
                "brown1", 
                "chartreuse2", 
                "darkorchid2",
                "eggplant",
                "tan4",
		"AntiqueWhite1",
		"azure3",
		"bisque3",
		"blueviolet",
		"burlywood3",
		"cadetblue1",
		"cadetblue4",
		"chartreuse",
		"chartreuse4",
		"chocolate",
		"chocolate4",
		"cornflowerblue",
		"coral4",
		"cyan",
		"cyan4",
		"darkblue",
		"darkgoldenrod",
		"darkgray",
		"darkkhaki",
		"darkolivegreen",
		"gold",
		"gold4",
		"greenyellow",
		"hotpink2",
		"indianred4",
		"lemonchiffon3",
		"lightblue3",
		"lightgreen",
		"lightpink2",
		"lightsalmon",
		"olivedrab",
		"palegreen4",
		"peachpuff2",
		"sienna1",
		"tan2",
		"tomato",
		"teal",
		"violetred2",
		"white",
		"yellow3",
		"thistle4",
		"thistle",
		"tan4",
		"tomato4"
               );

$p=0;
foreach($graphdata as $phrase1) { 
  $p++;

  // Negate all dat
  $n = count($phrase1);

  $legend = $phrase1[0];

  for($i=0; $i<$n; ++$i) {
    if (is_numeric($phrase1[$i]))
      $phrase1[$i] = -$phrase1[$i];
  }

  unset($phrase1[0]);  // the keyphrase is NOT a value to plot :)

  // Create all lines
  $$p = new LinePlot($phrase1);
  $$p->SetColor($colors[$p % count($colors)]);
  $$p->SetWeight(2);
  $$p->SetLegend($legend);
  $graph->Add($$p);
  $graph->legend->Pos(0,0,"left","top");
}



// Display the graph
$graph->Stroke();




?>

