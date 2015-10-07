<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'configure_func.php';
$pageVersion	= '0.00 2015-06-23';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.00 2015-06-23 beta version
# ----------------------------------------------------------------------
#
$disp_coloms	= 2;
#
echo '<script type="text/javascript">
 function selectChange(selectObj,resultObj) {
   var selectIndex=selectObj.selectedIndex;
   var selectValue=selectObj.options[selectIndex].text;
   var output=document.getElementById(resultObj);
   //alert(output.innerText);
   output.innerHTML=selectValue;
 }
</script>
';
#
/*function conf_link($setting) {
	global $conf_here;
	if ($text == '') {$text = $conf_here;}
	return '<button type="button"><a href="'.$link.'" class="urlextern" target="_blank" title='.$link.'rel="nofollow">'.$text.'</a></button>';
}
*/
function conf_to_cache() {
	global $settings, $cachefile;
	$string = serialize($settings);
	if (!file_put_contents($cachefile, $string)){
		$echo 	= '<h3> ERROR cache not writable  config program ends  </h3>';
		return false;
	}
	return true;
}
function load_old_site() {
	global $oldSettings,  $oldSite ;
	$translate	= array();
	$translate['webcamName']	= 'webcamName_1';
	$translate['webcamImg']		= 'webcamImg_1';
	$translate['warningTxt']	= 'maintenanceShow';
	$translate['wrnRain']		= 'showRain';
	$translate['wrnLightning']	= 'showLightning';
	$translate['mobileSite']	= 'useMobile';
	$translate['yrnoIconsOwn']	= 'metnoicons';
	$translate['wuHistPage']	= 'wuMember';
	$translate['location']		= 'yourArea';
	$translate['useLanguageFlags']	= 'langFlags';
	$translate['allowLanguageSelect']= 'userChangeLang';
	$translate['ewnID']             = 'ewnMember';
	$translate['poolTemp']          = 'extraTemp1';
	$translate['bottomDysplay']     = 'bottomDisplay';
	$translate['statsCode']         = 'statsCodeTxt';
	$translate['webcamSide']        = 'webcam';
	$translate['wcloud']            = 'wcloudID';
	$translate['cwopId']            = 'cwopID';
        $translate['wuId']              = 'wuID';
        $translate['fcstOrg']           = 'fctOrg';
        
	$extraID['wuID']        = 'wuMember';
	$extraID['anWeID']      = 'anWeMember';
	$extraID['mesoID']      = 'mesonetMember';
	$extraID['hwaID']       = 'hwaMember';
	$extraID['wowID']       = 'wowMember';
	$extraID['cwopID']      = 'cwopMember';
	$extraID['awekasID']    = 'awekasMember';
	$extraID['wcloudID']    = 'wcloudMember';
	$extraID['pwsID']       = 'pwsMember';
	$extraID['weatherlinkID']= 'wl_comMember';
	$extraID['webcamSide']  = 'webcam';
	$extraID['webcamImg_2'] = 'webcam_2';
	$extraID['statsCodeTxt']= 'statsCode';
	$extraID['MeteoplugPage']= 'meteoplug';
	$extraID['webcamImgNight_1']= 'webcamNight';
	
	$uomsConvert	= array ('uomTemp', 'uomBaro',  'uomWind', 'uomRain', 'uomSnow', 'uomDistance', 'uomPerHour', 'uomHeight' );
$CHECK['uomTemp'] 	= array(' ', '&deg;C', 	'&deg;F');
$CHECK['uomBaro'] 	= array(' ', ' hPa',    ' inHg', ' mb' );
$CHECK['uomWind'] 	= array(' ', ' km/h',  	' mph', ' kts', ' m/s',	);
$CHECK['uomRain'] 	= array(' ', ' mm',	' in' );
$CHECK['uomSnow'] 	= array(' ', ' cm',	' in' );
$CHECK['uomDistance'] 	= array(' ', ' km',  	' mi');
$CHECK['uomPerHour'] 	= array(' ', ' mm',	' in' );
$CHECK['uomHeight'] 	= array(' ', ' m',	' ft' );      
	$isFalse 	= array(
'DavisVP','SOLAR','UV','WXsoftwareShow','webcam',
'ajaxGizmoShow','anWeID','bannerBottom','bannerTop','banners','belgium','bottomDisplay','commaDecimal',
'contactPage','cookieSupport','cwopPage','donateButton','ecPage','equipment','ewnID','ewnMember','extraTemp1','extraTemp2','floatTop','geoKey','hwaIconsOwn','hwaPage','langFlags','leafUsed',
'maintenanceShow','mesoID','meteoplug','meteoware','metnoPage','mobileSite','multi_forecast','mwPage','netherlands','noaaIconsOwn','noaaPage','otherWS','partners','poolTemp','pcShow','providerShow','showVisitors',
'sideDisplay','skipTop','socialSiteSupport','soilUsed','soilPage','stationShow','statsCode','stripAll','stripMenu','tempInside','tempSimple','textLowerCase','useCurly','useMobile',
'userChangeChoice','userChangeColors','userChangeDebug','userChangeForecast','userChangeHeader','userChangeLang','userChangeMenu','userChangeUOM',
'warn1Box','warnPage','warningGreen','warningInside','warningsEuro','warningsNOAA','warningsNWS','warningsXtnd','warningsec','warnings','wp24ID',
'wd_live','wdlPage','webcamPage','webcamSide','worldPage','wrnLightning','wrnRain','wuIconsCache','wuIconsOwn','wuPage','wxsimPage','yahooIconsOwn','yahooPage','yrnoPage','wxsimIconsOwn',
);
	$isSkip  	= array('charset' ,'topfolder' ,'password' , 'standard_upload', 'pageWidth', 'menuWidth');	
	$arr		= file($oldSettings);
#echo '<pre>'; print_r($arr); exit;
	$count 		= count ($arr);
	for ($n1 = 0; $n1 < $count; $n1++) {
		$line	= $arr[$n1];
		if (substr($line,0,1) <> '|') 	{continue;}
		list ($none,$key,$value) = explode ('|',$line);
		if ($key == '') 		{continue;}
		if (in_array($key,$isSkip) ) 	{continue;}
		if (in_array($key,$isFalse)  ) {
			if      ($value == '1') 		  	{$value = 'true';}
			elseif  ($value === '' || $value == '0')  	{$value = 'false';}
#			elseif  ($value <> '')                          {$value = 'true';}
		}
		if (isset ($translate[$key]) ){$key = $translate[$key];}
		if 	($value == 'yes') {$value = 'true';}
		elseif  ($value == 'no')  {$value = 'false';}
		if (in_array ($key, $uomsConvert) ) {
echo '<!-- '.$key.' in: '.$value.' out: ';
			$result = 1;
			for ($n2 = 1; $n2 < count($CHECK[$key]); $n2++) {
				if ($value == $CHECK[$key][$n2]) {$result = $n2; break;}
			}
			$value = $result;
echo $result.' -->'.PHP_EOL;
		}
		$oldSite[$key]	= $value;
		if (isset($extraID[$key]) ) 	{
		        $newKey         =  $extraID[$key];
		        if ($value <> 'false' && $value <> '') {
		                $oldSite[$newKey]= 'true';
		        }
		        elseif ($value == 'false' || $value == '') {
		                if (!isset ($oldSite[$newKey]) ) {
		                        $oldSite[$newKey] = 'false';
		                }
		        }
		
		}
# and some extra code here
                if ($key == 'banners' && $oldSite['banners'] == 'false' ){
                        $oldSite['bannerTop']   = $oldSite['bannerBottom'] = 'false';
                }
 	}
	if (count($oldSite) > 1) {return true;} else {return false;}
}

function tr_setting($key, $arr) {
	global  $wp, $region, $config_folder, $save_province, $SITE, $disp_coloms, $LINKLOOKUP;
	if ($arr['wp'] 	   <> '--' && $arr['wp']     <> $wp) 	// skip lines for other weatherprogram	
		{return;} 
	if ($arr['region'] <> '--' && $arr['region'] <> $region)// skip lines for other region	
		{return;}
	if ($arr['type'] == 'none') 				// skip no use lines
		{return;}					
	if ($arr['type'] == '##') {
		$arr['old']	= langtransstr($arr['old']);
		echo '
	<tr class="headerline2"><td class="headerline2" colspan="'.$disp_coloms.'">'.$arr['old'].'</td></tr>';	
		return;
	}
	if ($arr['type'] == '#') {
		$arr['old']	= langtransstr($arr['old']);
		echo '
	<tr class="headerline1"><td>&nbsp;</td><td class="headerline1">'.$arr['old'].'</td></tr>';	
		return;
	}
	$setting	= $arr['setting'];
	$text		= 'conf-'.trim($setting);
	$link		= 'link-'.trim($setting);
	$text_trans     = langtransstr($text);
	if ($text == $text_trans) {$text_trans = 'explain text for '.$setting.' will be added shortly';}
#echo '<pre>'; print_r($LINKLOOKUP); exit;
	if (isset ($LINKLOOKUP[$link]) ) {
		$text_trans     .= '<br />
<a href="'.$LINKLOOKUP[$link].'" class="urlextern" target="_blank" title="'.$link.'" rel="nofollow">'.langtransstr($link).'</a>';
	}
	$field_type	= $field_typeXX	= $arr['type'];
	$value		= $arr['new'];
	$value_old	= $arr['old'];
	$field_type	= str_replace ('region','',$field_typeXX);
	if ($field_type <> $field_typeXX) {
		$field_type	= lcfirst($field_type);
		$arrOld	= explode ('!',$value_old.'!');
		foreach ($arrOld as $keyOld => $val_regOld) {
			list ($value_old, $regionOld) 	= explode ('#',$val_regOld.'#');	
			if (($regionOld == $region) || ($regionOld == '')  || ($regionOld == 'all') || ($regionOld == '--')  ) {break;}
		} // eo foreach 
	} 
	if  	( ($value_old == 'true') && ($value === true) )	{$value = $value_old;} 
	elseif  ( ($value_old == 'false')&& ($value === false)) {$value = $value_old;}
	$class = '';
	if 	($value == '')		{$value	= $value_old; $class = 'default';} 
/*	if 	($value == $value_old) 	
		{$class = 'default';}    
	else  	{$class = '';} */
	$values		= $arr['values'];
	
	if (isset ($arr['error']) ) 	{$error = $arr['error'];} else  {$error = ''; }
	
	if ($error <> '') 	{$border = ' style="border-color: red; border-width: 2px;"';}  else {$border = '';}
	echo '
    <tr class="">
      <td class="label">'.$text_trans.'</td>
      <td class="value" '.$border.'><span class="outkey">'.$setting.'</span>';
# ----------------------------      
	if ($field_type == 'select') {
		echo '
        <div class="input '.$class.'" ><!-- '.$field_type.' with value = '.$value. ' -->
	  <select class="edit  '.$class.'" id="config__'.$key.'" name="config['.$key.']">';
		$arr_values 	= explode ('!',$values);
		foreach ($arr_values as $none => $string) {
			list ($short,$long,$optional) = explode ('#',$string.'#');
			$optional	= trim($optional);
			if ($optional <> '') {
				$ok = array($region, 'all', '--');
				if (!in_array ($optional, $ok ) ) {continue;}
			}
			if ($value == $short) {$selected = 'selected="selected"';} else {$selected = '';}
			$long	= langtransstr($long);
			echo '
	     <option value="'.$short.'" '.$selected.'>'.$long.'</option>';
		}
            	echo '
          </select>  
        </div>';
	}
# ----------------------------
	elseif ($field_type == 'selectProv') {
		$save_province	= $value;
		echo '
        <div class="input"><!-- '.$field_type.' with value = '.$value. ' -->
	  <select class="edit '.$class.'" id="config__'.$key.'" name="config['.$key.']" onchange="show_cities(this);">';
		$arr_values 	= explode ('!',$values);
		foreach ($arr_values as $none => $string) {
			list ($short,$long) = explode ('#',$string);
			if ($value == $short) {$selected = 'selected="selected"';} else {$selected = '';}
			echo '
	     <option value="'.$short.'" '.$selected.'>'.$long.'</option>';
		}
            	echo '
          </select>  
        </div>';
	}
# ----------------------------
	elseif ($field_type == 'selectCity'){
		echo '
<script type="text/javascript">
function show_cities(selectObj) {
	var selectIndex=selectObj.selectedIndex;
   	var selectValue=selectObj.options[selectIndex].value;
 	var elements = document.getElementsByClassName("provinces");
  	var numelements = elements.length;
  	for (var index=0;index!=numelements;index++) {
 		var element = elements[index];
     		element.style.display="none";
     		element.disabled = true;
	}
	var element = document.getElementById("ca_" + selectValue);
	element.style.display="block";
	element.disabled = false;
 }
</script>';
		include_once $config_folder.'ca_province.php';
		$old_prov 	= '';
		$close	= false;
		echo '
	<div class="input"><!-- '.$field_type.' with value = '.$value. ' -->';
		foreach ($arr_prov_code as $none => $codes) {
			list($prov,$city,$code) = explode ('#', $codes);
			if ( ($prov <> $old_prov) && ($close == true) ) {
				echo '
		  </select>';	 
			}
			if ($prov <> $old_prov)  {
				$old_prov = $prov;
				$close = true;
				if ($prov == $save_province) 
				      {	$display = '"display: block;"';
					$select	 = $class;
				      } 
				else  {	$display = '"display: none;" disabled ';
					$select	 = '';}
				echo '
		  <select class="edit provinces '.$select.'" id="ca_'.$prov.'" name="config['.$key.']" style='.$display.'>';
			}
			if ($value == $code) {$selected = 'selected="selected"';} else {$selected = '';}
			echo '
		     <option value="'.$code.'" '.$selected.'>'.$city.'</option>';
		} // eo foreach
        	echo '
          </select>  
        </div>'; 
	}
# ----------------------------
	elseif ($field_type ==  'selectAQ') {
		$skipAQHIdisplay	= true;
		include_once $config_folder.'ca_AQHI_list.php';
		echo '
        <div class="input"><!-- '.$field_type.' with value = '.$value. ' -->
	  <select class="edit '.$class.'" id="config__'.$key.'" name="config['.$key.']">';
	  	$old_group = '';
		foreach ($region_array as $key => $arr_values) {
			$answer = $arr_values['region_name']['en'].'#'.$arr_values['region_name']['fr'].'#'.$arr_values['region_code'];
			$choice	= $arr_values['region_name']['en'];
			$group	= $arr_values['zone_name']['en'];
			list ($value_left) = explode ('#',$value.'#');
			if ($value_left == $choice) {$selected = 'selected="selected"';} else {$selected = '';}
			if ($group <> $old_group) {
				$old_group = $group;
				echo '
            <optgroup label="'.$group.'">';
			}
			echo '
                <option value="'.$answer.'" '.$selected.'>'.$choice.'</option>';
				
		} // eo for each
            	echo '
          </select>  
        </div>';		
	}
# ----------------------------		
	elseif ($field_type == 'selectWN') {
		echo '
        <div class="input"><!-- '.$field_type.' with value = '.$value. ' -->
	  <select class="edit '.$class.'" id="config__'.$key.'" name="config['.$key.']">';
                include 'glo/meso_arr.php';
# echo '<pre>'.print_r($meso_nets,true); exit;
		foreach ($meso_nets as $none => $arr) {
			if ( $arr['wn_region'] == $region) {
			        $wn_code        = $arr['wn_code'];
				if ($value == $wn_code) {$selected = 'selected="selected"';} else {$selected = '';}
				$long	        = $wn_code.'-'.$arr['wn_name'];
				echo '
	     <option value="'.$wn_code.'" '.$selected.'>'.$long.'</option>';
	     		}
		}
            	echo '
          </select>  
        </div>';	
	}
# ----------------------------
	elseif ($field_type == 'trueFalse') {
		if ($value == 0)	{$false = 'checked="checked"';   $true  = ''; $class_f  = $class; $class_t  = '';} 
		else 			{$true  = 'checked="checked"';   $false = ''; $class_f  = '';     $class_t  =  $class;} 
		echo '
        <div class="input"><!-- '.$field_type.' with value = '.$value. ' -->
	   	<input  type="radio" name="config['.$key.']" value="1" '.$true.'><span class= "'.$class_t.'">&nbsp;true</span></br>
	   	<input  type="radio" name="config['.$key.']" value="0" '.$false.'><span class= "'.$class_f.'">&nbsp;false</span>
        </div>';
		
	}
# ----------------------------
	elseif ($field_type ==  'tz'){
		include $config_folder.'tz.php';
		switch ($region) {
			case 'europe':  $timezone = timezone_europe(); break;
			case 'america': $timezone = timezone_america(); break;
			case 'canada':  $timezone = timezone_canada(); break;
			default:        $timezone = timezone_other(); break;
		} 
		echo  '
        <div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
	  <select class="edit" id="config__'.$key.'" name="config['.$key.']">';
		$count	= count($timezone);
		foreach ($timezone as $xx => $arr_tz) {	#	print_r ( $arr_tz);
			if ($count == 1 ){$name = '';} else {$name = $arr_tz['name'].' - ';}
			foreach ($arr_tz['timezones'] as $tz_code => $tz_utc) {
				if ($value == $tz_code) {$selected = 'selected="selected"';} else {$selected = '';}
				echo '
	     <option value="'.$tz_code.'" '.$selected.'>'.$name.'('. $tz_utc.')&nbsp;'.$tz_code.'</option>';
			}
		}
		echo '
          </select>  
        </div>';
	}
# ----------------------------
	elseif ($field_type ==  'email') {
		echo '
	<div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
          <input id="config__'.$key.'" name="config['.$key.']" type="email" class="edit" value="'.$value.'">
        </div>';		
	}
# ----------------------------
	elseif ($field_type ==  'noDecimal'){
		echo '
	<div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
          <input id="config__'.$key.'" name="config['.$key.']" type="text" class="edit" value="'.$value.'">
        </div>';		
	}
# ----------------------------
	elseif (  $field_type  == 'htmltext' || $field_type == 'numberDecimal'  ||
		  $field_type  == 'allcap'  ) {
		echo '
	<div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
          <input id="config__'.$key.'" name="config['.$key.']" type="text" class="edit" value="'.$value.'">
        </div>';
        }
# ----------------------------
	else {	echo ' 
 	<div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
          <input id="config__'.$key.'" name="config['.$key.']" type="text" class="edit" value="INVALID FIELDTYPE '.$field_type.' - '.$value.'">
        </div>';
        } // eo if list
	if ($error <> '') {echo $error;}    
	echo '
      </td>
    </tr>';
}
