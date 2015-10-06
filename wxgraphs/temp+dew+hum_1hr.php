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

// Create arrays for y-axis
//temp Celsius in clientrawhour.txt
$y=array();
$ii = 0;
$jj = 181;
while ($ii < 60) {
  $y[$ii] = $clientrawhour[$jj];
  $ii = $ii+1;
  $jj = $jj+1;
}
$datay = $y;

//humidity
$y2=array();
$ii = 0;
$jj = 241;
while ($ii < 60) {
  $y2[$ii] = $clientrawhour[$jj];
  $ii = $ii+1;
  $jj = $jj+1;
}
$datay2 = $y2;

//dew point Calculated
$y3=array();
$ii = 0;
while ($ii < 60) {
  $y3[$ii] = dp($datay[$ii],$datay2[$ii]);
  $ii = $ii+1;
}
$datay3 = $y3;

if ($temp_conv == 1.8) {
	array_walk($datay, "CtoF");
	array_walk($datay3, "CtoF");
}

//Check for negative values in array and do a SetAuotMin(0) if none
array_walk($datay, "NegVal");
$negvalue1 = $negvalue;

// Check for positive values in array
array_walk($datay, "PosVal");
$posvalue1 = $posvalue;

//Check for negative values in array and do a SetAuotMin(0) if none
array_walk($datay3, "NegVal");
$negvalue3 = $negvalue;

// Check for positive values in array
array_walk($datay3, "PosVal");
$posvalue3 = $posvalue;

if (($negvalue1 == 1) or ($negvalue3 == 1)) $negvalue = 1;
if (($posvalue1 == 1) or ($posvalue3 == 1)) $posvalue = 1;
if (($negvalue1 == 0) and ($negvalue3 == 0)) $negvalue = 0;
if (($posvalue1 == 0) and ($posvalue3 == 0)) $posvalue = 0;

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
$datax = $x;

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);
$graph->SetScale("textlin");
$graph->SetY2Scale("lin",0,100);
$graph->SetShadow();
$graph->SetMarginColor("$margincolour");

// Adjust the margin a bit to make more room for titles
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create line plots
$lplot1 = new LinePlot($datay);    //Temp
$lplot2 = new LinePlot($datay2);   //Hum
$lplot3 = new LinePlot($datay3);   //Dew

// Add the plot to the graph
$graph->Add($lplot1);
$graph->Add($lplot3);
$graph->AddY2($lplot2);

// Set the colors for the plots
$lplot1->SetColor("$temp_col1");
if (($negvalue1 == 1) and ($posvalue1 == 0)) $lplot1->SetColor("$temp_col2");
if (($negvalue1 == 1) and ($posvalue1 == 1)) $lplot1->SetColor("$temp_col3");
$lplot1->SetWeight(2);

$lplot2->SetWeight(2);
$lplot2->SetColor("$hum_col");
$lplot2->SetFillColor("$hum_col@0.9");
$lplot3->SetWeight(2);
$lplot3->SetColor("$dp_col");

// titles
$graph->title->Set("$txt_temp1($temp_unit),$txt_dew1($temp_unit),$txt_hum1(%) $txt_1h");
$graph->title->Align("left","top");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
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
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->yaxis->HideTicks(true,true);
eval($automin);
eval($grace);

//y2-axis
$graph->y2axis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->y2axis->SetColor("$y2textcolour");
$graph->y2axis->SetLabelFormat("%0.0f");
$graph->y2axis->HideTicks(true,true);
$graph->y2scale->SetAutoMin(0);

// Set the legends for the plots
$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,8);
$lplot1->SetLegend("$txt_temp1");
$lplot2->SetLegend("$txt_hum1");
$lplot3->SetLegend("$txt_dew1");

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.05,0.88);
 
// Display the graph
$graph->Stroke();
?>

