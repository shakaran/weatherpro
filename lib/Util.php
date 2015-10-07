<?php
class Util
{
	/**
	 * Display source of script if requested so
	 * 
	 * @return void
	 */
	public static function checkShowSource()
	{
		if(isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view') 
		{
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
	}
}
