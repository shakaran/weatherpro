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

// Here we create an aray for the y-values
$y=array($clientrawdaily['199'],$clientrawdaily['200'],$clientrawdaily['201'],$clientrawdaily['202'],
$clientrawdaily['203'],$clientrawdaily['204'],$clientrawdaily['205'],$clientrawdaily['206'],$clientrawdaily['207'],
$clientrawdaily['208'],$clientrawdaily['209'],$clientrawdaily['210'],$clientrawdaily['211'],$clientrawdaily['212'],
$clientrawdaily['213'],$clientrawdaily['214'],$clientrawdaily['215'],$clientrawdaily['216'],$clientrawdaily['217'],
$clientrawdaily['218'],$clientrawdaily['219'],$clientrawdaily['220'],$clientrawdaily['221'],$clientrawdaily['222'],
$clientrawdaily['223'],$clientrawdaily['224'],$clientrawdaily['225'],$clientrawdaily['226'],$clientrawdaily['227'],
$clientrawdaily['228'],$clientrawdaily['229']);

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
$graph->SetScale("textlin",0,100);
$graph->SetMarginColor("$margincolour");
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a line pot
$lplot = new LinePlot($y);
$lplot->SetWeight(2);
$lplot->SetColor("$hum_col");

//Add plot
$graph->Add($lplot);

// Setup the titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_hum $txt_31d (%)");
$graph->title->SetColor("$textcolour"); 

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->title->Set("$txt_date");
$graph->xaxis->title->SetColor("$xtextcolour");
$graph->xaxis->SetTickLabels($a); 
$graph->xaxis->SetTextLabelInterval(2);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xgrid->Show(true);
$graph->xaxis->SetColor("$xtextcolour");
$graph->xaxis->HideTicks(true,true); 

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour"); 
$graph->yaxis->HideTicks(true,true); 

// Display the graph
$graph->Stroke();
?>

