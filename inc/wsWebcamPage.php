<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsWebcamPage.php';
$pageVersion	= '3.20 2015-07-13';
#-----------------------------------------------------------------------
# 3.20 2015-07-13 release 2.8 version
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------Settings    --------------------------------
$width          = '100%';               // default takes all width
#$width          = '640px';              // use  smaller width when pictures are to big to be nice
$night_override = false;		// override for use night image when dark
$page_title     = 'Our Webcam Page';
#-----------------------------------------------------------------------
#  Do not modify below this point
#----------------------------------------------------------------------
$page_title     = langtransstr($page_title);
#----------------------------------------------------------------------
$webcamNight	= '';			// we check if we need a night image when dark
if (!$night_override && $dayNight == 'nighttime' && $SITE['webcamNight'] && $SITE['webcamImgNight'] <> '') {
	$webcamNight	= $SITE['webcamImgNight'];
}
#
$SITE['webcam_1']	= $SITE['webcam'];
$n1			= 0;
$div_start      = $div_end      = '';
if ($width <> '100%') { // use smaller images with own border inside page border
        $div_start      = '<div class="blockDiv" style="width: '.$width.'; background-color: grey; margin: 10px auto;">'.PHP_EOL;
        $div_end        = '</div>'.PHP_EOL;
        echo '
<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'</h3>'.PHP_EOL;       
} 
else {	echo '
<div class="blockDiv" style="background-color: grey;">'.PHP_EOL;
}
for ($n1 = 1; $n1 <= 4 ; $n1++) {
	if (!isset ($SITE['webcam_'.$n1]) || $SITE['webcam_'.$n1] == false) {continue;}
        echo $div_start;
        if ($SITE['webcamName_'.$n1] <> '') {
        	$name	= $SITE['webcamName_'.$n1];
        	echo '<h3 class="blockHead">'.$name.'</h3>'.PHP_EOL; 	
        } else {$name = ' ';}
        if ($webcamNight <> '') { $SITE['webcamImg_'.$n1] = $webcamNight;}
        echo '<img src="'.$SITE['webcamImg_'.$n1] .'" alt = "'.$name.'" style="width: 100%; vertical-align: bottom;"/>'.PHP_EOL;
        echo $div_end; 
}
?>
</div>