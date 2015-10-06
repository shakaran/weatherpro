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
$y=array($clientrawextra['21'],$clientrawextra['22'],$clientrawextra['23'],$clientrawextra['24'],
$clientrawextra['25'],$clientrawextra['26'],$clientrawextra['27'],$clientrawextra['28'],$clientrawextra['29'],
$clientrawextra['30'],$clientrawextra['31'],$clientrawextra['32'],$clientrawextra['33'],$clientrawextra['34'],
$clientrawextra['35'],$clientrawextra['36'],$clientrawextra['37'],$clientrawextra['38'],$clientrawextra['39'],
$clientrawextra['40'],$clientrawextra['566'],$clientrawextra['567'],$clientrawextra['568'],$clientrawextra['569']);
$ydata = $y;

if ($temp_conv == 1.8) {
	array_walk($ydata, "CtoF");
	}

//Check for negative values in array and do a SetAuotMin(0) if none
array_walk($ydata, "NegVal");

// Check for positive values in array
array_walk($ydata, "PosVal");

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

//hum=============================================================================
$y=array($clientrawextra['611'],$clientrawextra['612'],$clientrawextra['613'],$clientrawextra['614'],
$clientrawextra['615'],$clientrawextra['616'],$clientrawextra['617'],$clientrawextra['618'],$clientrawextra['619'],
$clientrawextra['620'],$clientrawextra['621'],$clientrawextra['622'],$clientrawextra['623'],$clientrawextra['624'],
$clientrawextra['625'],$clientrawextra['626'],$clientrawextra['627'],$clientrawextra['628'],$clientrawextra['629'],
$clientrawextra['630'],$clientrawextra['631'],$clientrawextra['632'],$clientrawextra['633'],$clientrawextra['634']);
$y2data = $y;

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
$graph->SetY2Scale("lin",0,100);
$graph->SetShadow();
$graph->SetMarginColor("$margincolour");

// Adjust the margin
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create the two linear plot
$lplot=new LinePlot($ydata);
$lplot2=new LinePlot($y2data);

// Add the plot to the graph
$graph->Add($lplot);
$graph->AddY2($lplot2);
$lplot->SetWeight(2);
$lplot2->SetWeight(2);

//titles
$graph->title->Set("$txt_temp - $txt_hum $txt_24h");
$graph->title->Align("left","top");
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
$graph->yaxis->SetLabelFormat("%0.1f");
$graph->yaxis->HideTicks(true,true);
eval($automin);
eval($grace);

//y2-axis
$graph->y2axis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->y2axis->SetColor("$y2textcolour");
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->y2axis->HideTicks(true,true); 
$graph->y2grid->Show(true);

// Set the colors for the plots
$lplot->SetColor("$temp_col1");
if (($negvalue == 1) and ($posvalue == 0)) $lplot->SetColor("$temp_col2");
if (($negvalue == 1) and ($posvalue == 1)) $lplot->SetColor("$temp_col3");
$lplot->SetWeight(2);

$lplot2->SetColor("$hum_col");
$lplot2->SetWeight(2);

// Set the legends for the plots
$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,8);
$lplot->SetLegend("$temp_unit");
$lplot2->SetLegend("%");

// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.02,0.01);

// Display the graph
$graph->Stroke();
?>

