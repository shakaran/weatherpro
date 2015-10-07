<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsLangFunctions.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>"; exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
#-----------------------------------------------------------------------
# 3.00 2014-09-12 release version
# ----------------------------------------------------------------------
# no output sent until this time, so no echo allowed, use pathString instead
$pathString.= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
$lang_start	= '<!-- module '.$pageName;
#-----------------------------------------------------------------------------------------
#	mostly  based  on the splendid work of Ken True  Saratoga.org 
# 	language translate functions from "english" or codes to desired language 
#--------------------------------------------------------------------------------------------------
#	Main code exceuted when the script is loaded
#--------------------------------------------------------------------------------------------------
$LANGLOOKUP 	= array();				// array with FROM and TO languages
$missingTrans	= array();				// array with strings with missing translation requests
$dir 		= $SITE['langDir'];			// default lang files are here
if (isset ($SITE['textLowerCase']) && $SITE['textLowerCase']) {$langLower = true;} else {$langLower = false;}
langMergeFile ( $dir);					// load / merge lang files
return;
#-----------------------------------------------------------------------------------------
#	function langtrans    translates and echos a string
#-----------------------------------------------------------------------------------------
function langtrans ( $item ) {
	$trans  = langtransstr ( $item ); 
	echo $trans;
}  // eof langtrans  Translate and echo              
#-----------------------------------------------------------------------------------------
#	function langtransstr    translates  a string
#-----------------------------------------------------------------------------------------
function langtransstr ( $item ) {
	global $LANGLOOKUP,$missingTrans, $langLower;
	if ($langLower) {$string = trim(strtolower($item));} else {$string = trim( $item );}
	if (isset($LANGLOOKUP[$string])) {		// did we have a translation
		$string = $LANGLOOKUP[$string];
		return $string;
	}
	if (!$langLower) {				// try lowercase input
		$string = trim(strtolower( $item ));
		if (isset($LANGLOOKUP[$string])) {
			$string = $LANGLOOKUP[$string];
			return $string;
		}
	}
	if(isset($string) and $string <> '') {		// no translation found
		$string = trim($item);
		$missingTrans[$string] = true; 
	}
	return $string;
} // langtransstr Translate string
#-----------------------------------------------------------------------------------------
#	function langMergeFile    adds a file with langlookup entries to existing langlookup array
#-----------------------------------------------------------------------------------------
function langMergeFile ( $dir ) {		// $item point to the DIR where the file should be located
	global $SITE, $LANGLOOKUP, $missingTrans, $pathString,$lang_start,  $lang, $wsDebug;
	$charset_from   = $charset_to   = $SITE['charset'];
	$nLanglookup    = 0;	
	if ($wsDebug) { $pathString .=	$lang_start.' ('.__LINE__.'): adding files in '.$dir.' to language translation array  -->'.PHP_EOL; }
	$langfile       = $dir.'wsLanguage-' . $lang.'.txt';
	if (! file_exists($langfile) ) {	// there is no language file for this language
		$pathString     .= $lang_start.' ('.__LINE__."): langfile ($langfile) does not exist -->".PHP_EOL;					
		$langfile 	= $dir.'wsLanguage-'.$SITE['langBackup'].'.txt'; // than try site language 
		if (! file_exists($langfile) ) {
			$pathString.= $lang_start.' ('.__LINE__."):  ($langfile) does not exist either <br /> languagesupport failed -->".PHP_EOL;
			return 0; 
		} 
	}  
	$lfile 		= file($langfile);	// file exist, so read it into area
	if ($wsDebug) { $pathString 	.= $lang_start.' ('.__LINE__."): langfile '$langfile' loading -->".PHP_EOL; }
	#
	# make url / filepath for local language file f.e. wsLanguage-nl-local.text
	#
	# now we check our own modifications
	$dir            = '_my_texts/';
	$langfile 	= $dir.'wsLanguage-' . $lang .'-local.txt';
	if (file_exists($langfile)) { 	
		$lfile2  	= file($langfile); 	// file exist, so read it into area
		if ($wsDebug) { $pathString.= $lang_start.' ('.__LINE__."): langfile '$langfile' loading -->".PHP_EOL;}
		$lfile 		= array_merge($lfile,$lfile2);  // and merge the two files
	} else {
		$pathString.= $lang_start.' ('.__LINE__."): local langfile '$langfile' does not exist -->".PHP_EOL;
	}
	$nLanglookup = 0;				// number of entries
	if (isset ($SITE['textLowerCase']) && $SITE['textLowerCase']) {$lower = true;} else {$lower = false;}
	foreach ($lfile as $rec) { 			// process the language file
		$recin = trim($rec);
		if (substr($recin,0,1) <> '#' && $recin <> '') { // only process non blank, non comment records
			list($type, $item,$translation) = explode('|',$recin . '|||||');
			switch ($type) {
				case 'langlookup':
				        $item           = trim($item);
				        $translation    = trim($translation);
					if  ( ($item <> '') && ($translation <> '') ) {
					        $translation  = iconv ($charset_from, $charset_to.'//TRANSLIT',$translation);
                                                if ($lower) {
                                                        $translation	= trim(mb_strtolower($translation,$charset_to ));
                                                        $item 		= trim(strtolower($item));
                                                }						
                                                $LANGLOOKUP[$item]  = $translation;
                                                $nLanglookup++;
					}
					break;
				case 'admin':	
					if ($item && $translation) {
						$SITE['wsModules'][$item] = 'version: ' . $translation;
						$pathString.= $lang_start.' ('.__LINE__.'): module '.$item.' ==== '.$SITE['wsModules'][$item]." -->".PHP_EOL;
					break;
					}
				case 'charset':
					if (trim($item) == $charset_from) {break;}  // charset already correct
				        $pathString.= $lang_start.' ('.__LINE__.'): charset of file being loaded switched from  '.$charset_from.' to '.$item.' -->'.PHP_EOL;
				        $charset_from   = trim($item);
					break;
				default:
				        break;
			}  // eo switch input type
		} // end if nonblank, non comment
	
	} // end foreach entry in input files
	unset ($lfile );
	unset ($lfile2);
	if ($wsDebug) {
		$pathString .= $lang_start.' ('.__LINE__."): loaded $nLanglookup langtrans entries -->".PHP_EOL;
		$pathString .= $lang_start.' ('.__LINE__."): load_langtrans finished -->".PHP_EOL; 
	}
	return $nLanglookup;
}
#------------------------------------------------------------------------------------------
#	function print_language_selects   dropdown menu with supported languages
#--------------------------------------------------------------------------------------------------
function print_language_selects($key='') {
	global $SITE;
	$use_onchange_submit = true;
	$string1 = '';
	$arr = $SITE['installedLanguages'];
	if (!is_array($arr)){return;}
	$string = '	<form method="get" name="lang_select" action="index.php" style="padding: 0px; margin: 0px">'.PHP_EOL;
	$string .= '	<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" value ="'.$key.'"/>'.PHP_EOL;
	$string .= '	<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="wp" value ="'.$SITE['WXsoftware'].'"/>'.PHP_EOL;
	$string .= '	<span style="font-size: 10px">' . langtransstr('Language') .':&nbsp; </span>'.PHP_EOL; 
	$string .= '	<select id="lang" name="lang"  style="font-size: 9px" onchange="this.form.submit();">'.PHP_EOL;
	$flag = '';
	foreach ($arr as $k => $v) {
		if($SITE['lang'] == $k) {
			$selected = ' selected="selected"';
			$flag = '	<img src="'. $SITE['imgDir'] . 'flag-'. $k .'.gif" alt="'. $v .'" title="'. $v .'" style="padding: 0px; border: 0px; margin: 0px" />'.PHP_EOL;
		  } else {
			$selected = '';
		  }
	$string .= '	    <option value="'.$k.'"'.$selected.'>'.$v.'</option>'.PHP_EOL;
	} // end foreach
	$string .= '	</select>'.PHP_EOL;
	if($SITE['langFlags'] == true) {
		$string .= $flag;
	}   
	$string .= '	</form>'; 
	return $string;
}// end print_language_selects_dropdown function