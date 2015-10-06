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

$clientrawextra = get_raw("${hostloc}clientrawextra.txt");

//temp=========================================================================
$y=array($clientrawextra['21'],$clientrawextra['22'],$clientrawextra['23'],$clientrawextra['24'],
$clientrawextra['25'],$clientrawextra['26'],$clientrawextra['27'],$clientrawextra['28'],$clientrawextra['29'],
$clientrawextra['30'],$clientrawextra['31'],$clientrawextra['32'],$clientrawextra['33'],$clientrawextra['34'],
$clientrawextra['35'],$clientrawextra['36'],$clientrawextra['37'],$clientrawextra['38'],$clientrawextra['39'],
$clientrawextra['40'],$clientrawextra['566'],$clientrawextra['567'],$clientrawextra['568'],$clientrawextra['569']);
$datay = $y;

//hum=============================================================================
$y=array($clientrawextra['611'],$clientrawextra['612'],$clientrawextra['613'],$clientrawextra['614'],
$clientrawextra['615'],$clientrawextra['616'],$clientrawextra['617'],$clientrawextra['618'],$clientrawextra['619'],
$clientrawextra['620'],$clientrawextra['621'],$clientrawextra['622'],$clientrawextra['623'],$clientrawextra['624'],
$clientrawextra['625'],$clientrawextra['626'],$clientrawextra['627'],$clientrawextra['628'],$clientrawextra['629'],
$clientrawextra['630'],$clientrawextra['631'],$clientrawextra['632'],$clientrawextra['633'],$clientrawextra['634']);
$datay2 = $y;

//dew=============================================================================
$y=array(
dp($datay[0],$datay2[0]),dp($datay[1],$datay2[1]),dp($datay[2],$datay2[2]),dp($datay[3],$datay2[3]),
dp($datay[4],$datay2[4]),dp($datay[5],$datay2[5]),dp($datay[6],$datay2[6]),dp($datay[7],$datay2[7]),
dp($datay[8],$datay2[8]),dp($datay[9],$datay2[9]),dp($datay[10],$datay2[10]),dp($datay[11],$datay2[11]),
dp($datay[12],$datay2[12]),dp($datay[13],$datay2[13]),dp($datay[14],$datay2[14]),
dp($datay[15],$datay2[15]),dp($datay[16],$datay2[16]),dp($datay[17],$datay2[17]),
dp($datay[18],$datay2[18]),dp($datay[19],$datay2[19]),dp($datay[20],$datay2[20]),
dp($datay[21],$datay2[21]),dp($datay[22],$datay2[22]),dp($datay[23],$datay2[23])  );
$datay3 = $y;

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

$negvalue = 0;
$posvalue = 0;

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

//create timearray for the x-axis
$x=array($clientrawextra['459'],$clientrawextra['460'],$clientrawextra['461'],$clientrawextra['462'],
$clientrawextra['463'],$clientrawextra['464'],$clientrawextra['465'],$clientrawextra['466'],$clientrawextra['467'],
$clientrawextra['468'],$clientrawextra['469'],$clientrawextra['470'],$clientrawextra['471'],$clientrawextra['472'],
$clientrawextra['473'],$clientrawextra['474'],$clientrawextra['475'],$clientrawextra['476'],$clientrawextra['477'],
$clientrawextra['478'],$clientrawextra['578'],$clientrawextra['579'],$clientrawextra['580'],$clientrawextra['581']);
$datax = $x;

// Create the graph and specify the scale for both Y-axis
$graph = new Graph($xsize,$ysize,"auto",30);    
$graph->SetScale("textlin");
$graph->SetY2Scale("lin",0,100);
$graph->SetShadow();
$graph->SetMarginColor("$margincolour");

// Adjust the margin
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create the two linear plot
$lplot1=new LinePlot($datay);    // Temp
$lplot2=new LinePlot($datay2);  // Hum
$lplot3=new LinePlot($datay3);  //Dp

// Add the plot to the graph
$graph->Add($lplot1);
$graph->Add($lplot3);
$graph->AddY2($lplot2);

//titles
$graph->title->Set("$txt_temp1($temp_unit),$txt_dew1($temp_unit),$txt_hum1(%)");
$graph->title->Align("left","top");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->SetColor("$textcolour");

//x-axis
$graph->xaxis->title->SetColor("$xtextcolour");
$graph->xaxis->title->Set("$txt_24h");
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->xaxis->SetTitlemargin(25);
$graph->xaxis->SetLabelMargin(10);
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle($label_angle);
$graph->xaxis->SetTextLabelInterval($label_interval);
$graph->xaxis->SetPos("min"); 
$graph->xaxis->HideTicks(true,true); 
$graph->xaxis->SetColor("$xtextcolour"); 
$graph->xgrid->Show(true);

//y-axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->SetLabelFormat("%0.0f");
$graph->yaxis->HideTicks(true,true);
eval($automin);
eval($grace);

//y2-axis
$graph->y2axis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->y2axis->SetColor("$y2textcolour");
$graph->yaxis->SetLabelFormat("$temp_format");
$graph->y2axis->HideTicks(true,true); 
$graph->y2axis->scale->SetAutoMin(0);

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

// Set the legends for the plots
$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,7);
$lplot1->SetLegend("$txt_temp1");
$lplot2->SetLegend("$txt_hum1");
$lplot3->SetLegend("$txt_dew1");

// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.02,0.01,'right','top');

// Display the graph
$graph->Stroke();

?>


