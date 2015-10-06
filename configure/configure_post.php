<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
# display source of script if requested so
   $filenameReal = __FILE__;
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
$pageName	= 'configure_post.php';
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

if ($conf_debug) {echo '<pre> post array ='.PHP_EOL; print_r ($_POST); echo '</pre>';}
$count_errors	= 0;
$post_skip	= array ('submit','next_grp','prev_grp','this_grp','xx');
$check_skip	= array ('select', 'selectProv', 'selectCity', 'selectWN', 'selectAQ', 'trueFalse','tz','xx');	
foreach ($_POST['config'] as $key => $value)  {
#echo '<pre> $key = '.$key.' $value = '.$value;
	if (in_array($key, $post_skip) ) {
#echo 'skipped </pre>'.PHP_EOL;
	        continue;
	}		// skip al none key items
#	 echo 'halt2'; exit;
	$arr_field	= $form[$key];
	$setting	= $arr_field['setting'];	
	$field_type	= $field_typeXX	= $arr_field['type'];
	$field_type	= str_replace ('region','',$field_typeXX);
	if ($field_type <> $field_typeXX) {
		$field_type	= lcfirst($field_type);
	}
	$error_text	= '';
	$is_OK		= true;
#echo ' $field_type = '.$field_type.' $setting = '.$setting;
	if (!in_array($field_type, $check_skip) ) {
#echo ' => not in-array $check_skip'.PHP_EOL;
		switch (true) {
			case ($field_type == 'selectRegionPlus'): 
				if ($value == 'false') {$value = false;}	// this is a OK value in this case!
			break;
/*			case ($field_type == 'numberNoDecimal'):
				$result	= 1*$value; 
				if (is_integer($result) && (string) $result === (string) trim($value)) {break;}
				$error_text .= langtransstr('only integers allowed');
				$is_OK  = false;
			break; */
			case ($field_type == 'allcap' ):
				$value	= strtoupper($value);
				$is_OK	= check_strlen($value,$arr_field['values']);
			break;
			
			case ($field_type == 'htmltext'):	break;		// encap html chars ?
			case ($field_type == 'numberDecimal'): 	
				$value	= str_replace(',','.',$value);
				$is_OK 	= check_range($value,$arr_field['values']);
			break;		// check if numeric  check if in range
			case ($field_type == 'noDecimal'): 	
				$is_OK 	= check_range($value,$arr_field['values']);
				if ( $is_OK == false ) {break;}
				if (round(1.0*$value) <> 1.0*$value) {
					$error_text .= langtransstr('only integers alowed, no decimals');
					$is_OK 	= false;
				}
			break;		// check if numeric  check if in range
			
			case ($field_type == 'email'):		
				if (trim($value) == $settings[$process][$key]['old']) {
					$error_text .= langtransstr('needs a valid email address');
					$is_OK = false;
				}
			break;
/*			case ($field_type == 'selectAQ'):
				list($en_aq,$fr_aq) = explode ('#',$value);
				$nr 	= $key+1;
				$settings[$process][$nr]['new']	= $fr_aq;
				$value	= $en_aq;
			break;
			case ($field_type == 'selectWN'):
			        list ($wn_code,$wn_name,$wn_url) = explode ('#',$value.'###');
			        $nr 	= $key+1;
				$settings[$process][$nr]['new']	= $wn_name;
				$nr 	= $key+1;
				$settings[$process][$nr]['new']	= $wn_url;
			        $value  = $wn_code;
			break;
*/			default: 
				$echo 	= '<h3> error field_type '.$field_type.' unknown  </h3>'; 
				return false; 
			break;
		} // eo switch
	} // eo if check
	$form[$key]['new']	= $value;
	if ($is_OK <> true) {
		$form[$key]['error'] = $error_text;
		$count_errors++;
	} 
	else {	$settings[$process][$key]['new'] = trim($value);
		if ($process == '00') {
			$settings[$setting]	= trim($value);
		}
		$form[$key]['error'] = '';
	}	
} // eo for each
unset ($_POST['config']);
$return = true;
if ($count_errors == 0) {
	$settings['process']	= $next_grp;
	$return = conf_to_cache();
}
#echo '<pre>$this_grp = '.$this_grp.'</pre>';
return $return;
#
# --------------------------------------------------------------------------------------
function check_strlen($value,$length) {
	global $error_text;
#echo '$value = '.$value.' - $length = '.$length; exit;	
	if (trim($length) == '' ) {return true;}
	$length = (int) trim($length);
	if (strlen ($value) <> $length ){
		$error_text .= langtransstr('number of characters should be '.$length);
		return false;
	}
	return true;
}
function check_range($value,$range) {
	global $error_text, $region;
#
	$value	= trim($value);
	if (!is_numeric($value) ) 	{$error_text .= langtransstr('numeric values only'); return false;}
	if (trim($range) == '') 	{return true;}
	$arr	= explode ('!',$range.'!');
	for ($n = 0; $n < count ($arr); $n++) {
		$string	= trim($arr[$n]);
		if ($string == '') 	{continue;}
		list ($lowest,$highest,$region_check) = explode ('#',$string.'#');
		$region_check	= trim($region_check);
		if ($region_check == '' || $region_check == $region) {break;}
	}
	$lowest		= trim($lowest);
	$highest	= trim($highest);
	if (!is_numeric($lowest) || !is_numeric($highest)) {
		echo '<h3> invalid settings for field check range: '.$range.'  </h3>';
		exit;
	}
	if ($value < $lowest || $value > $highest) {
		$error_text .= langtransstr('value range allowed').' '.$lowest.'&gt;= <i style="color: red;">'.$value.'</i> =&lt;'.$highest; 
		return false;
	}
	return true;
} // eof check_range
