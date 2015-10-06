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

$clientrawextra = get_raw("${hostloc}clientrawextra.txt");

//temp=========================================================================
$y=array($clientrawextra['636'],$clientrawextra['637'],$clientrawextra['638'],$clientrawextra['639'],
$clientrawextra['640'],$clientrawextra['641'],$clientrawextra['642'],$clientrawextra['643'],$clientrawextra['644'],
$clientrawextra['645'],$clientrawextra['646'],$clientrawextra['647'],$clientrawextra['648'],$clientrawextra['649'],
$clientrawextra['650'],$clientrawextra['651'],$clientrawextra['652'],$clientrawextra['653'],$clientrawextra['654'],
$clientrawextra['655'],$clientrawextra['656'],$clientrawextra['657'],$clientrawextra['658'],$clientrawextra['659']);
$datay = $y;

if ($temp_conv == 1.8) {
	array_walk($datay, "CtoF");
	}

//Check for negative values in array and do a SetAuotMin(0) if none
array_walk($datay, "NegVal");

// Check for positive values in array
array_walk($datay, "PosVal");

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

//create timearray for the x-axis
$x=array($clientrawextra['459'],$clientrawextra['460'],$clientrawextra['461'],$clientrawextra['462'],
$clientrawextra['463'],$clientrawextra['464'],$clientrawextra['465'],$clientrawextra['466'],$clientrawextra['467'],
$clientrawextra['468'],$clientrawextra['469'],$clientrawextra['470'],$clientrawextra['471'],$clientrawextra['472'],
$clientrawextra['473'],$clientrawextra['474'],$clientrawextra['475'],$clientrawextra['476'],$clientrawextra['477'],
$clientrawextra['478'],$clientrawextra['578'],$clientrawextra['579'],$clientrawextra['580'],$clientrawextra['581']);
$datax = $x;

// Create the graph and specify the scale for both Y-axis
$graph = new Graph($xsize,$ysize,"auto",30);    
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->SetMarginColor("$margincolour");

// Adjust the margin
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create the two linear plot
$lplot=new LinePlot($datay);

// Set the colors for the plots
$lplot->SetColor("$temp_col1");
if (($negvalue == 1) and ($posvalue == 0)) $lplot->SetColor("$temp_col2");
if (($negvalue == 1) and ($posvalue == 1)) $lplot->SetColor("$temp_col3");
$lplot->SetWeight(2);

// Add the plot to the graph
$graph->Add($lplot);

//titles
$graph->title->Set("$txt_temp_in $txt_24h ($temp_unit)");
$graph->title->Align("center","top");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetTitlemargin(25);
$graph->xaxis->SetLabelMargin(10);
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle($label_angle);
$graph->xaxis->SetTextLabelInterval($label_interval);
$graph->xaxis->SetPos("min");
$graph->xaxis->HideTicks(true,true);
$graph->xaxis->SetColor("$xtextcolour");
$graph->xgrid->Show(true);

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->yaxis->HideTicks(true,true);
eval($automin);
eval($grace);

// Display the graph
$graph->Stroke();
?>

