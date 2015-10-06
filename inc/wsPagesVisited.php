<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   //--self downloader --
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
$pageName	= 'wsPagesVisited.php';	
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-12 release version
# -------------------------------------------------------------------------------------------------
#
$headText	= langtransstr('Pages visited');
$extraText1	= langtransstr('You can sort the table on the values in the columns by clicking in the corresponding heading');
# get file into array
$file	        = $SITE['cacheDir'].'pagesVisited.txt'; 
if (!file_exists($file)) {	
	echo '<h3> no data found</h3>'; 
	return;
}
$filestring	= file_get_contents($file);
echo '<!--  visited pages ('.$file.') loaded -->'.PHP_EOL;
$arrVisits	= unserialize($filestring);
#  sort array on pagecode/nr
ksort($arrVisits);
foreach ($arrVisits as $key => $count) {
        if ($key == '') {continue;}
	$arr = explode ('|', $key);
	$array[]= array ('code' => $arr[0], 'script' => $arr[1], 'count' => $count);	
}
?>
<script type="text/javascript" src="javaScripts/sorttable.js"></script>

<div class="blockDiv">
<h3 class="blockHead"><?php echo $headText.'<br ><br >'. $extraText1.'<br /><br />'; ?></h3>
<table class="sortable genericTable" style="width: 100%; ">
<thead>
<tr class="table-top">
<th style="padding-left: 5px; 
           text-align: left; width: 100px; cursor: n-resize;">menu</th>
<th style="text-align: left; width: 80px;  cursor: n-resize;">count</th>
<th style="text-align: left; width: 250px; cursor: n-resize;">script</th>
<th style="text-align: left;               cursor: n-resize;">folder</th>
</tr>
</thead>
<tbody>
<?php

$td	= '<td style="padding-left: 5px; text-align: left; ">';
$td1	= '<td style="text-align: left;">';
$td2	= '</td>';
$tr1	= '<tr class="row-light">';
$tr2	= '</tr>'.PHP_EOL;
foreach ($array as $key => $arr) {
#	$script = str_replace ('/', ' / ',$arr['script']);
	$scripts = explode ('/',$arr['script']);
	if (!isset ($scripts[1]) ) {
		$folder	= '/';
		$script	= $arr['script'];
	}
	else {
		$folder	= $scripts[0];
		$script	= $scripts[1];	
	}
	$count = number_format ( (float) 1*$arr['count'] , 0 , ',' , '.' );
	echo $tr1.
	$td.$arr['code'].$td2.
	$td1.$count.$td2.
	$td1.$script.$td2.
	$td1.$folder.$td2.
	$tr2;
}
?>
</tbody>
</table>
</div>
