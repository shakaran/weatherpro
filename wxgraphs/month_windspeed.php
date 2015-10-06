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
include ($jploc."jpgraph_line.php");

$clientrawdaily = get_raw ("${hostloc}clientrawdaily.txt");

//create aray of windspeed
$y=array($clientrawdaily['125'],$clientrawdaily['126'],$clientrawdaily['127'],$clientrawdaily['128'],
$clientrawdaily['129'],$clientrawdaily['130'],$clientrawdaily['131'],$clientrawdaily['132'],$clientrawdaily['133'],
$clientrawdaily['134'],$clientrawdaily['135'],$clientrawdaily['136'],$clientrawdaily['137'],$clientrawdaily['138'],
$clientrawdaily['139'],$clientrawdaily['140'],$clientrawdaily['141'],$clientrawdaily['142'],$clientrawdaily['143'],
$clientrawdaily['144'],$clientrawdaily['145'],$clientrawdaily['146'],$clientrawdaily['147'],$clientrawdaily['148'],
$clientrawdaily['149'],$clientrawdaily['150'],$clientrawdaily['151'],$clientrawdaily['152'],$clientrawdaily['153'],
$clientrawdaily['154'],$clientrawdaily['155']);
$datay = $y;

array_walk($datay, "KtoV");

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

$datax = $a;

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin");
$graph->SetMarginColor("$margincolour");
$graph->yaxis->scale->SetGrace(20);
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a line pot
$lplot = new LinePlot($datay);
$lplot->SetColor("$speed_col");
$lplot->SetWeight(2);

//Add plot
$graph->Add($lplot);

// Setup the titles
$graph->title->Set("$txt_wind_sp_av $txt_31d ($speed_unit)");
$graph->title->SetColor("$textcolour"); 
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->title->Set("$txt_date");
$graph->xaxis->title->SetColor("$xtextcolour");
$graph->xaxis->SetTickLabels($datax); 
$graph->xaxis->SetTextLabelInterval(2);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xgrid->Show(true);
$graph->xaxis->HideTicks(true,true); 

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour"); 
$graph->yaxis->HideTicks(true,true); 

// Display the graph
$graph->Stroke();
?>

