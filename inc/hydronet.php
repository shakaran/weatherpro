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
$SITE   = array();
$pageName	= 'hydronet.php';
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathstring ='<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-12 release version
# -------------------------------------------------------------------------------------------------
if (isset($_REQUEST['width']) ){
	$width	= (int) $_REQUEST['width'];
	$height = (int) ($width / 486 * 576);
	$styleHydro = 'style="width: '.$width.'px; height: '.$height.'px;"';
	$pathstring .= '<!-- width changed from 486 to '.$width.'. height from 576 to '.$height.' -->'.PHP_EOL;
}  else {$styleHydro = '';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>
			HydroNET / HWA Neerslagradar
		</title>
<style>
*
{
    margin: 0px;
    padding: 0px;
}

.Map
{
    position: absolute;
    z-index: 1;
}

.AnimatedGif
{
    position: absolute;
    z-index: 2;
}

.AnimatedGif img
{
    opacity: 0.75;
}

.HwaLogo 
{ 
    background-color: #FFFFFF;
    left: 304px;
    opacity: 0.75;
    padding: 6px;
    position: relative;
    top: 538px;
    width: 50px;
    z-index: 4;
} 
 
.WbLogo 
{ 
    background-color: #FFFFFF;
    left: 212px;
    opacity: 0.75;
    padding: 6px 6px 7px;
    position: relative;
    top: 505px;
    width: 75px;
    z-index: 5;
} 
</style>
	</head>
	<body>
<?php echo $pathstring.PHP_EOL; ?>
		<div id="Wrapper" class="Wrapper">
			<div id="Map" class="Map">
				<img src="../img/basemap.jpg" alt="Map" <?php echo $styleHydro; ?> />   
			</div>
			<div id="AnimatedGif" class="AnimatedGif">
				<img src="http://apps.hydronet.nl/precipitation/nl/images/hydronet_radaranimation.gif?<?php echo time(); ?>" 
				alt="Animation" <?php echo $styleHydro; ?> />
			</div>
<?php if ($styleHydro == '') { ?>
			<div class="HwaLogo"> 
				<img src="http://apps.hydronet.nl/precipitation/nl/images/hwa_logo.png" alt="HwaLogo" /> 
			</div> 
			<div class="WbLogo"> 
				<img src="http://apps.hydronet.nl/precipitation/nl/images/wb_logo.gif" alt="WbLogo" /> 
			</div>
<?php } ?>
		</div>
	</body>
</html>

