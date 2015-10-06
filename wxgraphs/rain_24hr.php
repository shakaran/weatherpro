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

// Now creating data-arays
$y=array($clientrawextra['41'],$clientrawextra['42'],$clientrawextra['43'],$clientrawextra['44'],
$clientrawextra['45'],$clientrawextra['46'],$clientrawextra['47'],$clientrawextra['48'],$clientrawextra['49'],
$clientrawextra['50'],$clientrawextra['51'],$clientrawextra['52'],$clientrawextra['53'],$clientrawextra['54'],
$clientrawextra['55'],$clientrawextra['56'],$clientrawextra['57'],$clientrawextra['58'],$clientrawextra['59'],
$clientrawextra['60'],$clientrawextra['570'],$clientrawextra['571'],$clientrawextra['572'],$clientrawextra['573']);
$datay = $y;

if ($rain_conv != 1) {
	array_walk($datay, "MtoI");
	}

//create timearray for the x-axis
$x=array($clientrawextra['459'],$clientrawextra['460'],$clientrawextra['461'],$clientrawextra['462'],
$clientrawextra['463'],$clientrawextra['464'],$clientrawextra['465'],$clientrawextra['466'],$clientrawextra['467'],
$clientrawextra['468'],$clientrawextra['469'],$clientrawextra['470'],$clientrawextra['471'],$clientrawextra['472'],
$clientrawextra['473'],$clientrawextra['474'],$clientrawextra['475'],$clientrawextra['476'],$clientrawextra['477'],
$clientrawextra['478'],$clientrawextra['578'],$clientrawextra['579'],$clientrawextra['580'],$clientrawextra['581']);
$datax = $x;

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin");
$graph->SetMarginColor("$margincolour");
$graph->SetMargin($lm,$rm,$tm,$bm);
$graph->SetShadow();

// Create a bar pot
$lplot = new LinePlot($datay);
$lplot->SetWeight(3);
$lplot->SetColor("$rain_col");
$lplot->SetFillColor("$rain_col");

//Add plots
$graph->Add($lplot);

// title
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_rain $txt_24h ($rain_unit)");
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
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xaxis->HideTicks(true,true); 
$graph->xgrid->Show(true);

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetLabelFormat("$rain_format");
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->HideTicks(true,true); 
$graph->yaxis->scale->SetGrace(10);

// Display the graph
$graph->Stroke();
?>

