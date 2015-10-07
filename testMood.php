<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="description" content="Weerstation Wilsele-Dorp bij Leuven-  - Welkom" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1 "/>
	<link rel="stylesheet" type="text/css" href="<? echo $SITE['CSSscreen']; ?>" media="screen" title="screen" />
	<link rel="stylesheet" type="text/css" href="<? echo $SITE['CSStable']; ?>" media="screen" title="screen" />
	<link rel="stylesheet" type="text/css" href="<? echo $SITE['CSSmenuVer']; ?>" media="screen" title="screen" />
	<link rel="stylesheet" type="text/css" href="<? echo $SITE['CSSColom']; ?>" media="screen" title="screen" />
	<link rel="shortcut icon" href="/img/icon.png" type="image/x-icon" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta name="Keywords" content="weather, Weather, temperature, dew point, humidity, forecast, Davis Vantage Pro, Belgium Weather, Leuven Weather, weather conditions, live weather, live weather conditions, weather data, weather history, Meteohub " />
	<meta name="Description" content="Weather conditions Leuven Wilsele Belgium" />
	<title>Mood / color test</title>

	
	
	<link rel="stylesheet" type="text/css" href="styleMood20.css" media="screen" title="screen" />
</head>

<body style="background-image: url();">  <!-- no class etc background-image: url(img/background-clouds.jpg); -->
<?php
$arr = array('default or invalid class','ws_clouds','ws_cloudsn','ws_mist','ws_moon','ws_pclouds','ws_rain','ws_snow','ws_storm','ws_sun','ws_thunder','orange','pastel','red','green','blue');
for ($i = 0; $i < count($arr); $i++){
	$testClass=$arr[$i];
	$testImg        = str_replace ('ws_','',$testClass);
?>
<div style = "background-color: white; text-align: center;"/><b>this output for <?php echo $testClass;?></b><br /></div>
<div class="<?php echo $testClass;?>" style="background-size: cover; background-image: url(img/background-<?php echo $testImg;?>.jpg);" >
<br />
<!-- <img src="img/background-<?php echo $testClass;?>.jpg" class="bg-xx" alt="" />  -->
<div id="pagina" style="min-width: 500px; width: 500px;">	
	<div id="header" style="height: 80px; width: 98%;">
		<br />header:minimum height, with background: transparent or a color or an image<br />
	</div> <!-- end header   -->
	text with background transparent or a color<br /><br /><br />
	<div id="nav"><ul><li>this text for menu colors etc</li></ul></div>
	text with background transparent or a color<br />
	<div class="blockHead"><h3>this is text for headings in ajax main page</h3></div>
	text with background transparent or a color<br />
	<div><table class="genericTable">
		<tr class="table-top"><td> text</td><td> class =  table-top</td><td> text </td></tr>
		<tr><td> text</td><td> none</td><td> text </td></tr>
		<tr class="row-dark"><td> text</td><td>class =  row dark</td><td> text </td></tr>
		<tr class="row-light"><td> text</td><td>class =   row light</td><td> text </td></tr>
	</table></div>
	text with background transparent or a color<br />
	<div id="footer" width="100%">
		<br />Footer with background transparent or a color<br />
	</div> <!-- end footer   -->
</div> <!-- end pagina   -->
<br />
</div> <!-- end test area   -->
<?php } ?>
</body>
</html>