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

$clientrawextra = get_raw("${hostloc}clientrawextra.txt");

// Create aray for y-axis
$y=array();$y=array($clientrawextra['704'],$clientrawextra['705'],$clientrawextra['706'],$clientrawextra['707'],
$clientrawextra['708'],$clientrawextra['709'],$clientrawextra['710'],$clientrawextra['711'],$clientrawextra['712'],
$clientrawextra['713'],$clientrawextra['714'],$clientrawextra['715'],$clientrawextra['716'],$clientrawextra['717'],
$clientrawextra['718'],$clientrawextra['719'],$clientrawextra['720'],$clientrawextra['721'],$clientrawextra['722'],
$clientrawextra['723'],$clientrawextra['724'],$clientrawextra['725'],$clientrawextra['726'],$clientrawextra['727']);
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
$bplot = new BarPlot($datay);

$bplot->SetWeight(2);
$bplot->SetColor("$light_col");
$bplot->SetFillGradient("$light_col","#EEEEEE",GRAD_LEFT_REFLECTION);

$graph->Add($bplot);

// titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_lightning $txt_24h");
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

