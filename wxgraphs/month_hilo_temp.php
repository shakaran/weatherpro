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

//create aray of max-temps
$y=array($clientrawdaily['1'],$clientrawdaily['2'],$clientrawdaily['3'],$clientrawdaily['4'],
$clientrawdaily['5'],$clientrawdaily['6'],$clientrawdaily['7'],$clientrawdaily['8'],$clientrawdaily['9'],
$clientrawdaily['10'],$clientrawdaily['11'],$clientrawdaily['12'],$clientrawdaily['13'],$clientrawdaily['14'],
$clientrawdaily['15'],$clientrawdaily['16'],$clientrawdaily['17'],$clientrawdaily['18'],$clientrawdaily['19'],
$clientrawdaily['20'],$clientrawdaily['21'],$clientrawdaily['22'],$clientrawdaily['23'],$clientrawdaily['24'],
$clientrawdaily['25'],$clientrawdaily['26'],$clientrawdaily['27'],$clientrawdaily['28'],$clientrawdaily['29'],
$clientrawdaily['30'],$clientrawdaily['31']);
$datay = $y;

if ($temp_conv == 1.8) {
	array_walk($datay, "CtoF");
	}

//create aray of min-temps
$b=array($clientrawdaily['32'],$clientrawdaily['33'],$clientrawdaily['34'],$clientrawdaily['35'],
$clientrawdaily['36'],$clientrawdaily['37'],$clientrawdaily['38'],$clientrawdaily['39'],$clientrawdaily['40'],
$clientrawdaily['41'],$clientrawdaily['42'],$clientrawdaily['43'],$clientrawdaily['44'],$clientrawdaily['45'],
$clientrawdaily['46'],$clientrawdaily['47'],$clientrawdaily['48'],$clientrawdaily['49'],$clientrawdaily['50'],
$clientrawdaily['51'],$clientrawdaily['52'],$clientrawdaily['53'],$clientrawdaily['54'],$clientrawdaily['55'],
$clientrawdaily['56'],$clientrawdaily['57'],$clientrawdaily['58'],$clientrawdaily['59'],$clientrawdaily['60'],
$clientrawdaily['61'],$clientrawdaily['62']);

$datay2 = $b;

if ($temp_conv == 1.8) {
	array_walk($datay2, "CtoF");
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

//Check for negative values in array and do a SetAuotMin(0) if none
array_walk($datay, "NegVal");
$negvalue1 = $negvalue;

// Check for positive values in array
array_walk($datay, "PosVal");
$posvalue1 = $posvalue;

//Check for negative values in array and do a SetAuotMin(0) if none
array_walk($datay2, "NegVal");
$negvalue2 = $negvalue;

// Check for positive values in array
array_walk($datay2, "PosVal");
$posvalue2 = $posvalue;

if (($negvalue1 == 1) or ($negvalue2 == 1)) $negvalue = 1;
if (($posvalue1 == 1) or ($posvalue2 == 1)) $posvalue = 1;
if (($negvalue1 == 0) and ($negvalue2 == 0)) $negvalue = 0;
if (($posvalue1 == 0) and ($posvalue2 == 0)) $posvalue = 0;

if ($negvalue == 0) {
  if ($temp_conv == 1) $automin = '$graph->yaxis->scale->SetAutoMin(0);';
  if ($temp_conv == 1.8) $automin = '$graph->yaxis->scale->SetAutoMin(32);';
  $grace = '$graph->yaxis->scale->SetGrace(20);';
}
if (($negvalue == 1) and ($posvalue == 0)) {
  if ($temp_conv == 1) $automin = '$graph->yaxis->scale->SetAutoMax(0);';
  if ($temp_conv == 1.8) $automin = '$graph->yaxis->scale->SetAutoMax(32);';
  $grace = '$graph->yaxis->scale->SetGrace(0,20);';
}
if (($negvalue == 1) and ($posvalue == 1)) {
  $automin = '';
  $grace = '$graph->yaxis->scale->SetGrace(20,20);';
}

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin");
$graph->SetMarginColor("$margincolour");
$graph->yaxis->scale->SetGrace(10);
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a line plot
$lplot = new LinePlot($datay);
$lplot2 = new LinePlot($datay2);

// Adjust colours
$lplot->SetColor("$temp_col_max");
$lplot->SetWeight(2);
$lplot2->SetColor("$temp_col_min");
$lplot2->SetWeight(2);

//Add plots
$graph->Add($lplot);
$graph->Add($lplot2);

// Setup the titles
$graph->title->Set("$txt_temp $txt_31d ($temp_unit)");
$graph->title->SetColor("$textcolour"); 
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->title->Set("$txt_date");
$graph->xaxis->title->SetColor("$xtextcolour"); 
$graph->xaxis->SetTickLabels($a); 
$graph->xaxis->SetTextLabelInterval(2);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xgrid->Show(true);
$graph->xaxis->HideTicks(true,true); 

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour"); 
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->yaxis->HideTicks(true,true);
eval($automin);

//legend
$lplot ->SetLegend("$txt_max");
$lplot2 ->SetLegend("$txt_min"); 
$graph->legend->Pos(0.5,0.92,"center","center");
$graph->legend->SetLayout(LEGEND_HOR); 

// Display the graph
$graph->Stroke();
?>

