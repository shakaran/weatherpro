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
include ($jploc."jpgraph_bar.php");

$clientrawdaily = get_raw ("${hostloc}clientrawdaily.txt");

//Setup Y data
$y=array();$y=array($clientrawdaily['94'],$clientrawdaily['95'],$clientrawdaily['96'],$clientrawdaily['97'],
$clientrawdaily['98'],$clientrawdaily['99'],$clientrawdaily['100'],$clientrawdaily['101'],$clientrawdaily['102'],
$clientrawdaily['103'],$clientrawdaily['104'],$clientrawdaily['105'],$clientrawdaily['106'],$clientrawdaily['107'],
$clientrawdaily['108'],$clientrawdaily['109'],$clientrawdaily['110'],$clientrawdaily['111'],$clientrawdaily['112'],
$clientrawdaily['113'],$clientrawdaily['114'],$clientrawdaily['115'],$clientrawdaily['116'],$clientrawdaily['117'],
$clientrawdaily['118'],$clientrawdaily['119'],$clientrawdaily['120'],$clientrawdaily['121'],$clientrawdaily['122'],
$clientrawdaily['123'],$clientrawdaily['124']);
$datay = $y;

if ($pres_conv != 1) {
	array_walk($datay, "HtoI");
	}

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

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin");
$graph->yscale->SetAutoMin($pres_automin);
$graph->yscale->SetAutoMax($pres_automax);
$graph->SetMarginColor("$margincolour");
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a bar pot
$bplot = new BarPlot($datay);

//Setup width of bars
$bplot->SetWidth(0.7);

// Adjust fill color
$bplot->SetFillColor("$pres_col@0.5");
$graph->Add($bplot);

// Setup the titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_baro $txt_31d ($pres_unit)");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->title->Set("$txt_date");
$graph->xaxis->title->SetColor("$xtextcolour"); 
$graph->xaxis->SetTickLabels($a); 
$graph->xaxis->SetTextLabelInterval(2);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xgrid->Show(true);
$graph->xaxis->HideTicks(true,true); 

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->SetLabelFormat("$pres_format");
$graph->yaxis->HideTicks(true,true); 

// Display the graph
$graph->Stroke();
?>

