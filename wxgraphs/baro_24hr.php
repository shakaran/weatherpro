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

// Create aray for y-axis
$y=array($clientrawextra['439'],$clientrawextra['440'],$clientrawextra['441'],$clientrawextra['442'],
$clientrawextra['443'],$clientrawextra['444'],$clientrawextra['445'],$clientrawextra['446'],$clientrawextra['447'],
$clientrawextra['448'],$clientrawextra['449'],$clientrawextra['450'],$clientrawextra['451'],$clientrawextra['452'],
$clientrawextra['453'],$clientrawextra['454'],$clientrawextra['455'],$clientrawextra['456'],$clientrawextra['457'],
$clientrawextra['458'],$clientrawextra['574'],$clientrawextra['575'],$clientrawextra['576'],$clientrawextra['577']);
$datay = $y;

if ($pres_conv != 1) {
	array_walk($datay, "HtoI");
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
$graph->yscale->SetAutoMin($pres_automin);
$graph->yscale->SetAutoMax($pres_automax);
$graph->SetMarginColor("$margincolour");
$graph->SetShadow();
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a bar pot
$lplot = new LinePlot($datay);
$lplot->SetWeight(2);
$lplot->SetColor("$pres_col");

//Add plot
$graph->Add($lplot);

// titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_baro $txt_24h ($pres_unit)");
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
$graph->yaxis->SetLabelFormat("$pres_format");
$graph->yaxis->HideTicks(true,true);
 
// Display the graph
$graph->Stroke();
?>

