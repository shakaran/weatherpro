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
$pageName	= 'wsSideColom.php';
$pageVersion	= '3.20 2015-07-13';
#-----------------------------------------------------------------------
# 3.20 2015-07-13  rel 2.8 version
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');;
# ----------------------------------------------------------------------
# displays left colomn with menu and/or other weatherstations and/or partners and/or eqipment in use
#---------------------------------------------------------------------------
if ($SITE['menuPlace'] 	== 'V') {   // show menu
	print '<ul id="nav">
'.$DropdownMenuText.'</ul>
<br />'.PHP_EOL;	
} // end of menu
#---------------------------------------------------------------------------
# 	optional curly warnings
#---------------------------------------------------------------------------
if ($SITE['region'] ==	'america' && $SITE['useCurly']) {
	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading nws-alerts-config.php -->');
	include_once 'usa/nws-alerts/nws-alerts-config.php'; 				// include the config file
	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading '.$cacheFileDir.$iconFileName.' -->');	
	include $cacheFileDir.$iconFileName; 						// include the big icon file
	if ( count($bigIcons)  > 0) {
		// construct icons
		#$bigIcos = '<div style="text-align:center">'."\n";
		$bigIcos =  '<p>Active alerts</p><div style="width: 100px; margin: 0px auto;">'.PHP_EOL;;
		foreach($bigIcons as $bigI) { 
		$bigIcos .= $bigI;
		}
		#$bigIcos .= " <br />\n</div>\n<!-- end nws-alerts icons -->\n";
		$bigIcos .= "</div>\n<!-- end nws-alerts icons --><br />\n";
		echo $bigIcos;
	}
}
#---------------------------------------------------------------------------
#		optional donate button
#---------------------------------------------------------------------------
if (	isset ($SITE['donateButton']) 	&& $SITE['donateButton'] == true && 
	isset ($SITE['donateCode']) 	&& is_file($SITE['donateCode']) ) {
	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading '.$SITE['donateCode'].' -->');	
	include $SITE['donateCode'];  
}
#---------------------------------------------------------------------------
#		optional social networks facebook 
#---------------------------------------------------------------------------
if (	isset ($SITE['socialSiteSupport']) 	&& $SITE['socialSiteSupport'] == "V" &&
	isset ($SITE['socialSiteCode']) 	&& is_file($SITE['socialSiteCode']) ) {  
	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading '.$SITE['socialSiteCode'].' -->');
	include $SITE['socialSiteCode']	; 
}
#---------------------------------------------------------------------------
# general values for small images in side area
#---------------------------------------------------------------------------
$link_text      = langtransstr('more information');
$title_text     = langtransstr('Click image to enlarge');
$img_style      = 'style="margin: 0; vertical-align: bottom;"';
$img_time 	= date('Ymdh');
#---------------------------------------------------------------------------
#	 show optional webcam here
#---------------------------------------------------------------------------
if ( ($SITE['webcam'] == true) && ($SITE['webcamSide'] == true || $SITE['webcamSide']   == 'V') ) {
        $SITE['webcam_1']	= $SITE['webcam'];
        $webcamNight		= '';
	if ($dayNight == 'nighttime' && $SITE['webcamNight'] && $SITE['webcamImgNight'] <> '') {
		$webcamNight	= $SITE['webcamImgNight'];
	}
	$feed			=  '';
	if ($SITE['webcamPage'] && isset ($SITE['pages']['wsWebcamPage']) ) {
		$feed	= $SITE['pages']['wsWebcamPage'];
	}
	for ($n1 = 1; $n1 <= 4; $n1++)  {
		if (!isset ($SITE['webcam_'.$n1]) || $SITE['webcam_'.$n1] == false) {continue;}
		if ($webcamNight <> '') {$image = $webcamNight; } else {$image = $SITE['webcamImg_'.$n1]; }
		$name	= $SITE['webcamName_'.$n1];
                if ($feed <> '') {
                        $linkImg = '<a   href="'.$feed.'&amp;lang='.$lang.$extraP.$skiptopText.'" >
<span style="float: right;"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="'.$link_text.'" title="'.$link_text.'" /></span></a>';
                }
                else {  $linkImg = '<a  href="'.$image.'"  rel="lightbox" title="'.$title_text.'">
<span style="float: right;"><img src="./img/i_symbol.png" alt=" " style="margin: 1px; vertical-align: middle; width: 15px;"></span></a>';
                }
                echo '<p>'.langtransstr($name).$linkImg.'
<a  href="'.$image.'"  rel="lightbox" title="'.$title_text.'">
  <img  src="'.$image.'" '.$img_style .'  alt="'.$name.'" />
</a>
</p>
<br />'.PHP_EOL; 
	} // eo for every webcam
} // end of webcam
#---------------------------------------------------------------------------
#	show lightning indication to click on ==> separate page
#	this part is always shown when there are warnings for thunder
# 	true = Always  |  false = Do not display  |  optional = Display with thunder warning
#---------------------------------------------------------------------------
$showLightning = false;		
if 	($SITE["showLightning"] === false ) {$showLightning = false;}
elseif 	($SITE["showLightning"] === true )  {$showLightning = true;}
elseif  (isset ($SITE['wrnLightning']) && $SITE['wrnLightning'] == true  ) {$showLightning = true;}
if ($showLightning == true) {  
        if (!isset ($ws['img_lightning']) ) {
        	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading _my_scripts/set_links.php -->');
                include '_my_scripts/set_links.php';
        }
        $img_lightning  = $ws['img_lightning'].'?t='.$img_time;
        #
        if (isset ($SITE['pages']['thunderRadar']) ) {
                $link_thunder   = '<a href= "'.$SITE['pages']['thunderRadar'].'&amp;lang='.$lang.$extraP.$skiptopText.'">
<span style="float: right;"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="'.$link_text.'" title="'.$link_text.'" /></span></a>';
        } 
        else {  $link_thunder   = '<a  href="'.$img_lightning.'"  rel="lightbox" title="'.$title_text.'">
<span style="float: right;"><img src="./img/i_symbol.png" alt=" " style="margin: 1px; vertical-align: middle; width: 15px;"></span></a>';
        }
#
	if (!isset ($wrnThun) ) {
		$wrnThun = langtransstr('Thunder radar');
	} else {
		$wrnThun = langtransstr('warning for').' '.langtransstr($wrnThun);
	}
	$wrnThun .= $link_thunder;
	echo '<p>'.$wrnThun.'<a href="'.$img_lightning.'" rel="lightbox" title="'.$title_text.'"> 
  <img src="'.$img_lightning.'" '.$img_style.' alt="blitzortung!" />
 </a>
</p>
<br />'.PHP_EOL; 
} // eo lightning
#
#---------------------------------------------------------------------------
#	show rain / snow indication to click on ==> separate page
#	this part is always shown when there are warnings for rain / snow
# 	true = Always  |  false = Do not display  |  optional = Display with rain warning
#---------------------------------------------------------------------------
$showRain = false;
if 	($SITE["showRain"] === false ) {$showRain = false;}
elseif 	($SITE["showRain"] === true )  {$showRain = true;}
elseif  (isset ($SITE['wrnRain']) && $SITE['wrnRain'] == true ) {$showRain = true;}
if ($showRain == true) {  
        if (!isset ($ws['img_rain']) ) {
        	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading _my_scripts/set_links.php -->');
                include '_my_scripts/set_links.php';
        }
        $imgRain        = $ws['img_rain'];
        if ($imgRain <> '') {
#                $imgRain        .= '?t='.$img_time;
        }
        if (isset ($SITE['pages']['wsPrecipRadar']) ) {
                $link_rain      = ' <a href="'.$SITE['pages']['wsPrecipRadar'].'&amp;lang='.$lang.$extraP.$skiptopText.'">
<span style="float: right;"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="'.$link_text.'" title="'.$link_text.'" /></span></a>';
        }
        elseif ($imgRain <> '') {  
                $link_rain      = '<a  href="'.$imgRain.'"  rel="lightbox" title="'.$title_text.'">
<span style="float: right;"><img src="./img/i_symbol.png" alt=" " style="margin: 1px; vertical-align: middle; width: 15px;"></span></a>';
        } else $link_rain      = '';
#
	if (!isset ($wrnRain) ) {
		$wrnRain = langtransstr('Rain radar').$link_rain;
	} else {
		$wrnRain = langtransstr('Warning for').' '.langtransstr($wrnRain).$link_rain;
	}
	echo '<p>'.$wrnRain.'<a href="'.$imgRain.'" rel="lightbox" title="'.$title_text.'">
   <img src="'.$imgRain.'" '.$img_style.' alt="rain snow radar" />
 </a>
</p>
<br />'.PHP_EOL; 
}  // eo showrain
#---------------------------------------------------------------------------
#	optional friendly weatherstations go here
#---------------------------------------------------------------------------
if ($SITE['otherWS'] 	== 'V' &&  is_file('./_my_texts/friendly_websites.php')) {
	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading ./_my_texts/friendly_websites.php -->');
	include './_my_texts/friendly_websites.php';
	if (isset ($sideArrCoop) && is_array ($sideArrCoop) )  {
		$count = count ($sideArrCoop);
		for ($n1 = 0; $n1 < $count; $n1++) {
			$arr	= $sideArrCoop[$n1];
			if ($arr['show'] <> true) {
				unset ($sideArrCoop[$n1]);
			}
		}
		$count = count ($sideArrCoop);
		if (!isset ($headString) ) {$headString = '';}
		for ($n1 = 0; $n1 < $count; $n1++) {
			$arr	= $sideArrCoop[$n1];
			echo $headString;	// will be displayed once only
			$string = '<p style="margin-top: 4px;"><a href="'.$arr['link'].'" target="_blank">';
			if ($arr['name'] <> '') 		
				{$string.=$arr['name'];}				
			if ($doIcons && $arr['icon'] <> '') 	
				{$string.='<img src="'.$arr['icon'].'" alt="'.$arr['alt'].'"/>';}
			$string.='</a></p>'.PHP_EOL; 
			echo $string;
			$headString='';
		}
		unset ($sideArrCoop);
		if ($count <> 0) {echo '<br />'.PHP_EOL; }
	}
} // end of weatherstations
#---------------------------------------------------------------------------
#	oprional other websites with our weatherdata are shown here
#---------------------------------------------------------------------------
if ($SITE['partners'] 	== 'V' &&  is_file('./_my_texts/partners.php')) {
	ws_message (  '<!-- module wsSideColom.php ('.__LINE__.'): loading ./_my_texts/partners.php -->');
        include './_my_texts/partners.php';
	# ------------------ clear hrefs for empty id's
	foreach ($arrPartners as $name => $arr) {
		if (!$arr['id'])  {
			$arrPartners[$name]['sideBar']	= false;
			$arrPartners[$name]['href'] 	= false;
		} else {
			$arrPartners[$name]['sideBar']	= true;
		}
	}
	$doIcons='yes';
	$headString = '<p style="margin-bottom: 4px;">'.langtransstr('You find our weatherinfo also at').':</p>'.PHP_EOL;
	#
	foreach ($arr_part_OK as $nr => $key) {
	        if (!isset ($arrPartners[$key]) ) {continue;}
	        $arr    = $arrPartners[$key];
		if (!$arr['sideBar']) {continue;}
		echo $headString; 
		if ($arr['href']=='') {$arr['href'] = $arr['webLink'];}
		$string = '<p style="margin-bottom: 4px;">'.'<a href="'.$arr['href'].'"';
		if (isset ($arr['target']) && $arr['target'] <> '') {
			$string .= ' target="'.$arr['target'].'"';	
		}
		$string .= '>';
		if ($doIcons=='yes') {
			if (isset ($arr['webImgSmall']) && $arr['webImgSmall'] <> '')  {
				$img=$arr['webImgSmall'];
			}  
			else {
				$img=$arr['webImg'];
			}
			if ($img <> '') {
				$string.='<img style="border-radius: 5px;" src="'.$img.'" title="'.$arr['webAlt'].'" alt="'.$arr['webAlt'].'"/>';
			}
		} 
		elseif ($arr['name'] <> '') {
			$string.=$arr['name'];
		}
		$string.='</a></p>'.PHP_EOL;
		echo $string;
		$headString='';
	}
	echo '<br />'.PHP_EOL;
	unset ($arrPartners);
} // end of other websites with our weatherdata
#---------------------------------------------------------------------------
#	optional show which equipment we use here
#---------------------------------------------------------------------------
if ($SITE['equipment'] 	== 'V' ) {	// show which equipment we use here
	$doIcons	= true;
	$doNames	= false;
	$sideArrHwSw	= array();
	if ($SITE['stationShow']) {
		$sideArrHwSw[] = array (
		'name'	=>	langtransstr('Weatherstation'),
		'icon'	=>	$SITE['stationJpg'],
		'link'	=>	$SITE['stationLink'],
		'alt'	=>	$SITE['stationTxt']);
	}
	if ($SITE['WXsoftwareShow']) {	
		$sideArrHwSw[] = array (
		'name'	=>	langtransstr('Weatherprogram'),
		'icon'	=>	$SITE['WXsoftwareIcon'] ,
		'link'	=>	$SITE['WXsoftwareURL'],
		'alt'	=>	$SITE['WXsoftwareLongName']);
	}
	if ($SITE['pcShow']) {	
		$sideArrHwSw[] = array (
		'name'	=>	langtransstr('Weatherserver'),
		'icon'	=>	$SITE['pcJpg'],
		'link'	=>	$SITE['pcLink'],
		'alt'	=>	$SITE['pcTxt'] );
	}
	if ($SITE['providerShow']) {	
		$sideArrHwSw[] = array (
		'name'	=>	langtransstr('Provider'),
		'icon'	=>	$SITE['providerJpg'],
		'link'	=>	$SITE['providerLink'],
		'alt'	=>	$SITE['providerTxt']);
	}
	$count 	= count ($sideArrHwSw);
	$head   = '<p style="margin: 2px 2px 10px 6px;">'.langtransstr('Equipment we use').":</p>".PHP_EOL;
	$string	= '';
	for ($n1 = 0; $n1 < $count; $n1++) {
		$arr	= $sideArrHwSw[$n1];		
		$string	.= $head;
		$string.= '<p style="margin: 2px 2px 10px 6px;">';
		if ($arr['name'] <> '' && $doNames) {$string.= $arr['name'];}
		$string.='<a href="'.$arr['link'].'" target="_blank">';
		$string.='<img style="border-radius: 5px;" src="'.$arr['icon'].'" title="'.$arr['alt'].'" alt="'.$arr['alt'].'"/>';
		$string.='</a></p>'.PHP_EOL;
		$head	= '';
	}
	echo $string;
	unset ($sideArrHwSw);
}  // eo equipment