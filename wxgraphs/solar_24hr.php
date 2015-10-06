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
$y=array();$y=array($clientrawextra['491'],$clientrawextra['492'],$clientrawextra['493'],$clientrawextra['494'],
$clientrawextra['495'],$clientrawextra['496'],$clientrawextra['497'],$clientrawextra['498'],$clientrawextra['499'],
$clientrawextra['500'],$clientrawextra['501'],$clientrawextra['502'],$clientrawextra['503'],$clientrawextra['504'],
$clientrawextra['505'],$clientrawextra['506'],$clientrawextra['507'],$clientrawextra['508'],$clientrawextra['509'],
$clientrawextra['510'],$clientrawextra['582'],$clientrawextra['583'],$clientrawextra['584'],$clientrawextra['585']);
$datay = $y;

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
$graph->yaxis->scale->SetGrace(10);
$graph->SetMarginColor("$margincolour");

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a line plot
$lplot = new LinePlot($datay);

$lplot->SetWeight(2);
$lplot->SetColor("$solar_col");

$graph->Add($lplot);

// titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_solar $txt_24h ($solar_unit)");
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
$graph->yscale->SetAutoMin(0);
$graph->yaxis->HideTicks(true,true);
 
// Display the graph
$graph->Stroke();
?>

