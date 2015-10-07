<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'rain_radar_europe_small.php';		
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.02 2015-05-03';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 3.02 2015-05-03 release version separate scripts / region

#-------------------------------------------------------------------------------------------------
$meRadarAvailable	= true; 		// do we want to display Meteox european radar
$brRadarAvailable	= true; 		// do we want to display Benelux buienradar
$hwaRainAvailable       = false;
if ($SITE['hwaID']) { $hwaRainAvailable = true; }// HWA radar should only be displayed by hwa members
# europe
$imgMeteox      = 'http://www.meteox.com/images.aspx?jaar=-3&amp;voor=&amp;soort=exp&amp;c=&amp;n=&amp;tijdid='.time();
$imgBuienradar  = 'http://www.buienradar.nl/image/?type=forecast3hourszozw&amp;fn=buienradarnl-1x1-ani700-verwachting-3uur.gif';
$imgHWA         = 'inc/hydronet.php';   // voor bruine kaart zonder rode stip, aanpasbaar qua grootte
#
$imgStyle       = ' style="border: none; width: 222px; margin: 0 auto;"';
$pageTitle      = langtransstr('Precipitation radars'); 
#
$countRain=0;
if ($meRadarAvailable)  {$countRain++;}
if ($brRadarAvailable)  {$countRain++;}
if ($hwaRainAvailable)  {$countRain++;}
echo '<!-- countrain = '.$countRain.' -->'.PHP_EOL;
if ($countRain == 3)  { 
        $td_width       = '32%';
        $imgStyle       = ' style="border: none; width: 222px; margin: 0 auto;"';
}
 else { $td_width       = '48%';
        $imgStyle       = ' style="border: none; width: 320px; margin: 0 auto;"';
}
$link_to_page           = $SITE['pages']['wsPrecipRadar'].'&amp;lang='.$lang.$extraP.$skiptopText;
$link_to_page_text      = langtransstr('more information');
$title_text             = langtransstr('Click image to enlarge');

echo '<!-- page --><div class="blockDiv">
<h3 class="blockHead">'.$pageTitle.'&nbsp;
<a href="'.$link_to_page.'"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle;" alt="'.$link_to_page_text.'" title="'.$link_to_page_text.'"></a>
</h3>
<table class="genericTable">
<tbody>
<tr style="margin: 0px; height: 15px;">';

$tdExtra = '';
$now = time();
if ($hwaRainAvailable) {
	echo $tdExtra.'<td  style="width: '.$td_width.';"><h4 style="margin: 0px;">'.langtransstr('HWA').'</h4></td>'.PHP_EOL;
	$tdExtra = '<td  style="width: 2%;">&nbsp;</td>'.PHP_EOL;
}
if ($meRadarAvailable) {
	echo $tdExtra.'<td  style="width: '.$td_width.';"><h4 style="margin: 0px;">'.langtransstr('Meteox').'</h4></td>'.PHP_EOL;
	$tdExtra = '<td  style="width: 2%;">&nbsp;</td>'.PHP_EOL;	
}
if ($brRadarAvailable) {	
	echo $tdExtra.'<td  style="width: '.$td_width.';"><h4 style="margin: 0px;">'.langtransstr('Buienradar').'</h4></td>'.PHP_EOL;
	$tdExtra = '<td  style="width: 2%;">&nbsp;</td>'.PHP_EOL;
}
echo '</tr>
<tr>'.PHP_EOL;
$tdExtra = '';
if ($hwaRainAvailable) {
	echo $tdExtra.'<td><iframe src="'.$imgHWA.'?width=218"  style="border: none; overflow: hidden; width: 222px; height: 263px; margin: 0 auto;"></iframe></td>'.PHP_EOL;
	$tdExtra = '<td  style="width: 2%;">&nbsp;</td>'.PHP_EOL;	
}
if ($meRadarAvailable) {
	echo $tdExtra.'<td><a  href="'.$imgMeteox.'"  rel="lightbox" title="'.$title_text.'"><img src="'.$imgMeteox.'" '.$imgStyle.' alt="  "  /></a></td>'.PHP_EOL;
	$tdExtra = '<td  style="width: 2%;">&nbsp;</td>'.PHP_EOL;	
}
if ($brRadarAvailable) {	
	echo $tdExtra.'<td><a  href="'.$imgBuienradar.'"  rel="lightbox" title="'.$title_text.'"><img src="'.$imgBuienradar.'"  '.$imgStyle.' alt="  "  /></a></td>'.PHP_EOL; 
	$tdExtra = '<td  style="width: 2%;">&nbsp;</td>'.PHP_EOL;	
}
echo '</tr>
</tbody>
</table>
</div>'.PHP_EOL;
  