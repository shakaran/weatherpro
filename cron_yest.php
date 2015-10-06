<?php ini_set('display_errors', 'On');  error_reporting(E_ALL);
#
$arr_wp         = array ('ws','wv','dw');
#
$string = $_SERVER['PHP_SELF'];
$string = str_replace('cron_yest.php','yesterday.php',$string);

$url    = $_SERVER['HTTP_HOST'].$string.'?wp=';
#
echo 'Started<br />';

for ($i = 0; $i < count($arr_wp); $i++) {
        $fullurl        = $url.$arr_wp[$i];
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $fullurl);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
        $rawdata = curl_exec ($ch);
        curl_close ($ch);
        echo '<br />'.$url.$arr_wp[$i].'<br /> =>'.$rawdata.'<br />';
}
echo '<br />finished';
