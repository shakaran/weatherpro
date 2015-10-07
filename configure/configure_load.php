<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

$pageName	= 'configure_load.php';
$pageVersion	= '0.01 2015-06-25';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.01 2015-06-25 beta version
# ----------------------------------------------------------------------
#
# load old data from cache if available
# if no cache 
#	load old settingsfile if available
#	load text file and save to cache
#
$settingsLoaded = true;
#
if (!isset ($settings) ) {
	$settingsLoaded = false;
	$settings		= array ();
	$settings['process']	= '00';
	$settings['WXsoftware']	= '--';
	$settings['region']	= '--';
	$settings['organ']	= '';	
}
#	
if ($settingsLoaded == false && is_file ($cachefile) ) {			// cache file defined in start page
	$settings	= unserialize(file_get_contents($cachefile));
	$filetimeUnix	= filemtime ($cachefile);
	$filetime	= date ($SITE['timeFormat'],$filetimeUnix);
	if (isset($settings['status']) ){
		$settingsLoaded = true;
		echo $start_echo.'cachefile found and contains data'.$end_echo;
		$settings['status']	= 'cachefile from '.$filetime.' loaded';
		echo $start_echo.$settings['status'].$end_echo;
	}
	else {	$settings['status']	= 'invalid cachefile from '.$filetime.' discarded';
		echo $start_echo.$settings['status'].$end_echo;	
	}
} // eo cachefile
if ($settingsLoaded == false) {			// try to load previous release settings, defined in start page
	if (is_file ($oldSettings) ) {
		$oldSite= array ();
		$found	= load_old_site($oldSite);
		if ($found) {
			$settings['status']	= 'old settings file '.$oldSettings.' will used';
			echo $start_echo.$settings['status'].$end_echo; 
		}
		else {	$settings['status']	= 'old settings file '.$oldSettings.' seems not to be a real settings file, discarded';
			echo $start_echo.$settings['status'].$end_echo; 
		}
	}
	else { 	$settings['status']	= 'no old settings file found';
		echo $start_echo.$settings['status'].$end_echo;
	} // eo oldsettings
#echo '<pre>';  print_r($oldSite);
}
if ($settingsLoaded == false) {	// load all settings descriptions and optional add answers from old file
	if (!is_file ($newsettings) ) {
		$echo	= '<h3 style="text-align; center">ERROR no settings files found - program halts</h3>'; 
		return false;
	}
	$arr		= file ($newsettings);
#echo '<pre>';  print_r($arr);
	$end_arr	= count ($arr);
	for ($n = 0; $n < $end_arr; $n++) {
		$line	= $arr[$n];
		if (substr($line,0,1)  <> '|') {continue;}
		$items	= explode ('|', $line);		// extra | so that the numbers are 1,2,3,4  
#echo '<pre>';  print_r($items); exit;	
		if (!is_numeric ($items[1]) ) {echo $start_echo.'line '.$n.' skipped - nr not numeric'.$end_echo; continue;}
		$nr		= $items[1];
		$text		= trim($items[2]);
		$set_wp		= $text;
		$set_region	= trim($items[3]);
		if (!isset ($items[4]) ) {echo $start_echo.'Error in line '.$line.$end_echo; continue;}
		$set_key	= trim($items[4]);
		$set_type	= trim($items[5]);
		$set_new	= '';
		$set_old	= trim($items[6]);
		if (!isset ($items[7]) ) 
		     {  $set_values 	= ''; }
		else {	$set_values	= trim($items[7]);}
		#
		if (isset ($oldSite[$set_key]) ) {$set_new 	= $oldSite[$set_key]; }
		$settings [$nr][$n] = array ('wp' => $set_wp,'region' => $set_region,'setting' => $set_key,'type' => $set_type, 'new' => $set_new,'old' => $set_old ,'values' => $set_values);		
	} // eo for each line
}
#echo '<pre>';  print_r($settings); exit;	
$status 	= '';
$process	= ''; #$this_grp;
$next_grp	= '';
$prev_grp 	= '';
$region		= '';
$wp		= '';
$organ		= '';
#echo 'Searching for '.$process.'<br />'.PHP_EOL;

foreach ($settings as $key => $arr) { 
	if ($key == 'status') 	{$status  	= $arr; continue;} // check for site wide settings
	if ($key == 'process') 	{
		if (isset ($this_grp) )
			{$process	= $this_grp;}
		else 	{$process	= $this_grp = $arr; }
		continue;
	}
	if ($key == 'WXsoftware') {$wp 		= $arr; continue;}
	if ($key == 'region') 	{$region 	= $arr; continue;}
	if ($key == 'organ') 	{$organ 	= $arr; continue;}
	if ($key == 'next_grp') {$next_grp 	= $arr; continue;}
	if ($key == 'prev_grp') {$prev_grp 	= $arr; continue;}
	if ($key == $process)   {$form 		= $arr;}
	elseif ($key >  $process)   {$next_grp 	= $key; break;}	
	else 			    {$prev_grp 	= $key;}
}
#echo '$next_grp = '.$next_grp.' - $prev_grp = '.$prev_grp.' - $key = '.$key; exit;
if ($next_grp == '') {$next_grp	= $process;}
if ($prev_grp == '') {$prev_grp	= $process;}
#echo '<pre>'.$process.'-'.$next_grp ;  print_r($form); 

return true;