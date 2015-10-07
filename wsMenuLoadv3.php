<?php 
ini_set('display_errors', 'On');   
error_reporting(E_ALL);
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

function langtransstr ($str) {return $str; }
$pathString = '';
#$SITE['menuXml']        = './wsMenuData.xml';
$SITE['menu_txt']       = '_my_scripts/my_menu.txt';
#$SITE['menu_txt_alt']   = '_my_scripts/ws_menu.txt';
$lang                   = 'en';
$extraP                 = '';
$top                    = '';
$SITE['cacheDir']	= 'cache/';		// directory to cache files
$SITE['imgDir']		= 'img/';		// directory to images 
#-----------------------------------------------------------------------
# just to know which script version is executing
#-----------------------------------------------------------------------
$pageName	= 'wsMenuLoadv3.php';
$pageVersion	= '3.01special 2014-10-23';

#if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}

$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.00 2014-09-29 release version	
# 3.01 2014-10-04 -03 code for rare (old) error in php xml implementation  - lines 70-76 -04 EWN problem disappearing menu
# 3.01special 2014-10-23  test new updated images in menu
#-----------------------------------------------------------------------------------------
/*
$pageFile	= $menuData;
$pageVersion= (string)$xmlStr->dateVersion;
$SITE['wsModules'][$menuData] = 'version: '.(string)$xmlStr->dateVersion;  // save some info
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
*/
#
$load_menu      = false;
if (isset ($_REQUEST['force']) && trim(strtolower ($_REQUEST['force'])) == 'menu') {
        $load_menu      = true;
        $pathString .='<!-- forced reload of menu file by user request -->'.PHP_EOL;
}
# check cached version of menu
$cache_file     = $SITE['cacheDir'].'menu_'.$lang.'.arr';
if ($load_menu == false && file_exists ($cache_file)) {
        $menuArray      = unserialize (file_get_contents ($cache_file) );
        if (!$menuArray) {
                $pathString            .='<!-- menu load failed for '.$cache_file.' -->'.PHP_EOL;
        }
        else  { $DropdownMenuText       = $menuArray['ullist'];
                $pathString    .='<!-- menu loaded from '.$cache_file.' -->'.PHP_EOL; 
                return true; 
        } // menu loaded into array => back to calling scrip        
}
# check what kind of menu file is available



$string = '';
if (isset ($SITE['menu_txt']) && file_exists ($SITE['menu_txt']) ) {
        $pathString    .='<!-- loading menu data from '.$SITE['menu_txt'].' -->'.PHP_EOL;
        $string 	= file_get_contents($SITE['menu_txt']); 
}
elseif (isset ($SITE['menu_txt_alt']) && file_exists ($SITE['menu_txt_alt']) ) {
        $pathString    .='<!-- loading menu data from '.$SITE['menu_txt_alt'].' -->'.PHP_EOL;
        $string 	= file_get_contents($SITE['menu_txt']); 
}
if ($string <> '') {
        $arr_text       = explode ("\n", $string);
#        echo '<pre>';  print_r($arr_text); exit;
        $n = -1;
        for ($i = 0; $i < count ($arr_text); $i++) {
                if (substr ($arr_text[$i],0,1) == '#') {continue;}
                $arr_line               = explode ('|',$arr_text[$i].'|||||');
                if (count ($arr_line)  < 10)    {continue;}
                $level  = trim($arr_line[0]);
                if (!is_numeric ($level) )      {continue;}
                $n++;
                $menuArray[$n]['level'] = $level;                                
                $menuArray[$n]['choice']= trim($arr_line[1]);   // key
                $menuArray[$n]['show']  = trim($arr_line[2]);
                $menuArray[$n]['title'] = trim($arr_line[3]);   // also caption
                $folder                 = trim($arr_line[4]);
                if ($folder == 'main') {$folder = '';}
                $menuArray[$n]['folder']= trim($folder);
                $menuArray[$n]['link']  = trim($arr_line[5]);   // also script
                $menuArray[$n]['gizmo'] = trim($arr_line[6]);
                $img                    = trim($arr_line[7]);
                if ($img == 'updated' || $img == 'new') {$img = $SITE['imgDir'].'menu_'.$img.'.gif';}
                $menuArray[$n]['img']   = $img;
                $menuArray[$n]['head']  = trim($arr_line[8]);
                $menuArray[$n]['css']   = trim($arr_line[9]);
                $menuArray[$n]['noutf8']= trim($arr_line[10]);  // character set in use
        }
        if (isset ($SITE['menuXml']) )          {unset ($SITE['menuXml']);}
        if (isset ($SITE['menu_txt_alt']) )     {unset ($SITE['menu_txt_alt']);} 
}
#echo $pathString.'<pre>';print_r($menuArray); exit;
				
if (isset ($SITE['menuXml']) && file_exists ($SITE['menuXml']) ) {
        $pathString .='<!-- loading menu data from '.$SITE['menuXml'].' -->'.PHP_EOL;
        $string 	= file_get_contents($SITE['menuXml']); 
        $xmlStr 	= new SimpleXMLElement($string);
        $level          = 0;
        $n              = -1;
        $menuArray      = array();
        function ws_arr_gen ($data) {
                global $menuArray, $n, $level;
                $level++;
                foreach ($data->item as $menuItem) {
                        $n++;
                        $menuArray[$n]['level']         = $level; 
                        $menuArray[$n]['choice']        = trim ((string) $menuItem['nr']);
                        $menuArray[$n]['show']          = trim ((string) $menuItem['show']);    
                        if (isset($menuItem['title']))  {$menuArray[$n]['title']= trim ((string) $menuItem['title']);}
                        else                            {$menuArray[$n]['title']= trim ((string) $menuItem['caption']);}  
        #                $menuArray[$n]['folder']        = trim ((string) $menuItem['folder']); 
                        $link_string 		        = trim ((string) $menuItem['link']);
                        if ($link_string == '')         {
                                $menuArray[$n]['choice']='group';
                        }
                        else {  $arr    = explode ('/',$link_string);
                                $end    = count($arr) - 1;
                                $script = $arr[$end];
                                $menuArray[$n]['folder']= str_replace ($script, '', $link_string);
                                $menuArray[$n]['link']  = $script;
                        }
                        $tekst = trim((string) $menuItem['gizmo']);
                        if ($tekst == '' || $tekst == '0' || $tekst == 'no' || $tekst == 'false' ) {
                                $menuArray[$n]['gizmo']	= '';
                        } else {
                                $menuArray[$n]['gizmo']	= 'gizmo';			
                        }
                        $menuArray[$n]['img']           = trim ((string) $menuItem['img']);
                        $menuArray[$n]['head']          = trim ((string) $menuItem['head']);
                        $menuArray[$n]['css']           = trim ((string) $menuItem['css']);
                        $menuArray[$n]['noutf8']        = trim ((string) $menuItem['noutf8']);
                        if ($link_string == '')         {
                                $data2          = $menuItem;											// 
				ws_arr_gen ($data2); 
                        }

                } // eo for each
                $level--;
        } // eof ws_arr_gen
        ws_arr_gen ($xmlStr);
}
# 
$DropdownMenuText       = '';
$end                    = count ($menuArray);
$level                  = 1;
$level_show             = true;
function check_show ($check) {
        return '';
}
#
for ($n = 0; $n < $end; $n++) {
        $extra          = '';
        $menuItem       = $menuArray[$n];
        $img            = $menuItem['img'];
        if ($img <> '') {
                $img = ' <img src ="'.$menuItem['img'].'" alt=" ">';
        }
#print_r ($menuItem); exit;
        if ($menuItem['level']  < $level ) { // insert 1 or more </ul></li>
                $i                      = $level - $menuItem['level'];
                $extra                  = str_repeat('</ul></li>', $i).PHP_EOL;
                $menuArray[$n-1]['html'].= $extra;
                $level                  = $menuItem['level'];  
                $DropdownMenuText      .= $extra;                       
                $level_show             = true;
        }
        if ($menuItem['link'] == '') { // insert li + ul
# <li class="withImg"> <a href="#" title="Demo extra pages">Demo extra pages</a>
# <ul>
                $level++;
                $title                  = langtransstr($menuItem['title']);
                $show                   = check_show ($menuItem['show']);
                if ($show <> '') {$level_show = false;}
                $string                 = '<li class="withImg" style="'.$show.'"> <a href="#" title="'.$title.'">'.$title.$img.'</a>'.PHP_EOL.'<ul style="'.$show.'">'.PHP_EOL;
                $menuArray[$n]['html']  = $string;
                $DropdownMenuText      .= $string;
#echo $DropdownMenuText; print_r($menuArray); exit;
                continue;

        } 
# same level generate li
# <li><a href="index.php?p=01&amp;lang=en#data-area" title="Airplane radar">Airplane radar</a></li>

        $title                  = langtransstr($menuItem['title']);
        $show                   = check_show ($menuItem['show']);
        $string                 = '<li><a href="index.php?p='.$menuItem['choice'].'&amp;lang='.$lang.$extraP.$top.'" title="'.$title.'">'.$title.$img.'</a></li>'.PHP_EOL;
        $menuArray[$n]['html']  = $string;
        $DropdownMenuText      .= $string;
#if ($n > 15) { echo 'menu'.$DropdownMenuText; print_r($menuArray); exit;}

} // eo for
echo $pathString.PHP_EOL.$DropdownMenuText; print_r($menuArray); exit;
#
$menuArray['ullist']    = $DropdownMenuText;
# save to chache
$result = file_put_contents( $cache_file, serialize ($menuArray) );
if (!$result) { 
        echo'Save to cache failed for menu file '.$cache_file.
        PHP_EOL.'Make sure your cache folder '.$SITE['cacheDir'].' is writable, program aborted.'.PHP_EOL; 
        exit;
}
else {  $pathString .='<!-- menu save to cahce as '.$cache_file.' -->'.PHP_EOL;
}

#--------------------------------------------------------------------------------------------------
#  iterative fuction to generate li and ul/li; when the array with menuchoices etc is build
#  OUTPUT = ARRAY  and ul/li text
#--------------------------------------------------------------------------------------------------
function wsMenuGen ($data) {								
	global $DropdownMenuText, $level, $SITE, $menuArray, $extraP, $lang, $pathString;	
	foreach ($data->item as $menuItem) {
		$Show= true;			// default every xml-menu setting is shown
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
		}  // eo check if this menu item be shown to the visitor
#
		for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.="\t";}   	// number of tabs
		if (isset ($SITE['skipTop']) && $SITE['skipTop']) 
		        {$top = '#data-area';}
		else    {$top = '';}
		if (isset ($menuItem['top']) ) {
		        $string = trim($menuItem['top']);
		        if ($menuItem['top'] == false) 
		                {$top = '';} 
		        elseif ($string == 'no') 
		                {$top = '';}
		        else    {$top    = '#'.$string;}
		}
		if (!isset ($menuItem['title']) ) {
		        $menuTitle = $menuItem['caption'];
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
		if (!isset($menuItem['link'])) {								// submenuheaders have no link specified
			$DropdownMenuText.=
			'<li class="withImg">
			<a href="#" title="'.langtransstr($menuTitle).'">'.
			langtransstr($menuItem['caption']).$img.
			'</a>'.PHP_EOL;
			for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.='	';} // number of tabs
			$DropdownMenuText.='	<ul>'.PHP_EOL;						// new ul group
			$level= $level + 1;											// extra tab in new level
			$data2 = $menuItem;											// 
				wsMenuGen ($data2);    	// this function calls itself for nested menu levels
			for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.='	';}
			$DropdownMenuText.='</ul>'.PHP_EOL;								// end new ul group
			$level = $level - 1;										// back 1 level
			for ($i=1 ; $i <= $level ; $i++) {$DropdownMenuText.='	';}
			$DropdownMenuText.='</li>'.PHP_EOL;
		} else {
			$nr = (string) $menuItem['nr'];
			if (isset ($SITE['noChoice']) && $SITE['noChoice'] == $nr) 	{$top = '';}
			$menuArray[$nr]['choice']		=trim((string) $menuItem['nr']);
			$menuArray[$nr]['css']  		=trim((string) $menuItem['css']);
			$menuArray[$nr]['head']  		=trim((string) $menuItem['head']);
			$menuArray[$nr]['show']  		=trim((string) $menuItem['show']);
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
			$menuArray[$nr]['title']		=trim((string) $menuTitle);
			$DropdownMenuText.='<li><a href="index.php?p='.$nr.'&amp;lang='.
			$lang.$extraP.$top.'" title="'.langtransstr($menuTitle).'">'.langtransstr($menuItem['caption']).$img.'</a></li>'.PHP_EOL;	
		} // eof link aanwezig of niveau dieper
	}// eof foreach
} // eof function