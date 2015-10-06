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

$clientrawhour = get_raw("${hostloc}clientrawhour.txt");

$process_hour = get_date("${hostloc}clientrawhour.txt");

// Here we create an array of the values we need depending of the day of the week we are in (each month we shift to the left 1 place)
$x=array();
$y=array(); 
$ii = 0;
$jj = 121;
while ($ii < 60) {
  $y[$ii] = $clientrawhour[$jj];
  $ii = $ii+1;
  $jj = $jj+1;
}
$datay = $y;

//With this-one we calculate the labels for the x-axis

$ii = 0;
while ($ii < 60) {
  if (($ii == 0) or ($ii == 10) or ($ii == 20) or ($ii == 30) or ($ii == 40) or ($ii == 50) or ($ii == 59)) {
    if ($ii == 0) $iii = "00";
    else $iii = $ii;
    $x[$ii] = $process_hour.":".$iii;
  }
  else {
    $x[$ii] = " ";
  }
  $ii =$ii+1;
}

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
$graph->title->Set("$txt_wind_dr $txt_1h");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetTitlemargin(25);
//$graph->xaxis->Settitle("time");
$graph->xaxis->SetLabelMargin(10);
$graph->xaxis->SetTickLabels($x);
//$graph->xaxis->SetLabelAngle($label_angle);
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
