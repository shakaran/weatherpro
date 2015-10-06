<?php # ini_set('display_errors', 1); error_reporting(E_ALL);
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
if (!isset ($SITE) ) {
        $SITE           = array();
        include 'wsLoadSettings.php';
        function langtrans($item) {return $item;}
        function langtransstr($item) {echo $item;}
        $lang           = $SITE['lang'];
        $inside_template= false;
} else {
        $inside_template= true;
        echo '<div class="blockDiv">
<h3 class="blockHead">Generate your upload files</h3>
<div style="width: 90%; margin: 10px auto;">';
       
}
$configs 	= array();
$version        = '3.0beta';
$convertDate	= date('Y-m-d H:i:s', time());
#
$pageName	= 'generate_tags.php';	
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.00 2015-06-08';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# ----------------------------------------------------------------------------------------
# 0.00 2015-06-08 added MP support
# ----------------------------------------------------------------------------------------	
$status = '';
$CUSTOM['weatherPrograms'] = array (    // these weather programs need to use this program
'MH' => 'Meteohub', 'MP' => 'Meteoplug'
);
#$ws_arr_supp_wx         = array ('CU', 'CW', 'DW', 'MB', 'MH', 'VW','WC', 'WD', 'WL', 'WS', 'WV');  #settings line 29
#
$SITE['customizeDir']	= './config/';	// here the base files for generation are located	
#
$wp		= $SITE['WXsoftware'];          // for which program we do generate
#
if (!isset ($CUSTOM['weatherPrograms'][$wp]) ) 
       {echo '<h3 style="text-align center;">Your weather-program ('.$wp.') does not need this generate program </h3>'; 
        if ($inside_template) {echo '</div></div>';}
        return;}
$weatherProgram	= $CUSTOM['weatherPrograms'][$wp];
# ---------------------------------------    which step are we----------------------------
$step		= 2; 
if (isset($_REQUEST['step3']) )	        {$step = 3;}
elseif (isset($_POST['reset']) 	)       {$step = 2;}  // reset all sensor values
elseif (isset($_POST['sensorSave']) )   {$step = 2;}
elseif (isset($_REQUEST['gen']) && $_REQUEST['gen'] <> '' )  {$step = 4;}
#
if ($step <> 4) {
        echo '
<div id="menu"><!--    menu - steps to follow -->
<form method="post" name="menu_select" action="">
<button id="step2" name = "step2" >'.langtransstr('Sensors').'</button>
<button id="step3" name = "step3" >'.langtransstr('Download').' '.langtransstr('sensor based files').'</button>
</form>
</div><!-- end menu - steps to follow -->'.PHP_EOL;
} // eo step 2 and 3 menu
#
$sensorFile	= $SITE['cacheDir'].'savedSensors'.$wp.'.txt';
#       first we load the default set of sensor settings in an array
if ($wp == 'MH' || $wp == 'MP') { //  Meteohub
$sensorArray[]	= array ( 'name' => 'TEMPOUT',	'default' => 'th0', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '31',	'lastPart' => '',	'post' => 'th0',	'opt' => false,	'comment' => 'temperature outside'	);	// th0-31
$sensorArray[]	= array ( 'name' => 'HUMOUT',	'default' => 'th0', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '31',	'lastPart' => '',	'post' => 'th0',	'opt' => false,	'comment' => 'humidity outside' 	);	// th0-31
$sensorArray[] 	= array ( 'name' => 'BARO',	'default' => 'thb0', 	'firstPart' => 'thb',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'thb0',	'opt' => false,	'comment' => 'pressure'			 	);	// thb0-9
$sensorArray[]	= array ( 'name' => 'TEMPIN',	'default' => 'thb0', 	'firstPart' => 'thb',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'thb0',	'opt' => true,	'comment' => 'temperature inside'	);	// thb0-9
$sensorArray[] 	= array ( 'name' => 'HUMIN',	'default' => 'thb0', 	'firstPart' => 'thb',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'thb0',	'opt' => true,	'comment' => 'humidity inside'		);	// thb0-9
$sensorArray[]	= array ( 'name' => 'WINDSPEED','default' => 'wind0',	'firstPart' => 'wind',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'wind0',	'opt' => false,	'comment' => 'wind speed'			);	// wind0-9
$sensorArray[]	= array ( 'name' => 'WINDDIR',	'default' => 'wind0',	'firstPart' => 'wind',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'wind0',	'opt' => false,	'comment' => 'wind direction'		);	// wind0-9
$sensorArray[]	= array ( 'name' => 'RAIN',	'default' => 'rain0',	'firstPart' => 'rain',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'rain0',	'opt' => false,	'comment' => 'rain'					);	// rain0-9
$sensorArray[] 	= array ( 'name' => 'UVS',	'default' => 'uv0', 	'firstPart' => 'uv',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'uv0',	'opt' => true,	'comment' => 'UV index =none if no UV sensor installed');  // uv0-9
$sensorArray[]	= array ( 'name' => 'SOLAR',	'default' => 'sol0', 	'firstPart' => 'sol',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'sol0',	'opt' => true,	'comment' => 'solar radiation or =none if no Solar sensor installed');	// sol0-9
$sensorArray[]	= array ( 'name' => 'TEMPEXTR1','default' => 't1', 	'firstPart' => 't',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'extra temperature 1 (f.i. swimmingpool) or =none');	// th0-9
$sensorArray[]	= array ( 'name' => 'TEMPEXTR2','default' => 't2', 	'firstPart' => 't',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'extra temperature 2 (f.i. glasshouse) or =none');	// t0-9
$sensorArray[]	= array ( 'name' => 'THEXTR1',	'default' => 'th1', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'extra temphum 1 (f.i. swimmingpool) or =none');	// th0-9
$sensorArray[]	= array ( 'name' => 'THEXTR2',	'default' => 'th2', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '9',		'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'extra temphum 2 (f.i. pond) or =none');	// th0-9
$sensorArray[]	= array ( 'name' => 'SOIL1',	'default' => 'th10', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '99',	'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'soil/moisture sensor  or =none');	// th10-99
$sensorArray[]	= array ( 'name' => 'SOIL2',	'default' => 'th11', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '99',	'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'soil/moisture sensor  or =none');	// th10-99
$sensorArray[]	= array ( 'name' => 'SOIL3',	'default' => 'th12', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '99',	'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'soil/moisture sensor  or =none');	// th10-99
$sensorArray[]	= array ( 'name' => 'SOIL4',	'default' => 'th13', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '99',	'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'soil/moisture sensor  or =none');	// th10-99
$sensorArray[]	= array ( 'name' => 'LEAF1',	'default' => 'th14', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '99',	'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'leaf sensor  or =none');	// th10-99
$sensorArray[]	= array ( 'name' => 'LEAF2',	'default' => 'th15', 	'firstPart' => 'th',	'nrMin' => '0',	'nrMax' => '99',	'lastPart' => '',	'post' => 'none',	'opt' => true,	'comment' => 'leaf sensor  or =none');	// th10-99
} // eo default sensor settings
#
# --------------------------- step 2 -----------------------------------------------------
if ($step == 2) {
        $doWeSave = false;
        if(isset($_POST['sensorSave'])) {
                for ($i = 0; $i < count($_POST['sensor']); $i++) {$sensorArray[$i]['post']= $_POST['sensor'][$i];}
                $status        .= 'sensors save requested<br />'.PHP_EOL;
                $doWeSave 	= true;
        } elseif (isset($_POST['reset'])) {
                for ($i = 0; $i < count($sensorArray);$i++) {$sensorArray[$i]['post']= $sensorArray[$i]['default'];}
                $status        .= 'sensors reset to defaults<br />'.PHP_EOL;
                $doWeSave 	= true;
        } elseif (file_exists($sensorFile)) {
                $filestring	= file_get_contents($sensorFile);
                $status	       .= 'existing sensor data ('.$sensorFile.') read from disk<br />'.PHP_EOL;
                $sensorArray 	= unserialize($filestring);
        }  else  {
                $status .= 'previously made sensors file not found. probably you are here for the first time<br />'.PHP_EOL;
        } // eo file_exists
        if ($doWeSave) {
                $ret		= file_put_contents ($sensorFile, serialize($sensorArray));
                if (!$ret) {
                        $status.= 'error saving sensor file to disk'.PHP_EOL;
                } else {$status.= 'sensor file save to disk - data written = '.$ret.'<br />'.PHP_EOL;}
        }  // eo do we save
# -------------------------------- display sensor and uom info ------------------------------------
        echo '<h3>Status:</h3><p>'.$status.'</p>';
        echo '<h3>'.langtransstr('Set sensors and units of measurement').'</h3>'.PHP_EOL;
        echo '<form method="post" name="sensor_select" action="" style="padding: 0px; margin: 0px">';
        echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
        echo '<tr><th colspan="2">'.langtransstr('sensor names').'</th></tr>';
        foreach  ($sensorArray as $key => $arr) { 
                echo '<tr><td style="text-align: right; font-size: 16px;"><span>'.langtransstr($arr['comment']).'</span></td><td>'.PHP_EOL;
                echo '&nbsp;<select id="sensor" name="sensor[]"  style="font-size: 12px" width="18">'.PHP_EOL;
                if ($arr['opt'] == true) {echo '<option value="none" selected="selected">none</option>'.PHP_EOL; $extra = '';}
                for ($i=$arr['nrMin']; $i<=$arr['nrMax']; $i++) {
                        $sensor = $arr['firstPart'].$i.$arr['lastPart'];
                        if ($sensor == $arr['post']) {$extra = 'selected="selected"';} else {$extra = '';}
                        echo '<option value="'.$sensor.'" '.$extra.'>'.$sensor.'</option>'.PHP_EOL;
                }
                echo '</select></td>'.PHP_EOL;
                echo '</tr>'.PHP_EOL;
        } // eo for each sensorarray
        echo '<tr>
<td  align="right"><br /><button id="sensorSave" name = "sensorSave" >'.langtransstr('Save your changes').'</button>&nbsp;</td>
<td  align="left"><br />&nbsp;<button id="reset"      name = "reset" >'     .langtransstr('Forget all changes').'</button></td>
</tr>'.PHP_EOL.
'</table>'.PHP_EOL.
'</form>'.PHP_EOL;
}  // eo step2
# --------------------------- step 3 -----------------------------------------------------
elseif ($step == 3 || $step == 4) {
        if (file_exists($sensorFile)) {
                $filestring	= file_get_contents($sensorFile);
                $status	       .= 'existing sensor data ('.$sensorFile.') read from disk<br />the files you are downloading are adapted to your own settings.'.PHP_EOL;
                $sensorArray 	= unserialize($filestring );
        }
        $sensorCodes 	= array ();
        $sensorValues	= array ();
        $end            = count($sensorArray);
        for ($i = 0; $i < $end; $i++) {
                $sensorCodes[]	= $sensorArray[$i]['name'];
                $sensorValues[]	= $sensorArray[$i]['post'];
        }
        # files to generate
        if ($wp == 'MH') { 	$filesAvail = array(	        //  Meteohub  realtime uses clientraw
                'tagstoday'     => 'MH_tagsPrototype.txt	|tags.mh.html		|Todays values for website use, uploaded by 			|<b>graphs</b> directory on Meteohub system|',
                'tagsyday' 	=> 'MH_tagsydayPrototype.txt	|tagsyday.mh.html	|Yesterdays values for website use, uploaded by 		|<b>graphs</b> directory on Meteohub system|',
                'wr'   		=> 'MH_windrosePrototype.txt	|windrose.mg		|Windrose graphic used on trends page,  uploaded by 		|<b>graphs</b> directory on Meteohub system|',
                'tdb'   	=> 'MH_tdpb2dayPrototype.txt	|tdpb2day.mg		|Temp/DP/Baro graphic used on station graphs page,  uploaded by |<b>graphs</b> directory on Meteohub system|',
                'wrg'   	=> 'MH_windrain2dayPrototype.txt|windrain2day.mg	|Wind/Rain graphic used on station graphs page,  uploaded by 	|<b>graphs</b> directory on Meteohub system|',
                'soluv'	        => 'MH_soluv2dayPrototype.txt   |soluv2day.mg           |Solar/UV Index graphic used on station graphs page             |<b>graphs</b> directory on Meteohub system|',
                'solhi'	        => 'MH_solhi2dayPrototype.txt   |solhi2day.mg           |Solar/Heat Index graphic used on station graphs page           |<b>graphs</b> directory on Meteohub system|',
                'uvhi'          => 'MH_uvhi2dayPrototype.txt    |uvhi2day.mg            |UV Index/Heat Index graphic used on station graphs page        |<b>graphs</b> directory on Meteohub system|'           );
        }
        if ($wp == 'MP') { 	$filesAvail = array(	        //  Meteohub  realtime uses clientraw
                'tagstoday'     => 'MP_tagsPrototype.txt	|tags.mp.txt		|All values for website use, uploaded by 			|scripts folder on your website|' );
        }
        if ($step == 3) {
                echo '<h3>Status:</h3><p>'.$status.'</p>';
                echo '<h3>'.langtransstr('Files to be generated and installed').'</h3>'.PHP_EOL;
                echo '<p><strong>'.langtransstr('Instructions').':</strong> '.langtransstr('click on the links below and save the file to the <em>directory</em> and <em>filename</em> specified').'.</p>'.PHP_EOL;
                echo '<table border="1" style="width: 100%; border-collapse: collapse;">'.PHP_EOL;
                echo '<tr><th>'.langtransstr('Filename').'</th><th>'.langtransstr('Save generated file to').'</th><th>'.langtransstr('Purpose/Setup needed').'</th></tr>'.PHP_EOL;
                foreach ($filesAvail as $arg => $vals) {
                        list($sce,$filename,$descr,$dest) = explode('|',$vals);
                        $sce		= trim($sce);
                        $filename	= trim($filename);
                        $descr		= trim($descr);
                        $dest		= trim($dest);
                        list($tfile,$text) = explode('.',$filename);
                        $next = $text;
                        switch ($text) {
                                case "mg" : 
                                        $next = 'png';
                                break;
                                case "html" : 
                                        $next = 'php';
                                break;
                        }
                        $uploadas = $tfile.'.'.$next;
                        $tstr = '';
                                if($text == 'mg') {
                                        $tstr = "<br/>".langtransstr('Use Meteohub <i>Manage Graphs</i> to edit file for correct units to display').".";
                                }
                                if(preg_match('|graphs|',$dest) ) {
                                        $tstr .=  "<br/>".langtransstr('Use Meteohub <i>Graph Uploads</i> to set schedule and upload file as')." <b>$uploadas</b>";
                                }
                        $descr=langtransstr($descr);
                        print "<tr>\n";
                        print "  <td><a href=\"?p=$p&gen=$arg\">$filename</a></td>\n";
                        print "  <td>".langtransstr($dest).".</td>\n";
                        print "  <td>$descr $weatherProgram$tstr</td>\n";
                        print "</tr>\n";
                }
                echo '</form>'.PHP_EOL.'</td></tr>'.PHP_EOL.'</table>'.PHP_EOL;
        }       // eo step 3
# --------------------------- step 4 -----------------------------------------------------
        if ($step == 4) {
                $sensorCodes[]	= 'VERSION';
                $sensorValues[]	= $version;	
                $sensorCodes[]	= 'CONVERTDATE';
                $sensorValues[]	= $convertDate;
                #$sensorCodes[]	= 'FORMAT';
                #$sensorValues[]= $CUSTOM['installedLanguages'][$lang]['langCode'];
                $sensorCodes[]	= 'WEATHERPROGRAM';
                $sensorValues[]	= $weatherProgram;
                #echo '<pre>'; print_r($filesAvail); exit;
                if(isset($_REQUEST['gen']) and isset($filesAvail[strtolower($_REQUEST['gen'])]) ) {
                        list($fileIn,$fileOut) = explode('|',$filesAvail[strtolower($_REQUEST['gen'])]);
                        $fileIn			= $SITE['customizeDir'].trim($fileIn);
                        $fileOut		= trim($fileOut);
                        $_REQUEST['gen']='';
                        $input 	                = file_get_contents($fileIn);
                        $string                 = str_replace($sensorCodes, $sensorValues, $input);                       
                        ob_end_clean();
                        header(":", true, 200);
                        header('Content-type: text/plain;');
                        header('Content-Disposition: attachment; filename="'.$fileOut.'"');
                        echo $string;   // download to user
                        $ret                    = file_put_contents ($SITE['wp_scripts'].$fileOut, $string);	
                        flush();
                        die;
                }
                ob_end_clean(); 
                echo "<script type='text/javascript'>alert('unknown error in file definitions')</script>";
                die;
        } // eo step 4
} // eo step==3 or 4
?>
</div>
</div>