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
include ($jploc."jpgraph_scatter.php");

$clientrawextra = get_raw("${hostloc}clientrawextra.txt");

// Create aray for y-axis
$y=array();$y=array($clientrawextra['536'],$clientrawextra['537'],$clientrawextra['538'],$clientrawextra['539'],
$clientrawextra['540'],$clientrawextra['541'],$clientrawextra['542'],$clientrawextra['543'],$clientrawextra['544'],
$clientrawextra['545'],$clientrawextra['546'],$clientrawextra['547'],$clientrawextra['548'],$clientrawextra['549'],
$clientrawextra['550'],$clientrawextra['551'],$clientrawextra['552'],$clientrawextra['553'],$clientrawextra['554'],
$clientrawextra['555'],$clientrawextra['590'],$clientrawextra['591'],$clientrawextra['592'],$clientrawextra['593']);
$datay = $y;

//create timearray for the x-axis
$x=array($clientrawextra['459'],$clientrawextra['460'],$clientrawextra['461'],$clientrawextra['462'],
$clientrawextra['463'],$clientrawextra['464'],$clientrawextra['465'],$clientrawextra['466'],$clientrawextra['467'],
$clientrawextra['468'],$clientrawextra['469'],$clientrawextra['470'],$clientrawextra['471'],$clientrawextra['472'],
$clientrawextra['473'],$clientrawextra['474'],$clientrawextra['475'],$clientrawextra['476'],$clientrawextra['477'],
$clientrawextra['478'],$clientrawextra['578'],$clientrawextra['579'],$clientrawextra['580'],$clientrawextra['581']);
$datax = $x;

$NESWN = array(0 => "$txt_N", 45 => 'NE', 90 =>"$txt_E", 135 => 'SE', 180 => "$txt_S", 225 => 'SW', 270 => "$txt_W", 315 => 'NW', 360 => "$txt_N");

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin",0,360);
$graph->yaxis->scale->ticks->Set(90,45);
$graph->SetY2Scale("lin",0,360);
$graph->y2axis->scale->ticks->Set(90);
$graph->SetMarginColor("$margincolour");

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->SetMargin($lm,$rm,$tm,$bm);

// titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_wind_dr_av $txt_24h");
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
$graph->yaxis->HideTicks(true,true);

$graph->y2axis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->y2axis->SetColor("$y2textcolour");
$graph->y2axis->HideTicks(true,true);
$graph->y2axis->SetTickLabels($NESWN);

$sp1 = new ScatterPlot($datay);

$sp1->mark->SetType(MARK_SQUARE);
$sp1->mark->SetFillColor("$speed_col");
$sp1->mark->SetWidth(3);

$graph->Add($sp1);

$graph->Stroke();
?>
