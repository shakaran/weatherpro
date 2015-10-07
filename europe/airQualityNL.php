<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'airQualityNL.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-04-02';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.01 2015-04-02 release 2.7 version
# -------------------------------------------------------------------------------------------------
$hours  = 1.5*60*60;            // na hoeveel tijd is het uurkaartje er
$path   = 'http://www.lml.rivm.nl/kaart/images/';
$ext    = '.png';
$image1 = '_o3';                // http://www.lml.rivm.nl/kaart/images/2014071123_o3.png
$image2 = '_pm10';
$image3 = '_no2';
$date   = date ('YmdH',time()-$hours);
$link1  = $path.$date.$image1.$ext;
$link2  = $path.$date.$image2.$ext;
$link3  = $path.$date.$image3.$ext;
?>
<div class="blockDiv">

<h3 class="blockHead"><?php echo langtransstr('Actual Air quality').' '.
 langtransstr ('Provided by'); ?> <a href="http://www.lml.rivm.nl/index.html" target="_blank">RIVM NL</a></h3> 
<br />
<table class = "genericTable">
<tr><th>&nbsp;</th>
<th class="blockHead">O<sub>3</sub></th><th>&nbsp;</th>
<th class="blockHead">PM10</th><th>&nbsp;</th>
<th class="blockHead">NO<sub>2</sub></th><th>&nbsp;</th>
</tr>
<tr>
<td colspan="7">&nbsp;</td>
</tr>
<tr><td>&nbsp;</td>
<td	style="width: 32%">
<a  href="<?php echo $link1; ?>" rel="lightbox" title="Click image to enlarge">
<img src="<?php echo $link1; ?>" alt="actuele situatie o3" style="width: 100%; cursor: pointer;"/></a>
</td>
<td>&nbsp;</td>
<td	style="width: 32%">
<a  href="<?php echo $link2; ?>" rel="lightbox" title="Click image to enlarge">
<img src="<?php echo $link2; ?>" alt="actuele situatie o3" style="width: 100%; cursor: pointer;"/></a>
</td>
<td>&nbsp;</td>
<td	style="width: 32%">
<a  href="<?php echo $link3; ?>" rel="lightbox" title="Click image to enlarge">
<img src="<?php echo $link3; ?>" alt="actuele situatie o3" style="width: 100%; cursor: pointer;"/></a>
</td>
<td>&nbsp;</td>
</tr>
</table>
<h3 class="blockHead"><?php echo langtransstr('Click on a map to get a pop-up with a larger image')?></h3>
</div>
<div class="blockDiv">
<h3 class="blockHead"><?php echo langtransstr('Legenda')?></h3>
<table class = "genericTable">
<tr>
<td style="text-align: left;"><img src="http://www.lml.rivm.nl/images/legenda.png" alt="legenda" style="width: 100%; " /></td>
<td >&nbsp;<img src="./img/rivm-logo.png" alt="" style="width: 100%; " /></td>
</tr>
</table>
</div>
<!--  </div>  -->
