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

// Here we create an aray for the y-values
$y=array($clientrawdaily['63'],$clientrawdaily['64'],$clientrawdaily['65'],$clientrawdaily['66'],
$clientrawdaily['67'],$clientrawdaily['68'],$clientrawdaily['69'],$clientrawdaily['70'],$clientrawdaily['71'],
$clientrawdaily['72'],$clientrawdaily['73'],$clientrawdaily['74'],$clientrawdaily['75'],$clientrawdaily['76'],
$clientrawdaily['77'],$clientrawdaily['78'],$clientrawdaily['79'],$clientrawdaily['80'],$clientrawdaily['81'],
$clientrawdaily['82'],$clientrawdaily['83'],$clientrawdaily['84'],$clientrawdaily['85'],$clientrawdaily['86'],
$clientrawdaily['87'],$clientrawdaily['88'],$clientrawdaily['89'],$clientrawdaily['90'],$clientrawdaily['91'],
$clientrawdaily['92'],$clientrawdaily['93']);

if ($rain_conv != 1) {
	array_walk($y, "MtoI");
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
$graph->SetMarginColor("$margincolour");
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a bar pot
$bplot = new BarPlot($y);

//Setup width of bars
$bplot->SetWidth(0.7);

// Adjust fill color
$bplot->SetFillColor("$rain_col@0.5");
$graph->Add($bplot);

// Setup the titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_rain $txt_31d ($rain_unit)");
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
$graph->yaxis->scale->SetGrace(10);
//$graph->yaxis->SetLabelFormat("$rain_format");
$graph->yaxis->SetColor("$ytextcolour"); 
$graph->yaxis->HideTicks(true,true); 

// Display the graph
$graph->Stroke();
?>

