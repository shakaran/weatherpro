<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsFooter.php';
$pageVersion	= '3.20 2015-09-25';
#-------------------------------------------------------------------------------
# 3.20 2015-09-25 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$versionFooter = '2.8a';
$strVisitors	= '';
$back_top       = 'pagina';  // 'pagina' = top of page   -  'data-area'  - top of information area (vertical menu);
$back_top       = 'data-area';
$guest		= ' unknown';
$ip 	        = $SITE['REMOTE_ADDR'];
ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): ip address = '.$ip.' -->');
$ownIP          = '';
#if (isset ($SITE['yourIP']) ) {$ownIP = $SITE['yourIP'];}
#
# first check and calculate the number of visitors
#
if ($SITE['showVisitors']) {
	$path 	=	$SITE['cacheDir'];
	$file	=	$path.$SITE['visitorsFile'];  // file with userdata
	$found	= 	false;
	$users	=	0;
	if ($ip == '::1') {$ip = 'localhost';}   // if we are testing on local pc
	#---------------------------------------------------------------------------
	#  load array with previous users os this website
	#---------------------------------------------------------------------------
	if (file_exists($file)) {							// if file was found
		$filestring=file_get_contents($file);
		ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): ip users ('.$file.') loaded     -->');
		$online = unserialize(base64_decode($filestring));  // recreate php array
		foreach ($online as $key => $record) {				//  check ip adres already known and update this record
			if ($key == $ip) {
				if (!isset($_SERVER['REQUEST_URI']))   {
       				$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'],1 );
       				if (isset($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING']; }
				} 	
				$online[$key]['page'] = $_SERVER['REQUEST_URI'];
				$online[$key]['time'] = time();
				$record['time']= time();
				if ($online[$key]['starttime'] == 0) {$online[$key]['starttime'] = time();}
				if ($online[$key]['active'] <> 'j')  {$online[$key]['starttime'] = time();}
				$online[$key]['active'] = 'j';
				$found	= true;
				$guest	= $online[$key]['guest'];	// ip user known indicator
			}
			$time = time();
			if ($key <> '0.0.0.0') {
				if (($time - $online[$key]['time']) >= 900) {  // more than 15 minutes inactive
					$online[$key]['starttime']= 0;			
					$online[$key]['active'] = 'n';
				} else {
					$online[$key]['active'] = 'j';
				}
				if ($online[$key]['active'] ==='j') {$users++;}
			}
		}
	
	} else {
		ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): NO ip users file ('.$file.'), create table     -->');
		$fileExist = false;
		$online = array();
		$online['0.0.0.0']['ip']		= 'ip';
		$online['0.0.0.0']['page']		= 'page';
		$online['0.0.0.0']['guest'] 	= 'who';
		$online['0.0.0.0']['time']		= 'last click';
		$online['0.0.0.0']['starttime']	= 'since';
		$online['0.0.0.0']['active']	= 'active';
		$online['0.0.0.0']['country']	= 'country';
		$online['0.0.0.0']['flag']		= 'flag';
	}  	// eo filestring found
	#---------------------------------------------------------------------------
	#  if user not found: load array with  users ip info 
	#---------------------------------------------------------------------------
	if (!$found && ($ip <> 'localhost')) {	//  user not known insert into array: ip, time page and do geolookup
		$users++;
		if (isset($SITE['geoKey']) && $SITE['geoKey'] <> '') {
			ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): loading inc/geoIp.php -->');
			include_once 'inc/geoIp.php'; 
			$ipaddress = $ip;
			ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): user not found IPaddress =  '.$ip.' -->');
	#---------------------------------------------------------------------------
	#  if user not found: do geo lookup
	#---------------------------------------------------------------------------
			$ipinfo = GeoIP :: getGeoArray($ipaddress);			// geo lookup
		} else {
			$ipinfo['CODE'] = '';
			$ipinfo['FLAG'] = '';
		}
		if (getenv('HTTP_USER_AGENT')) {
			$agent = getenv('HTTP_USER_AGENT');
		} else {
			if ($_SERVER['HTTP_USER_AGENT']) {
				$agent = $_SERVER['HTTP_USER_AGENT'];
			} else {
				$agent = '';
			}
		}
	#---------------------------------------------------------------------------
	#  if user not found: check if its a bot or a real user
		$guest='unknown';
		$user_agent_lower = strtolower($agent);
	// see if the user is a spider (bot) or not based on a list of spiders in spiders.txt file
		if ($user_agent_lower <> '') {	
			$spiders = file_get_contents($SITE['spidersTxt'],'r');
			if ($spiders == false) {ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): no spiders file   -->' );} 
			else {
				$guest='guest';
				$arrSpiders= explode (PHP_EOL, $spiders);
				$cnt=count($arrSpiders)-1;
				for ($i=0;$i <= $cnt; $i++) {
					$string=trim($arrSpiders[$i]);
					if (!strpos($user_agent_lower, $string,0) === false) {
						$guest = $string;
						break;
				}  // spider found	
				}  // eo for loop spiders array
			} // end else
		} // eo useragent check
	}  // eo new user
	#---------------------------------------------------------------------------
	#  if user not found: put data into array
	#---------------------------------------------------------------------------
	if (!$found) {
		$online[$ip]['ip']		= $ip;
		$online[$ip]['page']		= $_SERVER['REQUEST_URI'];
		$online[$ip]['guest'] 		= $guest;
		$online[$ip]['time']		= time();
		$online[$ip]['starttime']	= time();
		$online[$ip]['active']		= 'j';
		if (isset ($ipinfo['CODE'])) {$online[$ip]['country'] 	= $ipinfo['CODE'];} else {$online[$ip]['country'] = '';}
		if (isset ($ipinfo['FLAG'])) {$online[$ip]['flag'] 	= $ipinfo['FLAG'];} else {$online[$ip]['flag'] 	  = '';}
	} // end of user not known
	#---------------------------------------------------------------------------
	#  save array with previous users os this website
	#---------------------------------------------------------------------------
	$safe_string_to_store = base64_encode(serialize($online)); // export array
	$ret=file_put_contents ($file, $safe_string_to_store, LOCK_EX);
	#---------------------------------------------------------------------------
	#  print last lines of footer
	#---------------------------------------------------------------------------
	if  ($users > 1) {
		$strVisitors = $users.' '.langtransstr('user(s) online')." | ";
	} elseif ($users == 1) {
	        $strVisitors = '1 '.langtransstr('user online')." | ";
	}
}  // eo visitors
#
# count / show pages visited
#
if ( isset ($SITE['pages_visited'])  && ($ownIP <> $ip) && $SITE['pages_visited'] && !isset($cron_all) && $guest == 'guest' )    {
        $arrVisits	= array();
	$file	        = $SITE['cacheDir'].'pagesVisited.txt'; // file with userdata
	if (file_exists($file)) {				// if file was found
		$filestring	= file_get_contents($file);
		ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): visited pages ('.$file.') loaded -->');
		$arrVisits	= unserialize($filestring);
	}
	$page	        = $SITE['pageRequest'];
	if (!isset ($arrVisits[$page]) ) {$arrVisits[$page] = 0;}
	$arrVisits[$page]++;
	$filestring	= serialize($arrVisits);
	file_put_contents ($file, $filestring, LOCK_EX);
	
	ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): visited pages ('.$file.') saved -->');
	ws_message (  '<!-- module wsFooter.php ('.__LINE__.'): '.print_r($arrVisits,true).' -->');

}  // eo pages visited
#---------------------------------------------------------------------------
$credit = langtransstr('Scripts from').': <a href="http://leuven-template.eu/index.php?lang='.$lang.'" target="_blank">Leuven-Template</a>  ('.$versionFooter.') | '.PHP_EOL;
$program= langtransstr('Weather program').': <a href="'.$SITE['WXsoftwareURL'].'" target="_blank">'.$SITE['WXsoftwareLongName'].'</a>'.PHP_EOL;
# now we generate the foorter html as a string
#
$strFooter      = '<div id="footer" class="blockDiv" >
<div style="float: left;" >
  <a href="#'.$back_top.'" title="Goto Top of Page"><img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;"></a>
</div>
<!--  copyright - links to software used    -->
&copy; '.gmdate("Y",time()).' '.$SITE['organ'].' | '.$credit.$program;
#
if( isset($ws['wsVersion'])   && $ws['wsVersion'] <> ''  && $ws['wsVersion'] <> '--'   && $ws['wsVersion'] <> '---'  && trim($ws['wsVersion']) <> '')     {$strFooter     .= ' ('.$ws['wsVersion'].')';}
#
$strFooter      .= '
<br /><br />
<!--  Visitors - Contact - HTML check - CSS check   -->
'.$strVisitors;
if ( $SITE['contactPage'] == 'yes'  || $SITE['contactPage'] == true )    {
        $strFooter     .= '<a href="'. $SITE['pages']['incContact'].'&amp;lang='.$lang.$extraP.$skiptopText.'">'.langtransstr('Contact Us').'</a>';
}
if(!isset($skipHTML5) || $skipHTML5 == false) 
        {$strFooter     .= ' | <a href="http://validator.w3.org/nu/?doc=http://'.$_SERVER['SERVER_NAME'].htmlspecialchars($_SERVER['REQUEST_URI']).'"> '.langtransstr('Valid').' HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3"> '.langtransstr('Valid').' CSS</a>';
}
$strFooter .= '<br /><br />'.langtransstr('Never base important decisions on this or any weather information obtained from the Internet').PHP_EOL;
#
if ( isset($SITE['statsCode'])   && $SITE['statsCode'] <> ''  && file_exists ($SITE['statsCodeTxt']) )   {
        $strFooter      .= '<!-- loading statscode --><br /><br />'.PHP_EOL.file_get_contents($SITE['statsCodeTxt']).PHP_EOL;
}
#
if ( isset ($SITE['socialSiteSupport']) && $SITE['socialSiteSupport'] <> false )    {
        $strFooter     .=  '<!-- AddThis Button script -->
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid='.$SITE['socialSiteKey'].'"></script>'.PHP_EOL;
}
$strFooter      .= '</div>
<!-- end id="footer" -->'.PHP_EOL;
echo $strFooter;
# ----------------------  version history
# 3.20 2015-09-25 release 2.8a version 
