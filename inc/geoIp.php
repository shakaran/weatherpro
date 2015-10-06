<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'geoIp.php';
$pageVersion	= '0.10 2012-04-17';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# Footer part of webpage
#---------------------------------------------------------------------------
#
# to be updated with other sources of ip info preferably without the use of a key
#---------------------------------------------------------------------------
class GeoIP {  
  
    /* 
        Returns all possible Geo Ip information in an associative array.  The following pieces of information 
        are returned: 
        IP - IP ADDRESS LOOKED UP 
        CODE - COUNTRY CODE 
        COUNTRY - COUNTRY NAME 
        FLAG - PATH TO IMAGE OF THE COUNTRY'S FLAG 
        CITY - CITY NAME 
        REGION - STATE NAME 
        ISP - ISP NAME 
        LAT - LATITUDE CORDINATE 
        LNG - LONGITUDE CORDINATE 
 
        USAGE: 
        $ipinfo = GeoIP :: getGeoArray('xxx.xxx.xxx.xxx'); 
        echo $ipinfo['CITY'] . ', ' . $ipinfo['STATE']; 
    */  
    public static function getGeoArray($ip) { 
    	global $SITE;
        $file = "http://www.ipgp.net/api/xml/". $ip."/".$SITE['geoKey'];    
        $xml_parser = xml_parser_create();  
        $fp = fopen($file, "r");  
        $data = fread($fp, 80000);  
        xml_parse_into_struct($xml_parser, $data, $vals);  
        $iplookup = array();  
        foreach ($vals as $v) {  
            if (isset($v['tag']) && isset($v['value'])) { 
                $iplookup[$v['tag']] = $v['value']; 
            } 
        } 
        xml_parser_free($xml_parser); 
        fclose($fp); 
 
        return $iplookup; 
    } 
 
    //shortcut to get the city name 
    public static function getCity($ip) { 
        $a =  self :: getGeoArray($ip); 
        return $a['CITY']; 
    } 
 
    //shortcut to get the state name 
    public static function getState($ip) { 
        $a =  self :: getGeoArray($ip); 
        return $a['REGION'];  
    }  
  
} 
?>