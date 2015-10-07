<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

$pageName	= 'configure_page.php';
$version_string	= '0.02 ';
$pageVersion	= $version_string.'2015-09-07';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message (   '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-----------------------------------------------------------------------
# 0.02 2015-09-07  beta version
# ----------------------------------------------------------------------
# SETTINGS
ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading login.php -->');
$result = require('_login.php'); 
if (!$result) {return;}

#
 $pathString 	= '';
langMergeFile ( './configure/lang/' );
if ($pathString <> '') {echo $pathString;}
#
$page_title	= langtransstr("Adapt the settings to reflect your situation").'<span style="float: right;">User: '.$username.' <a href="'.$phpself.'&logout">logout</a>&nbsp;';
$config_folder	= './configure/';
$conf_lng_folder= $config_folder.'lang/';
list ($script) 	= explode ('.',$pageFile);
if (isset ($SITE['pages'][$script]) ) {$form_action = $SITE['pages'][$script];} else {$form_action = 'index.php?p=125';} // should never happen ! 		
#
$cachefile	= $SITE['cacheDir'].$username.'_configure_setup.arr';
$oldSettings 	= '_my_texts/oldsettings.txt';		// add question box
$newsettings	= $config_folder.'settings.txt';
#$this_grp	= '00';
$configure_func	= $config_folder.'configure_func.php';
$configure_load	= $config_folder.'configure_load.php';
$configure_post	= $config_folder.'configure_post.php';
$configure_disp	= $config_folder.'configure_disp.php';
$already_loaded = false;
$start_echo	='<!-- '; $end_echo = ' -->'.PHP_EOL;
#$start_echo	=''; 	  $end_echo = ' <br />'.PHP_EOL;
$conf_debug	= false;
#
ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading '.$configure_func.' -->');
include $configure_func;					// load general functions
 
$file_name_lang = $conf_lng_folder.'questions_'.$lang.'.php';	// check language files
$file_name_def  = $conf_lng_folder.'questions_en.php';
if (is_file($file_name_lang) ) {
	ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading '.$file_name_lang.' -->');
	include $file_name_lang; 
} 
else {	ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading '.$file_name_def.' -->');
	include $file_name_def;
}
#echo '<pre>'; print_r ($LANGLOOKUP); exit;
# ----------------------------------------------------------------------
echo '<div class="blockDiv" style="">
<h3 class="blockHead">'.$page_title.'</h3>'.PHP_EOL;
#
$generate	= false;
# check if there is a post request
if (isset ($_POST['config']) ) {
	if (isset ($_POST['config']['generate']) ) {			
		$this_grp 	= $_POST['config']['this_grp'];
		$next_grp	= $_POST['config']['next_grp'];
		$prev_grp	= $_POST['config']['prev_grp'];
		$generate	= true;
		unset ($_POST['config']);
	}
	elseif (isset ($_POST['config']['start']) ) {		// back to step 1		
		$this_grp 	= '00';	
		unset ($_POST['config']);
	}
	elseif (isset ($_POST['config']['stepb']) ) {		// previous screen
		$this_grp 	= $_POST['config']['prev_grp'];
		unset ($_POST['config']);
	}
	elseif (isset ($_POST['config']['again']) ) {		// after a generate before the end of all questions
	
	}	
	else  {	$this_grp =	 $_POST['config']['this_grp'];	// we have to check the answers given
#echo '<pre>'; print_r($_POST['config']); echo '</pre>';
		ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading '.$configure_load.' -->');
		include $configure_load;			// load settings  for this step
		ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading '.$configure_post.' -->');
		$result	= include $configure_post;		// do the checking
		if ($result == false) {
			echo $echo.'</div>';
			return;					// back to index.php as there is a fatal error
		}		
		if ($count_errors == 0) {
			$this_grp = $next_grp;			// display next step as there are no errors
		}
		else {  $already_loaded = true;			// because of errors display same step again
		}	
		
	}
}
if ($already_loaded <> true) { 					// load settings  for this step
	ws_message (  '<!-- module configure_page.php ('.__LINE__.'): loading '.$configure_load.' -->');
	$result	= include $configure_load;
	if ($result == false) {
		echo $echo.'</div>';
		return;						// back to index.php as there is a fatal error
	}
}	
#
if ($generate == false) {	# print a group of questions
	if ($conf_debug) {echo '<pre>'.print_r($form,true).'</pre>';}
	#
	$footer= 'Settings for'.':&nbsp;'.$organ.'&nbsp;&nbsp;(region: '.$region.' -  program: '.$wp.') Questions: '.$process;
	echo '<div id="config__manager">';
	if (isset ($SITE['skipTop']) && $SITE['skipTop']) {$top = '&amp;show=missing#data-area';} else {$top = '';}
	echo'
<form action="'.$form_action.'&amp;lang='.$lang.$top.'" method="post">
<table class="inline">';
#	echo '<pre>'; print_r($form); exit;
	foreach ($form as $key => $arr) { 
		tr_setting($key, $arr);
	}
	echo '
</table>
<p style="float: right;">
  <input type="hidden" name="config[next_grp]" 		    	value="'.$next_grp.'">
  <input type="hidden" name="config[prev_grp]" 		    	value="'.$prev_grp.'">
  <input type="hidden" name="config[this_grp]" 		    	value="'.$process.'">
  <input type="submit" name="config[submit]" class="button" 	value="'.langtransstr('Save').'">
  <input type="reset"  name="reset"  	     class="button" 	value="'.langtransstr('Reset').'">
  <input type="submit" name="config[start]"  class="button" 	value="'.langtransstr('Back to start').'">  
  <input type="submit" name="config[stepb]"  class="button" 	value="'.langtransstr('Back one page').'">';
if ($process >= '10') {
	echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="config[generate]"  class="button" 	value="'.langtransstr('Generate settings file').'"> ';
}
echo '
</p>
</form>
<p style="padding-left: 10px;"><br /><small>'.$footer.'</small></p>
</div>
</div>';
	return;
}  // eo display normal group
# ----------------------------------------------------------------------
# Now display the generate message and output the user settings file
# ----------------------------------------------------------------------
#
if ($process == '9999') {$process = 'None left';}
$footer= 'Settings for'.':&nbsp;'.$organ.'&nbsp;&nbsp;(region: '.$region.' -  program: '.$wp.') Questions: '.$this_grp;
echo '<div id="config__manager">';
if (isset ($SITE['skipTop']) && $SITE['skipTop']) {$top = '&amp;show=missing#data-area';} else {$top = '';}
#
$settings	= unserialize(file_get_contents($cachefile));
echo'
<form action="'.$form_action.'&amp;lang='.$lang.$top.'" method="post">
<h4 style="">'.langtransstr('We are generating the settings-file');


$date_string	= date('Y-m-d', time() );
$setting_str 	= '<?php
$pageName	= "wsUserSettings.php";
$pageVersion	= "'.$version_string.$date_string.'";
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE["wsModules"][$pageName] = "version: " . $pageVersion;
if (!isset($pathString)) {$pathString="";}
$pathString .= "<!-- module ".$pageName." ==== ".$SITE["wsModules"][$pageName]." -->".PHP_EOL;
#---------------------------------------------------------
# '.$version_string.' generated '.$date_string.'
#---------------------------------------------------------
$SITE["wsDebug"]         = true;         	##### 
#$SITE["wsDebug"]        = false;        	##### remove comment mark at position 1 when you are fully satisfied with your site.
';
define ('MY_EOL', '
');
#echo $string;
# -----------  cleanup and convert answers

$CHECK['uomTemp'] 	= array(' ', '&deg;C', 	'&deg;F');
$VALUES['uomTemp']	= '&deg;C#Celcius!&deg;F#Fahrenheit';
$CHECK['uomBaro'] 	= array(' ', ' hPa', 	' inHg',    ' mb' );
$VALUES['uomBaro']	= ' hPa#hPa! mb#milibar! inHg#inHg';
$CHECK['uomWind'] 	= array(' ', ' km/h',  	' mph', ' kts', ' m/s',	);
$VALUES['uomWind']	= ' km/h# km/h! kts# kts! m/s# m/s! mph# mph';
$CHECK['uomRain'] 	= array(' ', ' mm',	' in' );
$VALUES['uomRain']	= ' mm# mm! in# in';
$CHECK['uomSnow'] 	= array(' ', ' cm',	' in' );
$VALUES['uomSnow']	= ' cm# cm! in# in';
$CHECK['uomDistance'] 	= array(' ', ' km',  	' mi');
$VALUES['uomDistance']	= ' km# kilometer! mi# mile';
$CHECK['uomPerHour'] 	= array(' ', '/hr', 	'/hr');
$VALUES['uomPerHour']	= '/hr# / hour';
$CHECK['uomHeight'] 	= array(' ', ' ft',	' m' );      
$VALUES['uomHeight']	= ' ft# feet! m# meter';
#-----------
$setting_length	= 24;
$from 	= array ('#','!');
$to	= array (' = ', '  |  ');
$count	= count ($settings);
$menu 	= '';
foreach ($settings as $key => $group) {
	if (!is_array($group) ) {continue;}
	foreach ($group as $key => $arr) {
#	print_r ($arr); exit;
		if ($arr['wp'] 	   <> $settings['WXsoftware'] && $arr['wp']     <> '--') {continue;}
		if ($arr['region'] <> $settings['region']     && $arr['region'] <> '--') {continue;}
		if ($arr['type']   == '##' ) {
			$setting_str	.= '#---------------------------------------------------------'.MY_EOL;
			$setting_str	.= '# '.$arr['old'].MY_EOL;
			$setting_str	.= '#---------------------------------------------------------'.MY_EOL;
			continue;
		} 
		elseif ($arr['type']   == '#' ) {
			$setting_str	.= '# '.MY_EOL.'# '.$arr['old'].MY_EOL;
			continue;
		}
		if ($arr['setting'] == 'menuPlace' 	&& $arr['new'] == 'V') { $menu = 'V';}
		if ($arr['setting'] == 'sideDisplay' 	&& $menu       == 'V') { $arr['new'] = 'true';}
		$check  = $arr['setting'];
		$value	= $arr['new'];	
		switch ($check) {
	          case 'aqhiArea':			// add check canada ??
	          	if ($value == '') {$value = $arr['old'];}
	          	list ($_en,$_fr,$code) 	= explode ('#',$value);
	          	$setting_str	.= str_pad( '$SITE["aqhiArea"]' , 	$setting_length).'= "'.$_en.'";'.MY_EOL;
	          	$setting_str	.= str_pad( '$SITE["aqhiArea-f"]' , 	$setting_length).'= "'.$_fr.'";'.MY_EOL;
	          	$setting_str	.= str_pad( '$SITE["aqhiCode"]' , 	$setting_length).'= "'.$code.'";'.MY_EOL;
	          	break;
	          case 'ewnMember':
	          	if ($value == '') 		{$value = '""';}
	          	$setting_str	.= str_pad( '$SITE["ewnMember"]' , $setting_length).'= '.$value.';'.MY_EOL;
	          	$setting_str	.= str_pad( '$SITE["ewnID"]' , $setting_length).'= '.$value.';'.MY_EOL;
	          	break;
	          break;
	          case 'mesoID':
	          	$wn_id = $value;
	          	$setting_str	.= str_pad( '$SITE["mesoID"]' , $setting_length).'= "'.$wn_id.'";'.MY_EOL;
	          	include 'glo/meso_arr.php';
	          	if (isset ($meso_nets[$wn_id]['wn_code'] ) ) {
	          	        $wn_name        = $meso_nets[$wn_id]['wn_name'] ;
	          	        $wn_url         = $meso_nets[$wn_id]['wn_url'] ;
	          	}
	          	else {  $wn_name        = $wn_url = ''; }
	          	$setting_str	.= str_pad( '$SITE["mesoName"]' , $setting_length).'= "'.$wn_name.'";'.MY_EOL;
	          	$setting_str	.= str_pad( '$SITE["mesoLink"]' , $setting_length).'= "'.$wn_url.'";'.MY_EOL;
	          	break;
	          break;
		  default:	
			$string	= str_pad( '$SITE["'.$check.'"]' , $setting_length);
			if (isset($CHECK[$check]) ) {
				if (!isset ($CHECK[$check][$value]) ) {
					if ($region == 'america') {$value = '2';} else {$value = '1';}
				}
				$value 		= $CHECK[$check][$value];
				$arr['values']	= $VALUES[$check];
	#echo '<pre>'; print_r ($arr);
			}
			if ($value === '') {
				$field_typeXX	= $arr['type'];
				$field_type	= str_replace ('region','',$field_typeXX);
				if ($field_type <> $field_typeXX) {
					$field_type	= lcfirst($field_type);
					$arrOld	= explode ('!',$arr['old'].'!');
					foreach ($arrOld as $keyOld => $val_regOld) {
						list ($value_old, $regionOld) 	= explode ('#',$val_regOld.'#');	
						if (($regionOld == $region) || ($regionOld == '')  || ($regionOld == 'all') || ($regionOld == '--')  ) {
							$value	= $value_old;
							break;
						}
					} // eo foreach 
				} 
				else {$value = $arr['old'];}
			}
			if ($value == 'true') 		{$string .= '= true;';}
			elseif ($value == 'false')	{$string .= '= false;';}
			else				{$string .= '= "'.$value.'";';}
			if ($arr['values'] == '') 	{$setting_str .=  $string.MY_EOL; continue; }
			$setting_str	.=  str_pad( $string, $setting_length+ $setting_length). '# '.str_replace($from,$to,$arr['values']).MY_EOL; continue; 
		}
	} // eo for arr
} // eo foreach setting
#echo $setting_str;
if ($wp == 'WD' || $wp == 'CW' ) {
	$setting_str	.= '#
#---------------------------------------------------------
# COMPATIBILLITY     for WeatherDisplay / consoleWD users
# set to true ONLY if it is ABSOLUTELY  necessary to use testtags.php from Saratoga or Leuven
#---------------------------------------------------------
$SITE["use_testtags"]   = false;  
';
}       
$setting_str	.= '#
#---------------------------------------------------------
# IMPORTANT     will you be uploading to the default upload folder (uploadXX) where xx is the short code for your weather program
#---------------------------------------------------------
#
$SITE["standard_upload"]= true;
#
#       If you do not want or are not able to upload to the default folder set the correct upload folder here
#     
#$SITE["uploadDir"]	= "../";        	# example for upload to root
#$SITE["clientrawDir"] 	= "../";
#$SITE["graphImageDir"] = "../";
#
#---------------------------------------------------------
$SITE["tpl_version"]    = "2.80";
#---------------------------------------------------------
# If you add an language add the new language code to this array
#---------------------------------------------------------
#
$SITE["installedLanguages"] = array (
"nl" => "Nederlands",
"en" => "English",
"fr" => "Fran&ccedil;ais",
"de" => "Deutsch",
);
';
echo ' => OK </h4>'.PHP_EOL;

$file = 'cache/wsUserSettings.php';
if (!file_put_contents($file, $setting_str) ) {
	$echo 	= '<h3> ERROR cache not writable  config program ends  </h4></div>';
	return false;
}
else {  echo '<br /><h4 style="">'.langtransstr('Settings-file saved as').' '.$file.' => OK </h3>'.PHP_EOL;
}
$file = 'cache/wsUserSettings.txt';
if (!file_put_contents($file, $setting_str) ) {
	$echo 	= '<h3> ERROR cache not writable  config program ends  </h3></div>';
	return false;
}
else {  echo '<br /><h4 style="">'.langtransstr('The settings-file can be checked on-line here ')
		.'  => <a href="'.$file.'" target="_blank"><b> '.$file.' </b></a></h4>'.PHP_EOL;
}
$gzdata = gzencode($setting_str, 9);
$file = 'cache/wsUserSettings.php.zip';
if (!file_put_contents($file, $gzdata) ) {
	$echo 	= '<h3> ERROR cache not writable  config program ends  </h3></div>';
	return false;
}
else {  echo '<br /><h4 style="">'.langtransstr('Settings-file can be downloaded from here ')
		.'  => <a href="'.$file.'" target="_blank"><b> '.$file.' </b></a></h4>'.PHP_EOL;
}

echo '<br /><p style="float: right;">';
if ($this_grp <> '9999') {
	echo '
  <input type="submit" name="config[again]" class="button" 	value="'.langtransstr('Continue where we were').'">';
}
echo '
  <input type="hidden" name="config[next_grp]" 		    	value="'.$next_grp.'">
  <input type="hidden" name="config[prev_grp]" 		    	value="'.$prev_grp.'">
  <input type="hidden" name="config[this_grp]" 		    	value="'.$this_grp.'">
  <input type="submit" name="config[start]"  class="button" 	value="'.langtransstr('Back to start').'">  
  <input type="submit" name="config[stepb]"  class="button" 	value="'.langtransstr('Back one page').'">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="config[generate]"  class="button" 	value="'.langtransstr('Generate settings file').'"> 
</p>
</form>
<p style="padding-left: 10px;"><br /><small>'.$footer.'</small></p>
</div>
</div>';
# ----------------------  version history
# 0.02 2015-09-07 release 2.8 version 
