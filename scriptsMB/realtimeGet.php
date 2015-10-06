<?php ini_set('display_errors', 'On');  error_reporting(E_ALL);		
if( isset($_GET['d']) ) {
	$string=$_GET['d'];
	$ret=file_put_contents ('../uploadMB/realtime.txt', $string, LOCK_EX);
	if (!$ret) { echo 'file not saved';} else {echo "<p>Success</p>\n"; }
}