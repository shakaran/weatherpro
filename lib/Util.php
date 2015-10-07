<?php
class Util
{
	/**
	 * Display source of script if requested so
	 * 
	 * @return void
	 */
	public static function checkShowSource($current_file = NULL)
	{
		echo $current_file;
		if(isset($_REQUEST['sce']) && ( strtolower($_REQUEST['sce']) == 'view' || strtolower($_REQUEST['sce']) == 'echo' )) 
		{
		   $download_size = filesize($current_file);
		   header('Pragma: public');
		   header('Cache-Control: private');
		   header('Cache-Control: no-cache, must-revalidate');
		   header("Content-type: text/plain");
		   header("Accept-Ranges: bytes");
		   header("Content-Length: $download_size");
		   header('Connection: close');
		   readfile($current_file);
		   exit;
		}
	}
}
