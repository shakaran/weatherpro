<?php
include_once('WUG-settings.php');

// iframe height
$iFramedH = '633'; // pixels; Iframe height (optional value, default '633') usable in special cases, requests (smaller resize jumps/steps, etc...). 

// graphs directory
$wugraphdir = './wxwugraphs/'; // with trailing slashes

// Get value for cookie name
/*
$thispage = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
$igraph = substr($thispage, 0, 7);
$cookie_i = substr($thispage, 5, 1); // 'm'(month graphs), 'y'(year graphs) or 'd' (day graphs)
*/

$igraph = $_GET['pg'];
$itheme =  !empty($_COOKIE['wu_graph_theme']) ? '&theme='.$_COOKIE['wu_graph_theme'] : '';

$iframepage = $wugraphdir . $igraph;

$actDay = date('j');
$actMnth = date('n');
$actYear = date('Y');

//get cookie for link (set cookie in WUGinc-day/month.php)
//year
$cookieY = (!empty($_COOKIE['wu_graph_y']) ? $_COOKIE['wu_graph_y'] : $actYear);
//month
$cookieM = (!empty($_COOKIE['wu_graph_m']) ? $_COOKIE['wu_graph_m'] : $actMnth);
// day
$cookieD = (!empty($_COOKIE['wu_graph_d']) ? $_COOKIE['wu_graph_d'] : $actDay);

if (substr($igraph,0,-1) == 'graphd') {
  $idateLink = 'd='.$cookieD.'&m='.$cookieM.'&y='.$cookieY.'&';
} elseif (substr($igraph,0,-1) == 'graphm') {
  $idateLink = '&m='.$cookieM.'&y='.$cookieY.'&';
} elseif (substr($igraph,0,-1) == 'graphy') {
  $idateLink = '&y='.$cookieY.'&';
} else {
  $idateLink = '';
}

echo '
<div class="gload" style="color:'.$wugfontColor.'; width:1px; margin:0px auto; position:relative; text-align:center; display: none; z-index:50; height: 0px;"><div style="position:absolute; top:0px; margin:120px 0 0 -65px; /*font-weight:bold;*/ font-size:130%;">'.$Tloading.'<br /><br /><img src="'.$preImg.'" width="128" height="128"></div></div>
';

echo '
<iframe name="WUGiframe" class="WUGiframe" src="'.$iframepage.'a.php?'.$idateLink.'i=1'.$itheme.'" width="100%" scrolling="no" frameborder="0" style="height:0px; background-color:'.$pgBGC.';"></iframe>
<div class="njump" style="height:'.$iFramedH.'px;"></div>
';

?>                                                                                                                                                                              