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

// Here we get the data from the clientraw file
$x=array();
$y=array(); 
$ii = 0;
$jj = 181;
while ($ii < 60) {
  $y[$ii] = $clientrawhour[$jj];
  $ii = $ii+1;
  $jj = $jj+1;
}
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

// Create a bar pot
$lplot = new LinePlot($datay);

eval($grace);

//Show the values
//$lplot->value->Show(); //you can comment this out if you do'nt want to show the value's

//Add the graph to the graph-area
$graph->Add($lplot);

// Set the colors for the plots
$lplot->SetColor("$temp_col1");
if (($negvalue == 1) and ($posvalue == 0)) $lplot->SetColor("$temp_col2");
if (($negvalue == 1) and ($posvalue == 1)) $lplot->SetColor("$temp_col3");
$lplot->SetWeight(2);

// Setup the titles
$graph->title->Set("$txt_temp $txt_1h ($temp_unit)");
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
$graph->yaxis->SetTextLabelInterval(1);
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->yaxis->HideTicks(true,true);
eval($automin);

// Display the graph
$graph->Stroke();
?>