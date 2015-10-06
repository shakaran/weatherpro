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

$clientrawhour = get_raw("${hostloc}clientrawhour.txt");

$process_hour = get_date("${hostloc}clientrawhour.txt");

// Here we create an array of the values we need depending of the day of the week we are in (each month we shift to the left 1 place)
$x=array();
$y=array(); 
$ii = 0;
$jj = 1;
while ($ii < 60) {
  $y[$ii] = $clientrawhour[$jj];
  $ii = $ii+1;
  $jj = $jj+1;
}
$datay = $y;

if ($speed_conv != 1) {
	array_walk($datay, "KtoV");
	}

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

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",60);	
$graph->SetScale("textlin");

//Setup margin color
$graph->SetMarginColor("$margincolour");

//Here we set a dropshadow around the graph
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a line plot
$lplot = new LinePlot($datay);

// Use 10% "grace" to get slightly larger scale then max of
// data or the value of max will end up in the title area
// you can commend this out if you dont show the values themselves
$graph->yscale->SetGrace(2);

//Add the graph to the graph-area
$graph->Add($lplot);
$lplot->SetWeight(2);
$lplot->SetColor("$speed_col");

// Setup the titles
$graph->title->Set("$txt_wind_sp $txt_1h ($speed_unit)");
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetPos("0");
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xgrid->Show(true);
$graph->xaxis->SetColor("$xtextcolour");
$graph->xaxis->SetTickLabels($x);
$graph->xaxis->HideTicks(true,true);

//y-axis
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yscale->SetAutoMin(0);
$graph->yaxis->HideTicks(true,true);


// Display the graph
$graph->Stroke();
?>