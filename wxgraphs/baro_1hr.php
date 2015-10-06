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
$jj = 301;
while ($ii < 60) {
  $y[$ii] = $clientrawhour[$jj];
  $ii = $ii+1;
  $jj = $jj+1;
}
$datay = $y;

if ($pres_conv != 1) {
	array_walk($datay, "HtoI");
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
$graph = new Graph($xsize,$ysize,"auto",30);
$graph->SetScale("textlin");
$graph->yscale->SetAutoMin($pres_automin);
$graph->yscale->SetAutoMax($pres_automax);

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
$graph->title->Set("$txt_baro $txt_1h ($pres_unit)");
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
$graph->yaxis->HideTicks(true,true);

// Display the graph
$graph->Stroke();
?>