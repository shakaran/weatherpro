<?php
$page=$_REQUEST['url'];
if (!isset ($_REQUEST['width']) ) {$width='800px'; } else {$width=$_REQUEST['width'];}
echo'
<html>
<head>
<title>Weather</title>
</head>
<body onLoad="window.scrollTo(0,170)">
<iframe  id="iframe" src="'.$page.'" 
style="margin-top: 0px;  height: 5000px; width: '.$width.'; " frameborder="0" scrolling="no">
</iframe>
</body>
</html>
'; 
?>