<head>
<link rel="stylesheet" href="http://www.europeanweathernetwork.eu/css/wrf_s.css" />
<script src="http://static.nordicweather.net/jq/jquery-1.8.2.min.js"></script>
</head>

<body>
<?php
$lat    = 60.45042;
$lon    = 23.23714;
$howmany = 6;
$windunit="m/s";
$lang="fi";         // Remove if your site has it defined allready

$topfrc=frccurl("http://www.europeanweathernetwork.eu/frc/data_short.php?lat=$lat&lon=$lon&howmany=$howmany&windunit=$windunit&lang=$lang");
echo $topfrc;

function frccurl($url) {
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120424 Firefox/12.0 PaleMoon/12.0');
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_TIMEOUT,2);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
  $response = curl_exec($ch);
  curl_close($ch); 
  return $response;
}
?>
</body>