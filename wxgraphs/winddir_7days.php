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

$clientrawdaily = get_raw("${hostloc}clientrawdaily.txt");

$x=array();
$y=array(); 
$yy = 0;
for ($xx = 317; $xx <= 344; $xx++) {
    $y[$yy] = $clientrawdaily[$xx];
    $yy = $yy+1;
}
$datay = $y;

/*print_r($y);
echo "<br>";
print_r($clientrawdaily);
exit;*/

//With this-one we calculate the labels for the x-axis
//what is the x-label depending on the month we are(remember we show the values of the previous month)
//so if we are december, the last label is Nov
$rday = date("w");
  if($rday == '1')
       $x = array(' ',"$txt_day1",' ',' ',' ',"$txt_day2",' ',' ',' ',"$txt_day3",' ',' ',' ',"$txt_day4",' ',' ',' ',"$txt_day5",' ',' ',' ',"$txt_day6",' ',' ',' ',"$txt_day7",' ',' ');
  if($rday == '2')
       $x = array(' ',"$txt_day2",' ',' ',' ',"$txt_day3",' ',' ',' ',"$txt_day4",' ',' ',' ',"$txt_day5",' ',' ',' ',"$txt_day6",' ',' ',' ',"$txt_day7",' ',' ',' ',"$txt_day1",' ',' ');
  if($rday == '3')
       $x = array(' ',"$txt_day3",' ',' ',' ',"$txt_day4",' ',' ',' ',"$txt_day5",' ',' ',' ',"$txt_day6",' ',' ',' ',"$txt_day7",' ',' ',' ',"$txt_day1",' ',' ',' ',"$txt_day2",' ',' ');
  if($rday == '4')
       $x = array(' ',"$txt_day4",' ',' ',' ',"$txt_day5",' ',' ',' ',"$txt_day6",' ',' ',' ',"$txt_day7",' ',' ',' ',"$txt_day1",' ',' ',' ',"$txt_day2",' ',' ',' ',"$txt_day3",' ',' ');
  if($rday == '5')
       $x = array(' ',"$txt_day5",' ',' ',' ',"$txt_day6",' ',' ',' ',"$txt_day7",' ',' ',' ',"$txt_day1",' ',' ',' ',"$txt_day2",' ',' ',' ',"$txt_day3",' ',' ',' ',"$txt_day4",' ',' ');
  if($rday == '6')
       $x = array(' ',"$txt_day6",' ',' ',' ',"$txt_day7",' ',' ',' ',"$txt_day1",' ',' ',' ',"$txt_day2",' ',' ',' ',"$txt_day3",' ',' ',' ',"$txt_day4",' ',' ',' ',"$txt_day5",' ',' ');
  if($rday == '0')
       $x = array(' ',"$txt_day7",' ',' ',' ',"$txt_day1",' ',' ',' ',"$txt_day2",' ',' ',' ',"$txt_day3",' ',' ',' ',"$txt_day4",' ',' ',' ',"$txt_day5",' ',' ',' ',"$txt_day6",' ',' ');

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
$graph->title->Set("$txt_wind_dr_av $txt_7d");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetTitlemargin(25);
$graph->xaxis->SetLabelMargin(10);
$graph->xaxis->SetTickLabels($datax);
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
