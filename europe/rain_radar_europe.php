<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;            # display source of script if requested so
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
$pageName	= 'rain-radar_europe.php';
$pageVersion	= '3.00 2015-04-30';
#		
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#----------------------------------------------------------------------
# 3.00 2015-04-30 release version 
#-----------------------------------Settings:---------------------------
#
$meRadarAvailable	= true; 	// do we want to display Meteox  radar
$brRadarAvailable	= true; 	// do we want to display Benelux buienradar
$hwaRainAvailable       = true;         // HWA radar

# available radars for europe
$imgMeteox      = 'http://www.meteox.com/images.aspx?jaar=-3&amp;voor=&amp;soort=exp&amp;c=&amp;n=&amp;tijdid='.time();

$imgBuienradar  = 'http://www.buienradar.nl/image/?type=forecast3hourszozw&amp;fn=buienradarnl-1x1-ani700-verwachting-3uur.gif';

$imgHWA         = 'http://www.hetweeractueel.nl/includes/custom/hydronetradar/?id='.$SITE['hwaXmlId'];
$imgHWA         = 'inc/hydronet.php';   // voor bruine kaart zonder rode stip
# 
$imgStyle	= 'style="width: 100%; vertical-align: bottom"';
#
$meLink['nl']   = '<a href="http://www.meteox.nl/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
$meLink['de']   = '<a href="http://www.meteox.de/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
$meLink['fr']   = '<a href="http://www.meteox.fr/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
$meLink['en']   = '<a href="http://www.meteox.co.uk/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
if (!isset ($meLink[$lang]) ) {$meLink[$lang] = $meLink['en'];}
#
$brLink['nl']   = '<a href="http://www.buienradar.nl/buienradar-3-uur-vooruit.aspx" target="_blank">';
$brLink['de']   = '<a href="http://www.meteox.de/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
$brLink['fr']   = '<a href="http://www.meteox.fr/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
$brLink['en']   = '<a href="http://www.meteox.co.uk/h.aspx?r=&amp;jaar=-3&amp;soort=exp" target="_blank">';
if (!isset ($brLink[$lang]) ) {$brLink[$lang] = $brLink['en'];}
#---------------------------End Of Settings:----------------------------
#
if (!isset ($meLink[$lang]) ) {$meLink[$lang] = $meLink['en'];}
if (!isset ($brLink[$lang]) ) {$brLink[$lang] = $brLink['en'];}

if ($SITE['hwaID'] == '') { $hwaRainAvailable = false; }	// HWA radar only when a member of HWA

$count          = 0;
if ($meRadarAvailable)  { $count++; }
if ($brRadarAvailable)  { $count++; }
if ($hwaRainAvailable)  { $count++; }
#
echo '<div class="blockDiv"><!-- page -->
<h3 class="blockHead">'.langtransstr('Precipitation forecasts').'</h3>'.PHP_EOL;
$start_tab  = $end_tab  = '';
if ($count > 1) {
        echo '<br /><div class="tabber" style="width: 100%; "><!-- tabber -->'.PHP_EOL;
        $start_tab      = '<div class="tabbertab" style="padding: 0;">'.PHP_EOL;
        $end_tab        = '</div>'.PHP_EOL;
}


if ($meRadarAvailable) {
        if ($count > 1) {
                echo $start_tab.'<h3 style="text-align: center;">Meteox -'.langtransstr('3 hours W-Europe').'</h3>'.PHP_EOL;
        }
        echo '<h4  class="blockHead" style="padding: 5px;">'.langtransstr('Click on image for the meteox site with a lot of extra information').'</h4>'.PHP_EOL;
	echo $meLink[$lang].'<img src="'.$imgMeteox.'" '.$imgStyle.' alt="Meteox -'.langtransstr('3 hours W-Europe').'"  /></a>'.PHP_EOL.$end_tab;
}
#
if ($brRadarAvailable) {
        if ($count > 1) {
                echo $start_tab. '<h3 style="text-align: center;">Buienradar - '.langtransstr('3 hours Benelux').'</h3>'.PHP_EOL;
        }
        echo '<h4  class="blockHead" style="padding: 5px;">'.langtransstr('Click on image for the buienradar site with a lot of extra information').'</h4>'.PHP_EOL;
        if ($lang == 'nl') { echo 
'<p style="width: 97%; margin: 10px;">Deze radarverwachting gaat 3 uur vooruit en is gebaseerd 
op 11 radarstations gecombineerd met actuele satellietbeelden. 
Met name de filtering op clutter of spookwaarnemingen is sterk verbeterd in deze radarverwachting. 
<br />Tevens houdt het door gebruik te maken van 9 buitenlandse radarstations rekening met de randen van de radar. 
Hierdoor worden buien, die bijvoorbeeld nu nog in de grensgebieden zitten van de Nederlandse radar, 
meegenomen in de verwachting. 
Dit biedt vooral als u verder dan 2 uur wilt kijken een goed beeld welke kant het qua regen op gaat.
<br /></p>'.PHP_EOL;
        }
	echo $brLink[$lang]. '<img src="'.$imgBuienradar.'" '.$imgStyle.' alt="'.langtransstr('3 hours Benelux').'"  /></a>'.PHP_EOL.$end_tab;
}
#
if ($hwaRainAvailable) { 
        if ($count > 1) {
                echo $start_tab. '<h3 style="text-align: center;">HWA - '. langtransstr('3 hours Benelux').'</h3>'.PHP_EOL;        
        }
        echo '<h4  class="blockHead" style="padding: 5px;">Deze radar geeft de neerslag over de afgelopen uren dus geen verwachting.</h4>'.PHP_EOL;
        echo '<div style="width: 486px; margin: 0 auto;"><!-- hwa -->
        <div style="width: 100%;"><iframe src="'.$imgHWA.'"  style="border: none; overflow: hidden; width: 486px; height: 576px;"></iframe></div>'.PHP_EOL;
        if ($lang == 'nl') { echo 
'<p style="padding: 5px;">
Bovenstaande neerslagradar is tot stand gekomen met behulp van de HydroNET en Dataprofeet software. 
Via een samenwerking tussen Hetweeractueel.nl, Witteveen+Bos en HydroNet wordt deze radar beschikbaar gesteld.
<br />
In de toekomst wordt dit radarbeeld gecalibreerd op basis van metingen van honderden deelnemende weerstations via Hetweeractueel.nl waar '.$SITE['organ'].' ook deel vanuit maakt. 
</p>';
        }
        echo '<br /><p style="padding: 5px;" ><small><b>Er kunnen geen rechten worden ontleend aan deze gegevens.</b></small></p>';
        echo '</div><!-- hwa -->'.PHP_EOL;
        echo $end_tab;
}
#
if ($count > 1) {
        echo '</div><!-- tabber -->
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
echo '</div><!-- page -->'.PHP_EOL;
 