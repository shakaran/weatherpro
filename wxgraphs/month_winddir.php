<?php
// Graphs Package V2.1 16th March 2008
if (!file_exists("graphsconf.php")) include("error_msg.php");
include ("graphsconf.php");
if (!file_exists($jploc."jpgraph.php")) {
  $string = "Unable to find JPGraph files";
  create_image1($string,$jploc);
  exit;
}
include ($jploc."jpgraph.php");
include ($jploc."jpgraph_scatter.php");

$clientrawdaily = get_raw ("${hostloc}clientrawdaily.txt");

//create aray of winddir
$y=array($clientrawdaily['156'],$clientrawdaily['157'],$clientrawdaily['158'],$clientrawdaily['159'],
$clientrawdaily['160'],$clientrawdaily['161'],$clientrawdaily['162'],$clientrawdaily['163'],$clientrawdaily['164'],
$clientrawdaily['165'],$clientrawdaily['166'],$clientrawdaily['167'],$clientrawdaily['168'],$clientrawdaily['169'],
$clientrawdaily['170'],$clientrawdaily['171'],$clientrawdaily['172'],$clientrawdaily['173'],$clientrawdaily['174'],
$clientrawdaily['175'],$clientrawdaily['176'],$clientrawdaily['177'],$clientrawdaily['178'],$clientrawdaily['179'],
$clientrawdaily['180'],$clientrawdaily['181'],$clientrawdaily['182'],$clientrawdaily['183'],$clientrawdaily['184'],
$clientrawdaily['185'],$clientrawdaily['186']);
$datay = $y;

//=================================================================================================
//here we create the labels for the x-axis depending month and year
//so if we are in March we must show the last day of Feb normally 28 or 29 and the following
//label must be 01
//same for moths with 30 days (last day = 30 next label must be 01 and not 31
//we need 31 labels because we have 31 datapoints
//==================================================================================================
$month = (date("m"));
$year = date("y");
$today = date("j");
$a = array(date ("d", mktime (0,0,0,$month,$today-31,$year)),date ("d", mktime (0,0,0,$month,$today-30,$year)),
date ("d", mktime (0,0,0,$month,$today-29,$year)),date ("d", mktime (0,0,0,$month,$today-28,$year)),
date ("d", mktime (0,0,0,$month,$today-27,$year)),date ("d", mktime (0,0,0,$month,$today-26,$year)),
date ("d", mktime (0,0,0,$month,$today-25,$year)),date ("d", mktime (0,0,0,$month,$today-24,$year)),
date ("d", mktime (0,0,0,$month,$today-23,$year)),date ("d", mktime (0,0,0,$month,$today-22,$year)),
date ("d", mktime (0,0,0,$month,$today-21,$year)),date ("d", mktime (0,0,0,$month,$today-20,$year)),
date ("d", mktime (0,0,0,$month,$today-19,$year)),date ("d", mktime (0,0,0,$month,$today-18,$year)),
date ("d", mktime (0,0,0,$month,$today-17,$year)),date ("d", mktime (0,0,0,$month,$today-16,$year)),
date ("d", mktime (0,0,0,$month,$today-15,$year)),date ("d", mktime (0,0,0,$month,$today-14,$year)),
date ("d", mktime (0,0,0,$month,$today-13,$year)),date ("d", mktime (0,0,0,$month,$today-12,$year)),
date ("d", mktime (0,0,0,$month,$today-11,$year)),date ("d", mktime (0,0,0,$month,$today-10,$year)),
date ("d", mktime (0,0,0,$month,$today-9,$year)),date ("d", mktime (0,0,0,$month,$today-8,$year)),
date ("d", mktime (0,0,0,$month,$today-7,$year)),date ("d", mktime (0,0,0,$month,$today-6,$year)),
date ("d", mktime (0,0,0,$month,$today-5,$year)),date ("d", mktime (0,0,0,$month,$today-4,$year)),
date ("d", mktime (0,0,0,$month,$today-3,$year)),date ("d", mktime (0,0,0,$month,$today-2,$year)),
date ("d", mktime (0,0,0,$month,$today-1,$year)));

$datat = $a;

$NESWN = array(0 => "$txt_N", 45 => 'NE', 90 =>"$txt_E", 135 => 'SE', 180 => "$txt_S", 225 => 'SW', 270 => "$txt_W", 315 => 'NW', 360 => "$txt_N");

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin",0,360);
$graph->yaxis->scale->ticks->Set(90,45);
$graph->SetY2Scale("lin",0,360);
$graph->y2axis->scale->ticks->Set(90);
$graph->SetMarginColor("$margincolour");
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_wind_dr_av $txt_31d");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->title->Set("$txt_date");
$graph->xaxis->title->SetColor("$xtextcolour");
$graph->xaxis->SetTickLabels($datat); 
$graph->xaxis->SetTextLabelInterval(2);
$graph->xaxis->SetPos("min");
$graph->xaxis->HideTicks(true,true); 
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xgrid->Show(true);

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour"); 
$graph->yaxis->HideTicks(true,true);

$graph->y2axis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->y2axis->SetColor("$y2textcolour");
$graph->y2axis->HideTicks(true,true);
$graph->y2axis->SetTickLabels($NESWN);

$sp1 = new ScatterPlot($datay);
//$sp1->SetLinkPoints(true,"red",2);
$sp1->mark->SetType(MARK_SQUARE);
$sp1->mark->SetFillColor("$speed_col");
$sp1->mark->SetWidth(3);

//Add plot
$graph->Add($sp1);

// Display the graph
$graph->Stroke();
?>

