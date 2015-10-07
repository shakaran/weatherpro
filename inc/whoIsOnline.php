<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'whoIsOnline.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-19';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-19 release version
# -------------------------------------------------------------------------------------------------
#
#---------------------------------------------------------------------------
?>
<script type="text/javascript" src="javaScripts/sorttable.js"></script>
<div class="blockDiv">
<h3 class="blockHead"><?php echo langtransstr('Who is Online').'<br /><br />'.
langtransstr('You can sort the table on the values in the columns by clicking in the corresponding heading').'<br /><br />'; ?></h3>
<?php
$path 	=	$SITE['cacheDir'];
$file	=	$path.$SITE['visitorsFile'];  // file with userdata
$found	=	false;
$users	=	0;
$ip 	=	$SITE['REMOTE_ADDR'];
if ($ip == '::1') {$ip = 'localhost';}
echo "<!--  IP = $ip -->\n";
#---------------------------------------------------------------------------
#  load array with previous users at this website
#---------------------------------------------------------------------------
$fileExist=true;
$filestring=file_get_contents($file);			        // get array of online users as saved last time
if (!$filestring == false) {				        // if file was found
	echo "<!--  ip users ($file) FOUND     -->\n";
	$online = unserialize(base64_decode($filestring));      // recreate php array
	foreach ($online as $key => $record) {		        //  check ip adres already known and update this record
		if ($key === $ip) { 	
			$online[$key]['page'] = $_SERVER['REQUEST_URI'];
			$online[$key]['time'] = time();
			$record['time']= time();
			if ($online[$key]['starttime'] == 0) {$online[$key]['starttime'] = time();}
			if ($online[$key]['active'] <> 'j')  {$online[$key]['starttime'] = time();}
			$online[$key]['active'] = 'j';
			$found=true;				// ip user known indicator
		}
		$time = time();
		if ($key <> '0.0.0.0') {
			if (($time - $online[$key]['time']) >= 900) {
				$online[$key]['starttime']= 0;			
				$online[$key]['active'] = 'n';
			} else {
				$online[$key]['active'] = 'j';
			}
			if ($online[$key]['active'] ==='j') {$users++;}
		}
	}
} else {
	echo '<!-- NO ip users FILE FOUND, creqate table     -->';
	$fileExist=false;
	$online = array();
}  	//
$online['0.0.0.0']['ip']		= 'ip';
$online['0.0.0.0']['page']		= 'page';
$online['0.0.0.0']['guest'] 	= 'who';
$online['0.0.0.0']['time']		= 'last click';
$online['0.0.0.0']['starttime']	= 'since';
$online['0.0.0.0']['active']	= 'active';
$online['0.0.0.0']['country']	= 'country';
$online['0.0.0.0']['flag']		= 'flag'; 
#---------------------------------------------------------------------------
#echo '<pre>'; print_r ($online); exit;
#echo '<p style="width: 90%; margin: 20px auto; ">'.langtransstr('You can sort the table on the values in the columns by clicking in the corresponding heading').'</p>'.PHP_EOL;
echo '<table   class="sortable genericTable">'.PHP_EOL;
$rowcolor=0;
// print headings
echo '<thead>
<tr class="table-top">
<th style="text-align: center;  cursor: n-resize;">'.langtransstr('online for').'</th>
<th style="text-align: center;  cursor: n-resize;">'.langtransstr('who').'</th>';
if (isset ($SITE['geoKey']) && $SITE['geoKey']	<> '') {
	echo '
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['country'].'</th>
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['flag'].'</th>';
}
echo '
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['ip'].'</th>
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['starttime'].'</th>
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['time'].'</th>
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['page'].'</th>
<th style="text-align: center;  cursor: n-resize;">'.$online['0.0.0.0']['active'].'</th>
</tr>
</thead>
<tbody>'.PHP_EOL;
$style='background-color: #FFFFFF;';
$online2= sort_multi_array ($online, 'time');
$online3= array();
$oldrecordsExist=false;
$class='"row-light"';
$rowcolor = 0;
$x=4;
$toOld=time()-60*60*24*$x;  // to delete records older than x days
foreach ($online2 as $key => $record) {
	if ($record['ip'] === 'ip') {   // skip the row with the headings
		$online3['0.0.0.0']=$record; 
	}
	else
	{	if ($record['time'] <= $toOld) {
			$oldrecordsExist=true;
		}
		else
		{	$key = $record['ip'];
			$online3[$key]=$record;
			echo '<tr class='.$class.'><td>';
			if ($record['starttime'] == 0) {echo '---';} else {
			echo date($SITE['timeOnlyFormat'],($record['time']-$record['starttime']-3600));}
			echo '</td><td>';
			echo $record['guest'];
			if ($record['ip'] == $_SERVER['REMOTE_ADDR']) {echo '-you';}
			echo '</td>';
			if (isset ($SITE['geoKey']) && $SITE['geoKey']	<> '') {
				echo '<td>'.$record['country'].'</td>
				<td><img src="'.$record['flag'].'" alt=" " /></td>';
			}
			echo '<td>'.$record['ip'].'</td>
			<td>';
			if ($record['starttime'] === 0) {
				$start = '-'; 
			}  else {
				$start =date($SITE['timeOnlyFormat'],$record['starttime']);
			}
			$time =date($SITE['dateOnlyFormat'],$record['time']).' ';
			$time .=date($SITE['timeOnlyFormat'],$record['time']);
			echo $start.'</td>
			<td>'.$time.'</td>
			<td>';	
			$len = 24;
			$arr = explode ('index.php', $record['page']);
			if (count ($arr) == 1) {$string = $arr[0];} else {$string = $arr[1];}
			$string = str_replace('&amp;','&',$string);
			$subString = chunk_split ($string,24,"<br />"); 
			$subString = str_replace('&','&amp;',$subString);
			echo $subString.'</td>
			<td>'.$record['active'].'</td>
			'."</tr>\n";  
		}  // eo if  to old
	}  // eo if else not heading 
}  // eo for each 
echo '</tbody></table>
</div>'.PHP_EOL;
// -----------------------------------------------------
$keys = array();
// create a custom search function to pass to usort
 function func52 ($a, $b)  {
  	global $keys;
    for ($i=0;$i<count($keys);$i++) {
      if ($a[$keys[$i]] != $b[$keys[$i]]) {
        return ($a[$keys[$i]] < $b[$keys[$i]]) ? 1 : -1;
      }
    }
    return 0;
  };


function sort_multi_array ($array, $key)
{
//  $keys = array();
global $keys;
  for ($i=1;$i<func_num_args();$i++) {
    $keys[$i-1] = func_get_arg($i);
  }
  usort($array, 'func52');
  return $array;
} 

#---------------------------------------------------------------------------
#  save array with previous users os this website
#---------------------------------------------------------------------------
if (!$fileExist==true){
	echo "<!--  new ip users ($file) saved     -->\n";
	$safe_string_to_store = base64_encode(serialize($online)); // export array
	$ret=file_put_contents ($file, $safe_string_to_store, LOCK_EX);
}
unset($online);
if ($oldrecordsExist==true){
	echo "<!--  smaller ip users ($file) saved     -->\n";
	$safe_string_to_store = base64_encode(serialize($online3)); // export array
	$ret=file_put_contents ($file, $safe_string_to_store, LOCK_EX);
}

