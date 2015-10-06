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

//$clientraw = get_raw("${hostloc}clientraw.txt");
$clientrawdaily = get_raw("${hostloc}clientrawdaily.txt");
//$clientrawdaily = get_raw ("${hostloc}clientrawdaily.txt");//for this graph we only need the values from this file

// Here we create an array of the values we need depending of the day of the week we are in (each month we shift to the left 1 place)
$x=array();
$y=array(); 
$yy = 0;
for ( $xx = 261; $xx <= 288; $xx++) {
    $y[$yy] = $clientrawdaily[$xx];
    $yy = $yy+1;
}
$datay = $y;

//print_r($y);
//exit;

if ($pres_conv != 1) {
	array_walk($datay, "HtoI");
	}

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

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",60);
$graph->SetScale("textlin");

//Setup margin color
$graph->SetMarginColor("$margincolour");

//Here we set a dropshadow around the graph
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a bar pot
$lplot = new LinePlot($datay);

$lplot->SetWeight(2);
$lplot->SetColor("$pres_col");

//Add the graph to the graph-area
$graph->Add($lplot);

// Setup the titles
$graph->title->Set("$txt_baro $txt_7d ($pres_unit)");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xgrid->Show(true);
$graph->xaxis->SetColor("$xtextcolour");
$graph->xaxis->SetTickLabels($x);
$graph->xaxis->HideTicks(true,true);

//y-axis
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->SetLabelFormat("$pres_format");
$graph->yscale->SetAutoMin($pres_automin);
$graph->yscale->SetAutoMax($pres_automax);
$graph->yaxis->HideTicks(true,true);

// Display the graph
$graph->Stroke();
?>

