<?php
header('Content-type: text/html; charset=utf-8');
ini_set('display_errors', 'On'); 
error_reporting(E_ALL); 

require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

$pageName	= 'printScript.php';

if (!isset($_REQUEST['echo']) || strtolower($_REQUEST['echo']) == '' ) 
{
        echo 'na valida specas'; exit;
}

ob_start(); 
if (!is_array($SITE) ) 
{
	 $SITE= array();
} 
include_once 'wsLoadSettings.php'; 
ob_end_clean();
#
if (isset($SITE['password']) && trim($SITE['password']) <> '' ) 
{
        $pass   = trim( $SITE['password'] );
        if (!isset($_REQUEST['pw'])  )   
        {
                echo 'na valida spepwas'; 
                exit;
        }
		
        $pw     = trim($_REQUEST['pw']);
        if ($pass <> $pw)               {
                echo 'na valida spepwas-2'; 
                exit;
        }  
}

$filenameReal = trim($_REQUEST['echo']);

$download_size = filesize($filenameReal);
header('Pragma: public');
header('Cache-Control: private');
header('Cache-Control: no-cache, must-revalidate');
header("Content-type: text/plain");
header("Accept-Ranges: bytes");
header("Content-Length: $download_size");
header('Connection: close');
readfile($filenameReal);