<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsCookie.php';
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
#-------------------------------------------------------------------------------
if (isset($_SESSION['lang'])){			
	$s      =trim(strtolower($_SESSION['lang']));
	if (isset($SITE['installedLanguages'][$s]))	{ $lang =  $s;}
}elseif (isset($_REQUEST['lang'])){	
	$s      =trim(strtolower($_REQUEST['lang']));
	if (isset($SITE['installedLanguages'][$s]))	{ $lang = $_SESSION['lang'] = $s;}
}
# now save for further reference
if (isset ($lang) ) {$SITE['lang']= $lang;}
#
$cookieName	= $SITE['cookieName'];
#
$cookie 	= $cookieName."allowed";
$cookieAllowed	= false;
if (isset($_POST['acceptCookie']) ){
	$cookieAllowed	= true;
}
#
if ($SITE['userChangeDebug']){
	$cookie = $cookieName."debug";
	if (isset ($_COOKIE[$cookie])) {				// first check  cookie
		$s=trim(strtoupper($_COOKIE[$cookie]));
		if ($s == 'Y')		{ $wsDebug	= true; } 
		elseif ($s == 'N')	{ $wsDebug	= false; }
	}
	if (isset($_SESSION['debug'])) {				//  than check session settings
		$s=trim(strtoupper($_SESSION['debug']));
		if ($s == 'Y')		{ $wsDebug	= true; } 
		elseif ($s == 'N')	{ $wsDebug	= false; }
	}
	if (isset($_REQUEST['debug'])) {				// than check this request settings
		$s=trim(strtoupper($_REQUEST['debug']));
		if ($s == 'Y')		{ $wsDebug	= true; } 
		elseif ($s == 'N')	{ $wsDebug	= false; }
	}
	if ($wsDebug == true) {
		$pathString.= '<!-- module wsCookie.php ('.__LINE__.'): debug is switched on by user request -->'.PHP_EOL;
		ini_set('display_errors', 'On'); 
		error_reporting(E_ALL);	
	}
	$SITE['wsDebug']		= $wsDebug;
	$_SESSION['debug']		= $wsDebug;
	wsSetcookie($cookie, $wsDebug, time()+60*60); 	// cookie expires in 1 hour
}
# ------ Now we check which other user settings are in effect, first the language --------
$lang	= $SITE['lang'];							// default language,
if ($SITE['userChangeLang']) {
	$cookie = $cookieName."lang";
	if (isset ($_COOKIE[$cookie])) {				// first check  cookie
		$s=trim(strtolower($_COOKIE[$cookie]));
		if (isset($SITE['installedLanguages'][$s]))	{ 
			$lang = $s; 
		}
	}
	if (isset($_SESSION['lang'])){					//  than check session settings
		$s=trim(strtolower($_SESSION['lang']));
		if (isset($SITE['installedLanguages'][$s]))	{ 
			$lang = $s; 
		}
	}     
	if (isset($_REQUEST['lang'])){					// than check this request settings
		$s=trim(strtolower($_REQUEST['lang']));
		if (isset($SITE['installedLanguages'][$s]))	{ 
			$lang = $s; 
		}
	}
	if (isset($_REQUEST['lang2'])){					// than check this request settings
		$s=trim(strtolower($_REQUEST['lang2']));
		if (isset($SITE['installedLanguages'][$s]))	{ 
			$lang = $s; 
		}
	}
	# now save for further reference
	$SITE['lang']			= $lang;
	$_SESSION['lang']		= $lang;
	wsSetcookie($cookie, $lang, time()+60*60*24*30 ); // cookie expires in 30 days
}
# ---------- now we check if the user want to put the menu  on a not default place -------
$menu	= $SITE['menuPlace'] ;				// default menu place, 
if ($SITE['userChangeMenu']) {
	$cookie = $cookieName."menu";
	if (isset ($_COOKIE[$cookie])) {			// first check  cookie
		$s=trim(strtoupper($_COOKIE[$cookie]));
		if ($s == 'H' || $s == 'V')	{ 
			$menu = $s; 
		}
	}
	if (isset($_SESSION['menu'])){				//  than check session settings
		$s=trim(strtoupper($_SESSION['menu']));
		if ($s == 'H' || $s == 'V')	{ 
			$menu = $s; 
		}
	}     
	if (isset ($_REQUEST['menu'])) {
		$s=trim(strtoupper($_REQUEST['menu']));
		if ($s == 'H' || $s == 'V')	{ 
			$menu = $s; 
		}
	}
	$SITE['menuPlace'] 	= $menu;
	$_SESSION['menu']	= $menu;
	wsSetcookie($cookie, $menu, time()+60*60*24*30 ); // cookie expires in 30 days
}
# ------------- now we check if the user wants to get rid of mood switches ---------------
if ($SITE['userChangeColors']) {
	$color 	= $SITE['colorNumber'];
	$cookie = $cookieName."mood";
	if (isset ($_COOKIE[$cookie])) {			// first check  cookie
		$s=intval(trim($_COOKIE[$cookie]));
		if ($s >= '0' && $s <= count($SITE['colorStyles']))	{ 
			$color = $s; 
		}
	}
	if (isset($_SESSION['mood'])){				//  than check session settings
		$s=trim($_SESSION['mood']);
		if ($s >= '0' && $s <= count($SITE['colorStyles']))	{ 
			$color = $s; 
		}
	}
	if (isset ($_REQUEST['mood'])) {
		$s=trim($_REQUEST['mood']);
		if ($s >= '0' && $s <= count($SITE['colorStyles']))	{ 
			$color = $s; 
		}
	}
	$_SESSION['mood']	= $color;
	wsSetcookie($cookie, $color, time()+60*60*24*30 ); // cookie expires in 30 days
	$SITE['colorNumber'] = $color;
}

if (!isset ($SITE['userChangeHeader']) ){$SITE['userChangeHeader'] = true;}
if ($SITE['userChangeHeader'])	{	
	$hdrSelect = $SITE['header'];
	$cookie = $cookieName."hdrSelect";
	if (isset ($_COOKIE[$cookie])) {				// first check  cookie
		$s=trim($_COOKIE[$cookie]);
		if ($s == '1' || $s == '2' || $s == '3')	{ 
			$hdrSelect = $s; 
		}
	}
	if (isset($_SESSION['hdrSelect'])){				//  than check session settings
		$s=trim($_SESSION['hdrSelect']);
		if ($s == '1' || $s == '2' || $s == '3')	{ 
			$hdrSelect = $s; 
		}
	}
	if (isset ($_REQUEST['hdrSelect'])) {
		$s=trim($_REQUEST['hdrSelect']);
		if ($s == '1' || $s == '2' || $s == '3')	{  
			$hdrSelect = $s; 
		}
	}
	$SITE['header']	= $hdrSelect;
	$_SESSION['hdrSelect']	= $hdrSelect;
	wsSetcookie($cookie, $hdrSelect, time()+60*60*24*30 ); // cookie expires in 30 days	
}
# ----------------------- default page normally index.php page  -----------------------------------
$choice	= $SITE['noChoice'] ;				// default page to start when user visits first time
if ($SITE['userChangeChoice']) {
	$arrAllowed=array('wsStartPage','wsPrecipRadar','gaugePage','wsForecast');
	$cookie = $cookieName."choice";
	if (isset ($_COOKIE[$cookie]) ) {			// first check  cookie
		$select	=  trim($_COOKIE[$cookie]);
		if (in_array ($select,$arrAllowed) ) {
			$choice = $select;
		}
	}
	if (isset($_SESSION['choice'])){			//  than check session settings
		$select	=  trim($_SESSION['choice']) ;
		if (in_array ($select,$arrAllowed)  ){
			$choice = $select;
		}
	}
	if (isset ($_REQUEST['choice'])) {
		$select	=  trim($_REQUEST['choice']);
		if (in_array ($select,$arrAllowed)   ){
			$choice = $select;
		}
	}
	$SITE['noChoice'] = $choice;
	$_SESSION['choice']	= $choice;
	wsSetcookie($cookie, $choice, time()+60*60*24*30 ); // cookie expires in 30 days
}
#---------------------------   UOM  now we change Units Of Measurement------------------------------
if ($SITE['userChangeUOM']) {
	$cookie = $cookieName."uom";
	$temp='x';$baro='x';$wind='x';$rain='x';
	if (isset ($_COOKIE[$cookie])) {
		$uom =  trim($_COOKIE[$cookie]);
		list($temp, $baro, $wind, $rain) = explode ('|',$uom);		
	}
	if (isset($_SESSION['temp']) && $_SESSION['temp'] <> 'x'){ $temp= $_SESSION['temp']; $_SESSION['temp']= 'x';}
	if (isset($_SESSION['baro']) && $_SESSION['baro'] <> 'x'){ $baro= $_SESSION['baro']; $_SESSION['baro']= 'x';}
	if (isset($_SESSION['wind']) && $_SESSION['wind'] <> 'x'){ $wind= $_SESSION['wind']; $_SESSION['wind']= 'x';}
	if (isset($_SESSION['rain']) && $_SESSION['rain'] <> 'x'){ $rain= $_SESSION['rain']; $_SESSION['rain']= 'x';}
	if (isset($_REQUEST['temp']) && $_REQUEST['temp'] <> 'x'){ $temp= $_REQUEST['temp']; $_REQUEST['temp']= 'x';}
	if (isset($_REQUEST['baro']) && $_REQUEST['baro'] <> 'x'){ $baro= $_REQUEST['baro']; $_REQUEST['baro']= 'x';}
	if (isset($_REQUEST['wind']) && $_REQUEST['wind'] <> 'x'){ $wind= $_REQUEST['wind']; $_REQUEST['wind']= 'x';}
	if (isset($_REQUEST['rain']) && $_REQUEST['rain'] <> 'x'){ $rain= $_REQUEST['rain']; $_REQUEST['rain']= 'x';}
	if ($temp == 'c' ) 	{$SITE['uomTemp'] ='&deg;C';} 	elseif ($temp == 'f' ) 	{$SITE['uomTemp'] = '&deg;F';} 	else 	{$temp ='x';}
	if ($baro == 'hpa' ) 	{$SITE['uomBaro'] =' hPa';} 	elseif ($baro == 'mb' )	{$SITE['uomBaro'] = ' mb';} 	elseif	($baro == 'inhg' )	{$SITE['uomBaro'] = ' inHg';} else {$baro='x';}
	if ($wind == 'kmh' )	{$SITE['uomWind'] =' km/h';} 	elseif ($wind == 'kts' ){$SITE['uomWind'] = ' kts';} 	elseif	($wind == 'ms' )	{$SITE['uomWind'] = ' m/s';}  elseif ($wind == 'mph' ) {$SITE['uomWind'] = ' mph';} else {$wind='x';}
	if ($rain == 'mm' ) 	{$SITE['uomRain'] =' mm';} 	elseif ($rain == 'in' ) {$SITE['uomRain'] = ' in';} 	else 	{$rain='x';}
	$tekst=$temp.'|'.$baro.'|'.$wind.'|'.$rain;
	if ($tekst <> 'x|x|x|x') {
		wsSetcookie($cookie, $tekst, time()+60*60*24*30 ); // cookie expires in 30 days		
	}
	if ($wsDebug) {
#		$pathString .= '<!-- module '.$pageFile.' on exit: fctOrg='.$fctOrg.' fctContent = '.$fctContent.' -->'.PHP_EOL;
		$pathString .= '<!-- module wsCookie.php ('.__LINE__.'): on exit: uoms ='.$tekst.' -->'.PHP_EOL;
	}

}
#--------------------------------------------------------------------------------------------------
if (isset($SITE['userChangeForecast'])  && $SITE['userChangeForecast']){
	$fctOrg		= $SITE['fctOrg'];
#	$fctContent	= $SITE['fctContent'];
	if ($wsDebug) {
#		$pathString .= '<!-- module '.$pageFile.' on entrance: fctOrg='.$fctOrg.' fctContent = '.$fctContent.' -->'.PHP_EOL;
		$pathString .= '<!-- module wsCookie.php ('.__LINE__.'):  on entrance: fctOrg='.$fctOrg.' -->'.PHP_EOL;
	}
	$cookie = $cookieName.'fctOrg';
	if (isset ($_COOKIE[$cookie])) {				// first check  cookie
		$fctOrg = trim(strtolower($_COOKIE[$cookie]));
	}
#
/*	$cookie = $cookieName.'fctContent';
	if (isset ($_COOKIE[$cookie])) {				// first check  cookie
		$fctContent = trim(strtolower($_COOKIE[$cookie]));
	} */
#
	if (isset($_REQUEST['fct'])){					// than check this request settings
#		$s=trim(strtolower($_REQUEST['fct']));
#       list ($fctOrg, $fctContent) = explode ('|',$s);
		$fctOrg = trim(strtolower($_REQUEST['fct']));
	}
#
	# now save for further reference
	$cookie = $cookieName.'fctOrg';
	$SITE['fctOrg']			= $fctOrg;
	wsSetcookie($cookie, $fctOrg, time()+60*60*24*30 ); // cookie expires in 30 days
/*	$cookie = $cookieName.'fctContent';
	$SITE['fctContent']		= $fctContent;
	wsSetcookie($cookie, $fctContent, time()+60*60*24*30 ); // cookie expires in 30 days	*/
	if ($wsDebug) {
#		$pathString .= '<!-- module '.$pageFile.' on exit: fctOrg='.$fctOrg.' fctContent = '.$fctContent.' -->'.PHP_EOL;
		$pathString .= '<!-- module wsCookie.php ('.__LINE__.'):  on exit: fctOrg='.$fctOrg.' -->'.PHP_EOL;
	}
}
#--------------------------------------------------------------------------------------------------
if ( isset ($_REQUEST['default']) ){			// reset all to site defaults
	unset ($_REQUEST['default']);
	$cookieAllowed	= true;
	$lang 				= $SITE['langBackup'];
	$SITE['lang']		= $SITE['langBackup'];			// $SITE values to their origanl
	$SITE['menuPlace']	= $SITE['menuPlaceBackup'];
	$SITE['colorNumber']= $SITE['colorBackup'];
	$SITE['header']  	= $SITE['headerBackup'];
	$SITE['noChoice']	= $SITE['noChoiceBackup'];
	$SITE['uomTemp']	= $uomBackup['uomTemp'];
	$SITE['uomBaro']	= $uomBackup['uomBaro'];
	$SITE['uomWind']	= $uomBackup['uomWind'];
	$SITE['uomRain']	= $uomBackup['uomRain'];
	$SITE['fctOrg']		= $SITE['fctOrgBackup'];
#	$SITE['fctContent']	= $SITE['fctContentBackup'];

	$_SESSION['lang']	= $SITE['langBackup'];			// $_SESSION to defaults
	$_SESSION['menu']	= $SITE['menuPlaceBackup'];
	$_SESSION['mood']	= $SITE['colorBackup'];
	$_SESSION['hdrSelect'] = $SITE['headerBackup'];
	$_SESSION['choice'] = $SITE['noChoiceBackup'];
	$_REQUEST['lang']	= $SITE['langBackup'];			// $_REQUEST to defaults
	$_REQUEST['menu']	= $SITE['menuPlaceBackup'];
	$_REQUEST['mood']	= $SITE['colorBackup'];
	$_REQUEST['hdrSelect']= $SITE['headerBackup'];
	$_REQUEST['choice'] = $SITE['noChoiceBackup'];
	$_REQUEST['fctOrg'] = $SITE['fctOrgBackup'];
#	$_REQUEST['fctContent']= $SITE['fctContentBackup'];

	$cookie = $cookieName."lang";
	wsSetcookie($cookie, $SITE['langBackup'], time()+2 ); 	// cookie expires in 2 seconds to get rid of the other ones
	$cookie = $cookieName."menu";
	wsSetcookie($cookie, $SITE['menuPlaceBackup'], time()+2 );
	$cookie = $cookieName."mood";
	wsSetcookie($cookie, $SITE['colorBackup'], time()+2 );
	$cookie = $cookieName."hdrSelect";
	wsSetcookie($cookie, $SITE['headerBackup'], time()+2 );
	$cookie = $cookieName."choice";
	wsSetcookie($cookie, $SITE['noChoiceBackup'], time()+2 );
	$cookie = $cookieName."uom";
	wsSetcookie($cookie, 'x|x|x|x', time()+2 );
	$cookie = $cookieName.'fctOrg';
	wsSetcookie($cookie, $SITE['fctOrgBackup'], time()+2  );
#	$cookie = $cookieName.'fctContent';
#	wsSetcookie($cookie, $SITE['fctContentBackup'], time()+2  );
	$cookie = $cookieName."allowed";
	wsSetcookie($cookie,'N',time()+2 );
}
function wsSetcookie ($cookie, $doSomething,$time) {
	global $cookieAllowed;
	if ($cookieAllowed == true) {
		setcookie($cookie, $doSomething, $time); // cookie expires in 1 hour
	} 
	return;
}