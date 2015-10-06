<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
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
#-----------------------------------------------------------------------
# just to know which script version is executing
#-----------------------------------------------------------------------
$pageName	= 'wsMenuLoad.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.02 2015-05-06';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.02 2015-05-25   release version
#-----------------------------------------------------------------------------------------
$menuArray	= array();				// array with all possible menuchoices
$menuData	= $SITE['menuXml'];			// here we find the xml file	
$pathString	.= '<!-- module wsMenuLoad.php ('.__LINE__.'): loading '.$menuData.'  -->'.PHP_EOL;				
$string 	= file_get_contents($menuData);  	// load menu xml file into string
$xmlStr 	= new SimpleXMLElement($string);	// make xml object
#
$pageFile	= $menuData;
$pageVersion= (string)$xmlStr->dateVersion;
$SITE['wsModules'][$menuData] = 'version: '.(string)$xmlStr->dateVersion;  // save some info
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
$level		= 1;								//number of LI tabs to have clean looking output
wsMenuGen 	($xmlStr);							// iterative routine to create main li's en lower level  ul/li's
#unset 		($xmlStr);							// get rid of the xml
#return;
#
#--------------------------------------------------------------------------------------------------
#  iterative fuction to generate li and ul/li; when the array with menuchoices etc is build
#--------------------------------------------------------------------------------------------------
function wsMenuGen ($data, $hide=false) {								
	global $DropdownMenuText, $level, $SITE, $menuArray, $extraP, $lang, $pathString;	
	foreach ($data->item as $menuItem) {
		$Show   = true;			                // default every xml-menu setting is shown
		$hide_me=$hide;
		if (isset($menuItem['show'])) {
# show can be (1) 'yes/'no' or (2) true/false or (3) a reference to a $SITE setting which can be  (1) 'yes/'no' or (2) true/false (3)
			if ($menuItem['show'] == false) {
				continue;
			} 
			if  (trim($menuItem['show']) == 'no') {
				continue;
			}
			$string = strval($menuItem['show']);
			if (isset($SITE[$string])) {
					if ($SITE[$string] === false || $SITE[$string] === 'no') {continue;}
			}  // eo check sitestring
		}            // eo check if this menu item be shown to the visitor
		for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.="\t";}   	// number of tabs
		if (isset ($SITE['skipTop']) && $SITE['skipTop']) {
		        $top = '#data-area';
		} else    {$top = '';
		}
		if (isset ($menuItem['top']) ) {
		        $string = trim($menuItem['top']);
		        if ($menuItem['top'] == false) 
		                {$top = '';} 
		        elseif ($string == 'no') 
		                {$top = '';}
		        else    {$top    = '#'.$string;}
		}
		if (!isset ($menuItem['title']) ) {
		        $menuTitle =  ''; #          $menuItem['caption'];
		} else {
		        $menuTitle = $menuItem['title'];
		}
		$img    = '';
		if (isset($menuItem['img']) ){
		        $img    = trim((string) $menuItem['img']);
		        if ($img  == 'updated') {$img= 'img/menu_updated.gif';}
		        elseif ($img  == 'new') {$img= 'img/menu_new.gif';}
		        $img = ' <img src ="'.$img.'" alt=" ">';
		}
		if (isset($menuItem['hide']) ){	 $hide_me= true;}
		if (!isset($menuItem['link'])) {
		        if ($hide_me <> true) {								// submenuheaders have no link specified
                                $DropdownMenuText.=
                                '<li class="withImg">
                                <a href="#" title="'.langtransstr($menuTitle).'">'.
                                langtransstr($menuItem['caption']).$img.
                                '</a>'.PHP_EOL;
                                for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.='	';} // number of tabs
                                $DropdownMenuText.='	<ul>'.PHP_EOL;						// new ul group
                                $level= $level + 1;
                        }											// extra tab in new level
                                $data2 = $menuItem;											// 
				wsMenuGen ($data2, $hide_me);    	// this function calls itself for nested menu levels
                        if ($hide_me <> true) {	
                                for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.='	';}
                                $DropdownMenuText.='</ul>'.PHP_EOL;								// end new ul group
                                $level = $level - 1;										// back 1 level
                                for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.='	';}
                                $DropdownMenuText.='</li>'.PHP_EOL;
                        }
		} 
		elseif (isset($menuItem['external']) ){
#  echo '<pre>'.print_r($menuItem,true); exit;
                        if (isset($SITE['no_menu_arrow']) ){$green_arrow = '';} 
                        elseif (!is_file('./img/external-link.png') ) {$green_arrow = '';}
                        else {  $green_arrow =  '<img src="./img/external-link.png"  style=" float: right; width: 18px; margin:1px; vertical-align: middle;" alt="external menu link" title="external menu link">';}
			$DropdownMenuText.='<li><a href="'.$menuItem['link'].'"  target="_blank" title="'.langtransstr($menuTitle).'">'.langtransstr($menuItem['caption']).$green_arrow.'</a></li>'.PHP_EOL;
		}
		else {
			$nr = (string) $menuItem['nr'];
			if ($SITE['noChoice'] == $nr) 	{$top = '';}
			$menuArray[$nr]['choice']		=trim((string) $menuItem['nr']);
			$menuArray[$nr]['css']  		=trim((string) $menuItem['css']);
			$menuArray[$nr]['head']  		=trim((string) $menuItem['head']);
			$menuArray[$nr]['show']  		=trim((string) $menuItem['show']);
			if ($hide_me) {$menuArray[$nr]['hide'] = true; } else { $menuArray[$nr]['hide'] = false;} 
			$menuArray[$nr]['top']                  =$top;
			$tekst = trim((string) $menuItem['folder']);
			if ($tekst == '') {
				$menuArray[$nr]['folder']	= '';
			} else {
				$menuArray[$nr]['folder']	= $tekst;
			}
			$tekst = trim((string) $menuItem['gizmo']);
			if ($tekst == '' || $tekst == '0' || $tekst == 'no' || $tekst == 'false' ) {
				$menuArray[$nr]['gizmo']	= false;
			} else {
				$menuArray[$nr]['gizmo']	= true;			
			}
			$link_string 		                = trim((string) $menuItem['link']);
			$menuArray[$nr]['link']	                = $link_string;
			$arr    = explode ('/',$link_string);
			$end    = count($arr) - 1;
			list($pagename,$type)   = explode ('.',$arr[$end]);
# echo $link_string; print_r ($arr);			
			$SITE['pages'][$pagename] = 'index.php?p='.$menuArray[$nr]['choice'];
			$menuArray[$nr]['noutf8']		=trim((string) $menuItem['noutf8']);
			$string_title   = trim((string) $menuTitle);
			if ($string_title == '') {$string_title = trim((string) $menuItem['caption']);}
			$menuArray[$nr]['title']		=$string_title;
			
			if ($hide_me <> true) {
			        $DropdownMenuText.='<li><a href="index.php?p='.$nr.'&amp;lang='.$lang.$extraP.$top.'" title="'.langtransstr($menuTitle).'">'.langtransstr($menuItem['caption']).$img.'</a></li>'.PHP_EOL;
			}	
		} // eof link aanwezig of niveau dieper
	}// eof foreach
} // eof function
?>
