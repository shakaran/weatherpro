<?php ini_set('display_errors', 'On');   error_reporting(E_ALL);

if (!isset ($config_folder) ) {$config_folder = './';}

# http://dd.weather.gc.ca/citypage_weather/docs/site_list_en.csv
$arr = file($config_folder.'Canada_site_list_en.csv');
$end = count ($arr);
$arr_provinces = array ();
$arr_prov_code = array ();
for ($n = 2; $n < $end; $n++) {
	$line = explode (',',$arr[$n]);
#echo '<pre>'; print_r($line);  exit;
	$prov	= $line[2];
	$name	= $line[1];
	$code	= $line[0];
	if (!isset ($arr_provinces[$prov]) ) {$arr_provinces[$prov] = $prov;}
	$arr_prov_code[] = $prov.'#'.$name.'#'.$code;


}
asort($arr_provinces);
asort($arr_prov_code);
/*
echo '<pre>xx'; 
print_r($arr_provinces);
print_r($arr_prov_code); 
*/