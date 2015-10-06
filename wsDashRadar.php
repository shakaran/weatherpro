<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'wsDashRadar.php';	
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-07-27 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
if (!isset ($ws['img_lightning']) ) {
	ws_message (  '<!-- module wsDashRadar.php ('.__LINE__.'): loading _my_scripts/set_links.php -->');
        include '_my_scripts/set_links.php';
}
#-----------------------------------------------------------------------
# text for headers
$htxt_rain      = langtransstr('Precipitation').'&nbsp;';
$htxt_thunder	= langtransstr('Lightning').'&nbsp;';
$htxt_cloud	= langtransstr('Clouds').'&nbsp;';
# links to full page
if (isset ($SITE['pages']['wsPrecipRadar']) )  {
        $link_rain	= '<a href="'.$SITE['pages']['wsPrecipRadar'].'&amp;lang='.$lang.$extraP.$skiptopText.'">'.
'<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="information" title="more" /></a>';      
} 
else {  $link_rain	= '<a  href="'.$ws['img_rain'].'"  rel="lightbox" title="'.$title_text.'">'.
'<img src="./img/i_symbol.png" alt=" " style="margin: 1px; vertical-align: middle; width: 15px;" /></a>';
}
if (isset ($SITE['pages']['thunderRadar']) )  {
        $link_thunder	= '<a href="'.$SITE['pages']['thunderRadar'].'&amp;lang='.$lang.$extraP.$skiptopText.'">'.
'<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="information" title="more" /></a>';
} 
else {  $link_thunder	= '<a  href="'.$ws['img_lightning'].'"  rel="lightbox" title="'.$title_text.'">'.
'<img src="./img/i_symbol.png" alt=" " style="margin: 1px; vertical-align: middle; width: 15px;" /></a>';
}
if (isset ($SITE['pages']['cloudRadarv3']) )  {
        $link_cloud	= '<a href="'.$SITE['pages']['cloudRadarv3'].'&amp;lang='.$lang.$extraP.$skiptopText.'">'.
'<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="information" title="more" /></a>';
}
else {  $link_cloud	= '<a  href="'.$ws['img_clouds'].'"  rel="lightbox" title="'.$title_text.'">'.
'<img src="./img/i_symbol.png" alt=" " style="margin: 1px; vertical-align: middle; width: 15px;" /></a>';
}
#
$imgStyle	= 'border: none; max-width: 100%; max-height: 250px; margin: 0 auto;'; 
$title_text     = langtransstr('Click image to enlarge');
#
# <td><a  href="'.$imgGC_region.'"  rel="lightbox" title="'.$title_text.'"><img src="'.$imgGC_region.'"  alt="'.$descGC_region.'"   style="'.$image_style.'" /></a></td>

# <a  href="http://www.hetweeractueel.nl/includes/custom/tempmap.png?refresh=<?php echo time(); ? >" rel="lightbox" title="Click image to enlarge">


echo '<!-- wsDashRadar -->
<div class="blockDiv">
<table class="genericTable">
<tr class="blockHead" style="">
<td  style="width: 1%;">&nbsp;</td>
<td style="width: 32%;"><h4 style="margin: 0px;">'.$htxt_rain.$link_rain.'</h4></td>
<td  style="width: 1%;">&nbsp;</td>	
<td style="width: 32%;"><h4 style="margin: 0px;">'.$htxt_thunder.$link_thunder.'</h4></td>
<td style="width: 1%;">&nbsp;</td>
<td style="width: 32%;"><h4 style="margin: 0px;">'.$htxt_cloud.$link_cloud.'</h4></td>
<td  style="width: 1%;">&nbsp;</td>
</tr>
<tr style="height: 5px;"><td style="height: 5px;" colspan="7"></td></tr>
<tr>
<td>&nbsp;</td>
<td><a href="'.$ws['img_rain'].         '" rel="lightbox" title="'.$title_text.'"><img src="'.$ws['img_rain']           .'" style="'.$imgStyle.'" alt="'.$htxt_rain.' radar"  /></a></td>
<td>&nbsp;</td>	
<td><a href="'.$ws['img_lightning'].    '" rel="lightbox" title="'.$title_text.'"><img src="'.$ws['img_lightning']      .'" style="'.$imgStyle.'" alt="'.$htxt_thunder.' radar"  /></a></td>
<td>&nbsp;</td>
<td><a href="'.$ws['img_clouds'].       '" rel="lightbox" title="'.$title_text.'"><img src="'.$ws['img_clouds']         .'" style="'.$imgStyle.'" alt="'.$htxt_cloud.' radar"  /></a></td>
<td>&nbsp;</td>
</tr>
</table>
</div>
<!-- end of wsDashRadar -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
