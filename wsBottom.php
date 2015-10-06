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
$pageName	= 'wsBottom.php';
$pageVersion	= '3.20 2015-09-12';
#-------------------------------------------------------------------------------
# 3.20 2015-09-12 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------

$styleImg       = 'style="max-width: 110px; vertical-align: bottom; max-height: 80px;"';
#
if ($SITE['partners'] 	== 'B' &&  is_file('./_my_texts/partners.php') ) {	// show other websites with our weatherdata here
	if (!isset ($arrPartners) ) {
		ws_message (  '<!-- module wsBottom.php ('.__LINE__.'): loading _my_texts/partners.php -->');
		include         './_my_texts/partners.php';
	}
	$doIcons        ='yes';
	$headString     = langtransstr('You find our weatherinfo also at').':';
	$cntDisplay     = 0;
	$display 	= false;
	foreach ($arr_part_OK as $nr => $key) {
		if (!isset ($arrPartners[$key]) ) {continue;}
		$arr    = $arrPartners[$key];
		if ($arr['sideBar'] && $arr['id']) {
			$display = true;
			$cntDisplay++;
		}	
	}
	if ($cntDisplay > 10) {$cntDisplay = 10; }
	if ($display == true) {
		$width          = round(100 / $cntDisplay,4);
		$styleTd        = 'style="width: '.$width.'%;"';
		echo '<div class="blockDiv" style="margin: 5px 5px 0px 5px;">'.PHP_EOL;
		echo '<h3 class="blockHead">'.$headString.'</h3>
<table class="genericTable">'.PHP_EOL;
		$i=1;
		foreach ($arr_part_OK as $nr => $key) {
	#	foreach ($arrPartners as $key => $arr) {
			if (!isset ($arrPartners[$key]) ) {continue;}   
			 $arr    = $arrPartners[$key];
			if ($arr['sideBar'] && $arr['id']) {
				if ($i == 1) {echo '<tr>'.PHP_EOL; $i = 1;}
				if (isset($arr['target']) ) {$target =' target="'. $arr['target'].'"';} else {$target = ' target="_blank"';}
				$string= '<td '.$styleTd .'>'.PHP_EOL.'<p><a href="'.$arr['href'].'" '.$target.'>';				
				if ($doIcons=='yes') {
					if (!isset ($arr['webImgSmall'] ) || trim($arr['webImgSmall']) == '' )  { $img = $arr['webImg']; } else { $img = $arr['webImgSmall']; } 
					if ($img <> '') {
						$string .= '<img '.$styleImg.' src="'.$img.'" title="'.$arr['webAlt'].'" alt="'.$arr['webAlt'].'"/>';
					}
				} 
				elseif ($arr['name'] <> '') { $string .=$arr['name']; }
				$string.= '</a></p>'.PHP_EOL.'</td>'.PHP_EOL;
				echo $string;
				$i++;
				if ($i > $cntDisplay) {echo '</tr>'.PHP_EOL; $i = 1;}
			}
		}
		if ($i <> 1) {
			for ($i; $i <= $cntDisplay; $i++) {echo '<td>&nbsp;</td>';}
			echo '</tr>'.PHP_EOL;
		}
		echo '</table>
</div>'.PHP_EOL;
	}
	unset ($sideArrWeather);
} // end of other websites with our weatherdata
#-----------------------------------------------------------------------------------------	
if ($SITE['equipment'] 	== 'B' ) {	// show which equipment we use here
	$altTxt         =langtransstr('Equipment we use').': ';
	$doIcons        ='yes';
	$sideArrHwSw    = array();
	$headString     = $altTxt;
	$display	= false;
	if ($SITE['stationShow']) {
		$display = true;
		$sideArrHwSw['station'] = array (
			'name'	=>	langtransstr('Weatherstation'),
			'icon'	=>	$SITE['stationJpg'],
			'link'	=>	$SITE['stationLink'],
			'alt'	=>	$SITE['stationTxt']);
	}
	if ($SITE['WXsoftwareShow']) {	
		$display = true;
		$sideArrHwSw['program'] = array (
			'name'	=>	langtransstr('Weatherprogram'),
			'icon'	=>	$SITE['WXsoftwareIcon'] ,
			'link'	=>	$SITE['WXsoftwareURL'],
			'alt'	=>	$SITE['WXsoftwareLongName']);
	}
	if ($SITE['pcShow']) {	
		$display = true;	
		$sideArrHwSw['pc'] = array (
			'name'	=>	langtransstr('Weatherserver'),
			'icon'	=>	$SITE['pcJpg'],
			'link'	=>	$SITE['pcLink'],
			'alt'	=>	$SITE['pcTxt'] );
	}
	if ($SITE['providerShow']) {	
		$display = true;
		$sideArrHwSw['provider'] = array (
			'name'	=>	langtransstr('Provider'),
			'icon'	=>	$SITE['providerJpg'],
			'link'	=>	$SITE['providerLink'],
			'alt'	=>	$SITE['providerTxt']);
	}
	$count = count($sideArrHwSw);
	if ($display == true && $count > 0) {
		echo '<div class="blockDiv" style="margin: 5px 5px 0px 5px;">'.PHP_EOL;
		$width          = round(100 / $count);
		$styleTd        = 'style="width: '.$width.'%;"';
		$string1 	= '';  // the names of the items displayed
		$string2 	= '';	// the immages
		foreach ($sideArrHwSw as $key => $arr) {		
			$string1 .= '<td '.$styleTd .'><p>'.$arr['name'].'<br />'.$arr['alt'].'</p></td>'.PHP_EOL;
			$string2 .= '<td '.$styleTd .'><p>
  <a href="'.$arr['link'].'" target="_blank">';				
			if ($doIcons=='yes') {
				if ($arr['icon'] <> '') {$string2 .= '
   <img '.$styleImg.' src="'.$arr['icon'].'" title="'.$arr['alt'].'" alt="'.$arr['alt'].'"/>';}
			} 
			$string2 .= '
  </a>
</p></td>'.PHP_EOL;;

		}  // eo for
		echo '<h3 class="blockHead">'.$headString.'</h3>
<table class="genericTable">
<tr>'.PHP_EOL.$string1.'</tr>
<tr>'.PHP_EOL.$string2.'</tr>
</table>'.PHP_EOL;
		unset ($sideArrHwSw);
		echo '</div>'.PHP_EOL;
	} // end display
        else {ws_message (  '<!-- module wsBottom.php ('.__LINE__.'): no equipment found to display -->');}
} // end equipment
# ----------------------  version history
# 3.20 2015-09-12 release 2.8 version 
