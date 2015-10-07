<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsPartners.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2015-05-28';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
#-----------------------------------------------------------------------------------------
# 3.00 2015-05-28
#-----------------------------------------------------------------------------------------
#
if (isset($_GET['s'])) {
	$s = $_GET['s'];	
} else {
	$s = 'none';  					// default index.php page
}
$arrPartners    = array();
$folder         = './_my_texts/';

if (isset ($arrPartners) ) {unset ($arrPartners); unset ($arr_part_OK);}
include $folder.'partners.php';

if (file_exists($folder.'partners_'.$lang.'.txt') ) {
	include $folder.'partners_'.$lang.'.txt' ;	// put also the text in the table in the correct language
} 
elseif (file_exists($folder.'partners_'.$lang.'.txt') ) {
	include($folder.'partners_en.txt');	// if no local text exist, than en
}
// ------------------ clear hrefs for empty id's
foreach ($arrPartners as $name => $arr) {
	if (!isset ($arr['id']) || $arr['id'] == false)  {
		$arrPartners[$name]['sideBar']	= false;
		$arrPartners[$name]['href'] 	= false;
	} else {
		$arrPartners[$name]['sideBar']	= true;
	}
}
if (!isset($arrPartners[$s])) {$s = 'none'; }
if ($s == 'none') {
	echo '<div class="blockDiv">'.PHP_EOL;
	echo '<h3 class="blockHead">'.langtransstr('Partnerships').'</h3>'.PHP_EOL;   	// one page with all partner descriptions
	echo '<div style="width: 98%; margin: 0 auto;">'.$partnersTxt."<br />";		// print general general text
	echo '<ul style="padding: 0px;">';	
	foreach ($arr_part_OK as $nr => $key) {
	        if (!isset ($arrPartners[$key]) ) {continue;}
	        $arr    = $arrPartners[$key];
	        if (!isset ($arr['name']) ) {continue;}
		echo "<li>\n";
		if ($arr['href'] <> '') {
			echo '<a href="'.$arr['href'].'" target="'.$arr['target'].'">'.$arr['name'].'</a>';
		}	else {echo $arr['name'];}
		echo ' '.$arr['text']."<br />\nWebsite: ";
		echo '<a href="'.$arr['webLink'].'" target="'.$arr['target']."\">\n\t".'<img src="'.$arr['webImg'].'" alt="'.$arr['webAlt'].'"  title="'.$arr['webAlt'].'" style="max-height: 100px;"/></a>';
		echo "<hr />\n</li>\n";
	}	// end foreach
	echo '</ul>
	</div>
	</div>';
	return;
}		
if (!isset($arrPartners[$s]['frame'])) { echo "2.invalid link\n"; return; }
if      ($s == 'weatherlink')   {$width = '800px'; $height = '1000px'; $color = 'white';}
elseif  ($s == 'awekas')        {$width = '640px'; $height = '700px';  $color = 'transparent';}
elseif  ($s == 'pws')           {$width = '900px'; $height = '1200px'; $color = 'white';}
elseif  ($s == 'wow')           {$width = '960px'; $height = '2200px'; $color = 'white';}
else                            {$width = '100%';  $height = '2200px'; $color = 'transparent';}

echo '<div class="blockDiv" style="background-color: '.$color.';">'.PHP_EOL;
echo '<h3 class="blockHead">'.langtransstr('Our weatherinformation as shown on').' '.$arrPartners[$s]['name'].'&nbsp;&nbsp;
<a href="'.$arrPartners[$s]['webLink'].'" target="_blank">
<img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="link to '.$arrPartners[$s]['name'].'" title="link to '.$arrPartners[$s]['name'].'">
</a>
</h3>
<div style="text-align: center;">
<iframe src="'.$arrPartners[$s]['frame'].'" name="targetFrame" style="width: '.$width.'; margin: 0 auto; height: '.$height.';  border:0; vertical-align: bottom;">
Your browser cannot display iframes and or no support for Flash
</iframe>
</div>
</div>';