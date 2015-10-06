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

$clientrawdaily = get_raw ("${hostloc}clientrawdaily.txt");//for this graph we only need the values from this file

// Here we create an array of the values we need depending of the month we are in (each month we shift to the left 1 place)
$x=array();
$y=array(); 
$month = date("m");
  if($month == '1')
$y=array($clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198']);
$datay = $y;
  if($month == '2')
$y=array($clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187']);
$datay = $y;
  if($month == '3')
$y=array($clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188']);
$datay = $y;
  if($month == '4')
$y=array($clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189']);
$datay = $y;
  if($month == '5')
$y=array($clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190']);
$datay = $y;
  if($month == '6')
$y=array($clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191']);
$datay = $y;
  if($month == '7')
$y=array($clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192']);
$datay = $y;
  if($month == '8')
$y=array($clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193']);
$datay = $y;
  if($month == '9')
$y=array($clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194']);
$datay = $y;
  if($month == '10')
$y=array($clientrawdaily['196'],$clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195']);
$datay = $y;
  if($month == '11')
$y=array($clientrawdaily['197'],$clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196']);
$datay = $y;
  if($month == '12')
$y=array($clientrawdaily['198'],$clientrawdaily['187'],$clientrawdaily['188'],$clientrawdaily['189'],$clientrawdaily['190'],$clientrawdaily['191'],$clientrawdaily['192'],$clientrawdaily['193'],$clientrawdaily['194'],$clientrawdaily['195'],$clientrawdaily['196'],$clientrawdaily['197']);
$datay = $y;

if ($rain_conv != 1) {
	array_walk($datay, "MtoI");
	}

//With this-one we calculate the labels for the x-axis
//what is the x-label depending on the month we are(remember we show the values of the previous month)
//so if we are december, the last label is Nov
$month = date("m");
  if($month == '1')
       $x = array("$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12");
  if($month == '2')
       $x = array("$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1");
  if($month == '3')
       $x = array("$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2");
  if($month == '4')
       $x = array("$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3");
  if($month == '5')
       $x = array("$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4");
  if($month == '6')
       $x = array("$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5");
  if($month == '7')
       $x = array("$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6");
  if($month == '8')
       $x = array("$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7");
  if($month == '9')
       $x = array("$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8");
  if($month == '10')
       $x = array("$txt_mth10","$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9");
  if($month == '11')
       $x = array("$txt_mth11","$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10");
  if($month == '12')
       $x = array("$txt_mth12","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11");

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",60);	
$graph->SetScale("textlin");

//Setup margin color
$graph->SetMarginColor("$margincolour");

//Here we set a dropshadow around the graph
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->img->SetMargin(30,30,30,40);

// Create a bar pot
$bplot = new BarPlot($datay);

//Setup width of bars
$bplot->SetWidth(0.7);

// Setup color for gradient fill style 
$bplot->SetFillGradient("$rain_col","#EEEEEE",GRAD_LEFT_REFLECTION);

// Use 10% "grace" to get slightly larger scale then max of
// data or the value of max will end up in the title area
// you can commend this out if you dont show the values themselves
$graph->yscale->SetGrace(10);

//Show the values
$bplot->value->Show(); //you can comment this out if you do'nt want to show the value's

//Add the graph to the graph-area
$graph->Add($bplot);

// Setup the titles
$graph->title->Set("$txt_rain $txt_12m ($rain_unit)");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->SetTickLabels($x); 
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

