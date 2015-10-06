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

// Here we create an array of the values we need depending of the day of the week we are in (each month we shift to the left 1 place)
$x=array();
$y=array(); 
$rday = date("w");
  if($rday == '1')
$y=array(($clientrawextra['484']/10),($clientrawextra['485']/10),($clientrawextra['486']/10),($clientrawextra['487']/10),($clientrawextra['488']/10),($clientrawextra['489']/10),($clientrawextra['490']/10));
$datay = $y;
  if($rday == '2')
$y=array(($clientrawextra['485']/10),($clientrawextra['486']/10),($clientrawextra['487']/10),($clientrawextra['488']/10),($clientrawextra['489']/10),($clientrawextra['490']/10),($clientrawextra['484']/10));
$datay = $y;
  if($rday == '3')
$y=array(($clientrawextra['486']/10),($clientrawextra['487']/10),($clientrawextra['488']/10),($clientrawextra['489']/10),($clientrawextra['490']/10),($clientrawextra['484']/10),($clientrawextra['485']/10));
$datay = $y;
  if($rday == '4')
$y=array(($clientrawextra['487']/10),($clientrawextra['488']/10),($clientrawextra['489']/10),($clientrawextra['490']/10),($clientrawextra['484']/10),($clientrawextra['485']/10),($clientrawextra['486']/10));
$datay = $y;
  if($rday == '5')
$y=array(($clientrawextra['488']/10),($clientrawextra['489']/10),($clientrawextra['490']/10),($clientrawextra['484']/10),($clientrawextra['485']/10),($clientrawextra['486']/10),($clientrawextra['487']/10));
$datay = $y;
  if($rday == '6')
$y=array(($clientrawextra['489']/10),($clientrawextra['490']/10),($clientrawextra['484']/10),($clientrawextra['485']/10),($clientrawextra['486']/10),($clientrawextra['487']/10),($clientrawextra['488']/10));
$datay = $y;
  if($rday == '0')
$y=array(($clientrawextra['490']/10),($clientrawextra['484']/10),($clientrawextra['485']/10),($clientrawextra['486']/10),($clientrawextra['487']/10),($clientrawextra['488']/10),($clientrawextra['489']/10));
$datay = $y;


if ($rain_conv != 1) {
	array_walk($datay, "MtoI");
	}

//With this-one we calculate the labels for the x-axis
//what is the x-label depending on the month we are(remember we show the values of the previous month)
//so if we are december, the last label is Nov
$rday = date("w");
  if($rday == '1')
       $x = array("$txt_day1","$txt_day2","$txt_day3","$txt_day4","$txt_day5","$txt_day6","$txt_day7");
  if($rday == '2')
       $x = array("$txt_day2","$txt_day3","$txt_day4","$txt_day5","$txt_day6","$txt_day7","$txt_day1");
  if($rday == '3')
       $x = array("$txt_day3","$txt_day4","$txt_day5","$txt_day6","$txt_day7","$txt_day1","$txt_day2");
  if($rday == '4')
       $x = array("$txt_day4","$txt_day5","$txt_day6","$txt_day7","$txt_day1","$txt_day2","$txt_day3");
  if($rday == '5')
       $x = array("$txt_day5","$txt_day6","$txt_day7","$txt_day1","$txt_day2","$txt_day3","$txt_day4");
  if($rday == '6')
       $x = array("$txt_day6","$txt_day7","$txt_day1","$txt_day2","$txt_day3","$txt_day4","$txt_day5");
  if($rday == '0')
       $x = array("$txt_day7","$txt_day1","$txt_day2","$txt_day3","$txt_day4","$txt_day5","$txt_day6");

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
$bplot = new BarPlot($datay);

//Setup width of bars
$bplot->SetWidth(0.7);

// Setup color for gradient fill style 
$bplot->SetFillGradient("$rain_col","#EEEEEE",GRAD_LEFT_REFLECTION);
$bplot->value->SetFormat("$rain_format");

// Use 10% "grace" to get slightly larger scale then max of
// data or the value of max will end up in the title area
// you can commend this out if you dont show the values themselves
$graph->yscale->SetGrace(10);

//Show the values
$bplot->value->Show(); //you can comment this out if you don't want to show the value's

//Add the graph to the graph-area
$graph->Add($bplot);

// Setup the titles
$graph->title->Set("$txt_rain $txt_7d ($rain_unit)");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xgrid->Show(true);
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xaxis->SetTickLabels($x);

//y-axis
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->HideTicks(true,true); 

// Display the graph
$graph->Stroke();
?>

