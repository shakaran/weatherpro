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

$clientraw = get_raw("${hostloc}clientraw.txt");

// Create aray for y-axis
$y=array();$y=array($clientraw['80'],$clientraw['81'],$clientraw['82'],$clientraw['83'],$clientraw['84'],
$clientraw['85'],$clientraw['86'],$clientraw['87'],$clientraw['88'],$clientraw['89']);
$datay = $y;

if ($speed_conv !=1) {
	array_walk($datay, "KtoV");
	}

//create timearray for the x-axis
$crhour = $clientraw[29];
$crmin = $clientraw[30];
$crhour = $crhour-1;
if ($hourmode == "24") {
    if ($crhour == -1) $crhour = 23;
    $hr_pad = "0";
  }
if ($hourmode == "12") {
    if ($crhour >= 13) $crhour = $crhour - 12;
    if ($crhour == -1) $crhour = 11;
    if ($crhour == 0) $crhour = 12;
    $hr_pad = " ";
  }
$crmin = $crmin+6;
if ($crmin >= 60) {
  $crmin = $crmin - 60;
  $crhour = $crhour + 1;
}
$x = array();
$x[0] = str_pad($crhour, 2, $hr_pad, STR_PAD_LEFT).":".str_pad($crmin, 2, "0", STR_PAD_LEFT);
for ($i = 1; $i < 10; $i++) {
	$crmin = $crmin+6;
	if ($crmin >= 60 ) {
		$crhour = $crhour+1;
		if ($crhour >23) $crhour = 0;
		$crmin = $crmin-60;
		}
	$x[$i] = str_pad($crhour, 2, $hr_pad, STR_PAD_LEFT).":".str_pad($crmin, 2, "0", STR_PAD_LEFT);
	}

$datax = $x;

// Create the graph. These two calls are always required
$graph = new Graph($xsize,$ysize,"auto",30);	
$graph->SetScale("textlin");
$graph->yaxis->scale->SetGrace(10);
$graph->SetMarginColor("$margincolour");

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->SetMargin($lm,$rm,$tm,$bm);

// Create a line plot
$lplot = new LinePlot($datay);

$lplot->SetWeight(2);
$lplot->SetColor("$speed_col");

$graph->Add($lplot);

// titles
$graph->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->title->Set("$txt_wind_sp $txt_60m ($speed_unit)");
$graph->title->SetColor("$textcolour");

//x-axis
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
$graph->yscale->SetAutoMin(0);
$graph->yaxis->HideTicks(true,true);
 
// Display the graph
$graph->Stroke();
?>

