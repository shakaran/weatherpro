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
$pageName	= 'wsDashForecast.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-07-27 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
$html_start     =  '<!-- forecast -->
<div class="blockDiv">'.PHP_EOL;
$html_end       = '</div>
<!--  end of forecast -->'.PHP_EOL;
#
$arrDashFct		= array();
$arrDashFct['metno']	= 'wsmetno3/wsDashMetno.php';
$arrDashFct['yahoo']	= 'forecasts/yahooForecast2.php';
$arrDashFct['wu']	= 'wuforecast/wsDashWU.php';
$arrDashFct['wxsim']	= 'wsWxsim/wsDashWxsim.php';
$arrDashFct['yrno']	= 'wsyrnofct/wsDashYrno.php';
$arrDashFct['noaa']	= 'usa/noaafct/noaa_dash_fct.php';
$arrDashFct['ec']	= 'canada/ec_print_dash_fct.php';
$arrDashFct['hwa']	= 'wsHwa/wsDashHwa.php';
$arrDashFct['yowindow']	= 'wsDashYowindow.php';

#
if (!isset($SITE['fctOrg']) || $SITE['fctOrg'] == 'yahoo')  {
        echo $html_start;
        $script	= $arrDashFct['yahoo'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        echo $html_end;
        return;
}
elseif ($SITE['fctOrg'] == 'metno') { 
        $script = $arrDashFct['metno'];  
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
   
        return;
}
elseif ($SITE['fctOrg'] == 'hwa')   { 
#	$hwaPageNr      = 54;
#	$hwaLink        = '<a href="index.php?p='. $hwaPageNr.'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/i_symbolWhite.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>'.PHP_EOL;
        echo $html_start;
        $script = $arrDashFct['hwa'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        echo $html_end;
        return;
}
elseif ($SITE['fctOrg'] == 'yowindow')   {
        if (isset ($wsDashYowindowLoaded)  && $wsDashYowindowLoaded == true) {return;}
        echo $html_start;
	echo '<h3 class="blockHead">'.langtransstr('YoWindow 7 day graphical forecast').'</h3>'.PHP_EOL;
	$script = $arrDashFct['yowindow'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        echo $html_end;
        return;
}
elseif ($SITE['fctOrg'] == 'wxsim')   {
        echo $html_start;
        $script =  $arrDashFct['wxsim'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        echo $html_end;
        return;
}
elseif ($SITE['fctOrg'] == 'wu')   {
        $script = $arrDashFct['wu'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        return;
}
elseif ($SITE['fctOrg'] == 'yrno')   {
        $script = $arrDashFct['yrno'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        return;
}
elseif ($SITE['fctOrg'] == 'noaa')   {
        $script = $arrDashFct['noaa'];
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        return;
}
elseif ($SITE['fctOrg'] == 'ec')   {
        $script = $arrDashFct['ec']; 
        ws_message (  '<!-- module wsDashForecast.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
        return;
}
echo $html_start.'<h3>&nbsp;&nbsp;&nbsp;program error  invalid forecast requested -'.$SITE['fctOrg'].'- !</h3>'.$html_end ;
?>