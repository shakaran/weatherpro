<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws-wind_map.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-10-08';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-10-08 release version
# ----------------------------------------------------------------------
# settings:
$page_title     = langtransstr('Wind map');
$legend         = langtransstr('Windspeed').' - ';
$wind_uom       = str_replace ('/', '', trim($SITE['uomWind']) );        // =' km/h', =' kts', =' m/s', =' mph'
$region         = $SITE['region'];
switch ($region) {
        case 'europe':
                $url    = 'http://earth.nullschool.net/#current/wind/surface/level/orthographic=-357.44,46.58,1109';
        break;
        case 'america':
                $url    = 'http://earth.nullschool.net/#current/wind/surface/level/orthographic=-106.47,52.53,1024';
        break;
        case 'canada':
                $url    = 'http://earth.nullschool.net/#current/wind/surface/level/orthographic=-114.71,56.95,990';
        break;
        default:
                $url    = 'http://earth.nullschool.net/#current/wind/surface/level/orthographic=-216.29,-25.94,679';
}
#$url            = '';
#
switch ($wind_uom) {
        case 'mph':
                $table_cnt      = 9;     
                $table_dif      = 25;           // 25 * 11 = 225 mph
                $legend         = $legend.'mi/h';
    #            echo '<!-- $SITE["uomWind"] = '.$SITE['uomWind'].' - windspeed in xxx - cnt = '.$table_cnt.' -  dif = '.$table_dif.' -->'.PHP_EOL;
        break;
        case 'ms':
                $table_cnt      = 10;     
                $table_dif      = 10;           // 10 * 10 = 100 m/s
                $legend         = $legend.'ms';
        break;       
        case 'kts':
                $table_cnt      = 13;     
                $table_dif      = 15;           // 15 * 13 = 195 kts
                $legend         = $legend.'kts';
        break;       
        default:
                $table_cnt      = 12;
                $table_dif      = 30;           // 30 * 12 = 360 km/h
                $legend         = $legend.'km/h';
}
# calculate nice looking 


#
?>
<!-- wind map -->
<div class="blockDiv"><!-- leave this opening div as it is needed  a nice page layout -->
<h3 class="blockHead"><?php echo $page_title; ?></h3>

<iframe src="<?php echo $url; ?>" style ="border: none; width:100%; height: 600px; margin: 0px; padding: 0px; vertical-align: bottom; "></iframe>
<table class="genericTable" >

<?php
# style="border: none; width: 100%; height: 800px; margin: 0px; padding: 0px; vertical-align: bottom;

echo '<tr><td colspan ="'.$table_cnt.'" style="text-align: center"><h3>'.$legend.'<h3></td></tr>'.PHP_EOL;
echo '<tr><td colspan ="'.$table_cnt.'"><img style="width: 100%;" src="img/windscale.png"/></td></tr>'.PHP_EOL;
echo '<tr>';
for ($i = 0; $i < $table_cnt; $i++) {
        echo '<td style="width: '.(100/$table_cnt).'%; text-align: left; ">| '.$i* $table_dif;
        if ($i == $table_cnt-1 ) {echo '<span style="float: right;"> '.$table_cnt*$table_dif.' |</span>'; }
        echo '</td>';
}
echo '</tr>'.PHP_EOL;
?>

</table>
<h3 class="blockHead"><small>
  Copyright 2014 - <a href="http://earth.nullschool.net/" target="_blank">Earth Nullschool</a></small>
</h3>
</div><!-- leave this closing div as it is needed  for the opennig div -->


