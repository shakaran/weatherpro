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

$clientrawdaily = get_raw("${hostloc}clientrawdaily.txt");

// Here we create an array of the values we need depending of the day of the week we are in (each month we shift to the left 1 place)
$x=array();
$y=array(); 
$y=array($clientrawdaily['233'],$clientrawdaily['234'],$clientrawdaily['235'],$clientrawdaily['236'],$clientrawdaily['237'],$clientrawdaily['238'],$clientrawdaily['239'],$clientrawdaily['240'],$clientrawdaily['241'],$clientrawdaily['242'],$clientrawdaily['243'],$clientrawdaily['244'],$clientrawdaily['245'],$clientrawdaily['246'],$clientrawdaily['247'],$clientrawdaily['248'],$clientrawdaily['249'],$clientrawdaily['250'],$clientrawdaily['251'],$clientrawdaily['252'],$clientrawdaily['253'],$clientrawdaily['254'],$clientrawdaily['255'],$clientrawdaily['256'],$clientrawdaily['257'],$clientrawdaily['258'],$clientrawdaily['259'],$clientrawdaily['260']);
$datay = $y;

if ($temp_conv != 1) {
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

// Set the colors for the plots
$lplot->SetColor("$temp_col1");
if (($negvalue == 1) and ($posvalue == 0)) $lplot->SetColor("$temp_col2");
if (($negvalue == 1) and ($posvalue == 1)) $lplot->SetColor("$temp_col3");
$lplot->SetWeight(2);

//Add the graph to the graph-area
$graph->Add($lplot);

// Setup the titles
$graph->title->Set("$txt_temp $txt_7d ($temp_unit)");
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
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->yaxis->HideTicks(true,true);
eval($automin);
eval($grace);

// Display the graph
$graph->Stroke();
?>

