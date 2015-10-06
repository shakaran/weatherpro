<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'hwakaartjes.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-26';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-26 release version
# -------------------------------------------------------------------------------------------------
# To do: 
#   
#---------------------------------------------------------------------------
?>
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('Weather maps provided by'); ?> <a href="http://www.hetweeractueel.nl/" target="_blank">Het Weer Actueel</a></h3> 
<p style="margin: 10px 5px;"><?php langtrans('The map with the current temperatures is generated  every 10 minutes from the current data from all weather stations, provided that the data is less than 1 hour old.'); ?> 
</p>
<p style="margin: 10px 5px;"><?php langtrans('The second map shows the precipitation that has fallen today until the time stated on the map.'); ?> 
</p>
<p style="margin: 10px 5px;"><?php langtrans('The map with the actual  windspeed (10 minute average) is constructed 2 times per hour from the current data from all weather stations, provided that the data is less than 1 hour old.'); ?>
</p>
<p style="margin: 10px 5px;"><?php langtrans('The South-East of Belgium remains light gray, as no weather stations are nearby.'); ?>
</p>
<table class = "genericTable">
<tr>
<th>&nbsp;</th>
<th class="blockHead"><?php langtrans('Temperature')?></th><th>&nbsp;</th>
<th class="blockHead"><?php langtrans('Rain')?></th><th>&nbsp;</th>
<th class="blockHead"><?php langtrans('Wind')?></th><th>&nbsp;</th>
</tr>
<tr>
<td>&nbsp;</td>
<td	style="width: 32%">
<a  href="http://www.hetweeractueel.nl/includes/custom/tempmap.png?refresh=<?php echo time(); ?>" rel="lightbox" title="Click image to enlarge">
<img src="http://www.hetweeractueel.nl/includes/custom/tempmap.png?refresh=<?php echo time(); ?>" alt="Actuele temperatuur kaart" style="width: 100%; cursor: pointer;"/>
</a></td>
<td>&nbsp;</td>
<td	style="width: 32%">
<a  href="http://www.hetweeractueel.nl/includes/custom/rainimages/rainmapcurrent.png?refresh=<?php echo time(); ?>" rel="lightbox" title="Click image to enlarge">
<img src="http://www.hetweeractueel.nl/includes/custom/rainimages/rainmapcurrent.png?refresh=<?php echo time(); ?>" 
alt="Actuele neerslag kaart" style="width: 100%; cursor: pointer;"/>
</a></td>
<td>&nbsp;</td>
<td	style="width: 32%">
<a  href="http://www.hetweeractueel.nl/includes/custom/windmap.png?refresh=<?php echo time(); ?>" rel="lightbox" title="Click image to enlarge">
<img src="http://www.hetweeractueel.nl/includes/custom/windmap.png?refresh=<?php echo time(); ?>" alt="Actuele wind kaart" style="width: 100%; cursor: pointer;"/>
</a></td>
<td>&nbsp;</td>
</tr>
</table>
<h3 class="blockHead"><?php langtrans('Click on a map to get a pop-up with a larger image')?></h3>
</div>
