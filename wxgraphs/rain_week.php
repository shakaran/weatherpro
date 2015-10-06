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

$y=($clientrawextra['484']+$clientrawextra['485']+$clientrawextra['486']+
$clientrawextra['487']+$clientrawextra['488']+$clientrawextra['489']+$clientrawextra['490']);
$y = $y/10;
$y = array($y);
$datay=$y;

if ($rain_conv != 1) {
	array_walk($datay, "MtoI");
	}

// Setup the graph.
$graph = new Graph($xsize1,$ysize,"auto");
$graph->SetScale("textlin");
$graph->yscale->SetGrace(10);
$graph->SetMargin($lm1,$rm1,$tm1,$bm1);
$graph->SetShadow();
$graph->SetMarginColor("$margincolour");

//Setup Mian Title
$graph->title->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->title->Set("$txt_7day");
$graph->title->SetColor("$textcolour");

//Setup x axis
$graph->xaxis->SetColor("$xtextcolour");
$graph->xaxis->HideLabels(true); 
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,8); 
$graph->xaxis->title->Set("$txt_rain ($rain_unit)");
$graph->xaxis->title->SetColor("$xtextcolour");

// Setup y axis
$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->yaxis->SetColor("$ytextcolour");
$graph->yaxis->SetLabelFormat("$rain_format");
$graph->yaxis->HideTicks(true,true);

// Create the bar pot
$bplot = new BarPlot($datay);
$bplot->SetWidth(0.7);
$bplot->SetAlign("center");
$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
$bplot->value->HideZero();
$bplot->value->SetColor("black");
$bplot->SetValuePos('top');
$bplot->value->Show();
$bplot->value->SetFormat("$rain_format");
$bplot->SetFillGradient("$rain_col","#EEEEEE",GRAD_LEFT_REFLECTION);
$bplot->SetColor("$rain_col");

//Add plot
$graph->Add($bplot);

// Finally send the graph to the browser
$graph->Stroke();
?>