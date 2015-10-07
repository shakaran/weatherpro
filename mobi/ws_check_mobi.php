<?php 	#ini_set('display_errors', 'On'); error_reporting(E_ALL);
#
# ----------------------  settings -----------------------------
#$which_script   = 'detectmobilebrowsers';
$which_script   = 'mobiledetect';
#$which_script  = 'no_detect';
#
$mobile         = false;                        // default normal site
$mobi 	        = false;                        // page-number on mobile site if applicable
#
$cookie         = $SITE['cookieName']."skip";   // only used for mobile devices who want to visit main site
$skip_time      = 3600;                         // minutes that a mobi user returns without any page link, => to main site
#
$mobi_link      = 'mobi';;                      // menu entry on main site for php script to mobi site
$mobi_site_start= $SITE['mobileSite'];          // redirect or first page of mobile (phone type) site
# -----------------end of settings -----------------------------
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_check_mobi.php';
$pageVersion	= '3.00 2015-04-22';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString	.='<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
function ws_check_mobi ($which_script) {
	global $pathString;
        $return = false;
        switch ($which_script) {
           case 'detectmobilebrowsers':
           	$pathString	.= '<!-- module ws_check_mobi.php ('.__LINE__.'): loading mobile_device_detect.php  -->'.PHP_EOL;
                require_once '/mobile_device_detect.php';// credits : http://detectmobilebrowsers.mobi/
                $return = mobile_device_detect(true,false,true,true,true,true,true,'','');
                break;
           case 'mobiledetect':
           	$pathString	.= '<!-- module ws_check_mobi.php ('.__LINE__.'): loading Mobile_Detect.php  -->'.PHP_EOL;
                require_once 'Mobile_Detect.php';       // credits : http://mobiledetect.net/ 
                $detect = new Mobile_Detect; 
                if( $detect->isMobile() && !$detect->isTablet() ){
                        $return = true; 
                }
           default:
                break;
        }
        return $return;
}
#
if (!isset ($_COOKIE[$cookie])) {                       // mobile user was not here last hour or so
        $mobile = ws_check_mobi ($which_script);                                    
        if ($mobile) {                                  // so this is a mobile user
                if (isset ($_REQUEST['pcSite']) || isset ($_SESSION['pcSite']) ) {  
                        $mobi           = false;        // mobile user pressed "I want the main site" button
                        $cookieAllowed  = true;         // if users returns in "60" min, the normal site will be used
                        wsSetcookie($cookie, "skip", time() + $skip_time);      
                }
                elseif (!isset ($_REQUEST['mobi']) ) {  // is mobile user but did not request a mobile page
                        $mobi           = 10;
           		$pathString	.= '<!-- module ws_check_mobi.php ('.__LINE__.'): loading mobi_site_start.php  -->'.PHP_EOL;
                        include $mobi_site_start;
                        exit;			        // user goes to mobile site, exit processing for main site.
                }
        }
} 
if (isset ($_REQUEST['p']) ) {                          // the page a pc user wants to go to
        $pnr    = trim($_REQUEST['p']);
        if ($pnr == $mobi_link) {                       // does he request to go mobile, 
                $mobi   = 10;                           // mimic he is already there and asked for the main mobile page
                include $mobi_site_start;
                exit;			                // user stays on mobile site, exit processing for main site.
        }
}	
elseif (isset ($_REQUEST['mobi']) ) {                   // a mobile user wants another page on the mobile site
        $mobi = intval ( trim($_REQUEST['mobi']));
        include $mobi_site_start;
        exit;			                        // user stays on mobile site, exit processing for main site.
}
