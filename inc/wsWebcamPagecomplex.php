<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
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
$pageName	= 'wsWebcamPagecomplex.php';		// #### change to exact page name
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-01-22';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.01 2015-01-22  version  request tested for meteooudkarspel
# -------------------------------------------------------------------------------------------------
# Example page for displaying a webcam image 
#
# Default a static image is displayed
# If showcam settings are uncommented a button  is displayed for the static image and up to two video feeds
# Lines with ##### have to be adapted to your feeds
#-------------------------------------------------------------------------------------------------
# settings
#$SITE['webcamFeed'] 	= 'index.php?p=99';
if (isset ($_REQUEST['webcam'])    )    {$n             = $_REQUEST['webcam'];}      else {$n           = 1;}
if (is_array($SITE['webcamFeed'] )  )   {$feed          = $SITE['webcamFeed'][$n];}  else {$feed        = $SITE['webcamFeed'];}
if (is_array($SITE['webcamImg']))       {$staticImage   = $SITE['webcamImg'][$n];}   else {$staticImage = $SITE['webcamImg'];}

$name           = 'My webcam Page';
if (is_array($SITE['webcamName'])){
        if (isset ( $SITE['webcamName'][$n]) )  {$name  = $SITE['webcamName'][$n];}  
}

$ourPageNr	= $feed.'&amp;lang='.$lang.$extraP.$skiptopText;
$headText	= langtransstr($name);
$showPic	= false;	$buttonPic	= langtransstr('Static picture');	//  description for the static picture
#$showCam1	= false;	$buttonCam1	= langtransstr('SecuritySpy');		#####	//  description for the video feed
$showCam2	= false;	$buttonCam2	= langtransstr('Ivideon');		#####	//	and maybe another feed

if (isset($_REQUEST['cam1']) && isset($showCam1) ){					// did the visitor choose an existing feed
	$showCam1	= true;
	$choice		= $buttonCam1;
} elseif (isset($_REQUEST['cam2'])  && isset($showCam2)) {				// did the visitor choose an existing feed
	$showCam2 	= true;
	$choice		= $buttonCam2;
} else {																	// default "choice"
	$showPic 	= true;
	$choice		= $buttonPic;
}
$headText	.= ' - '.$choice;

echo '
<div class="blockDiv" style="background-color: grey;">
<div class="blockHead" style="width: 100%">
	<h3 class="blockHead">'.$headText.'</h3>'.PHP_EOL;
if (isset($showCam1) || isset($showCam2) ) {								// do we need a menu to choose static or feed
	echo 
'	<div style="width: 320px; margin: 0 auto;">
		<form method="post" name="menu_select" action="'.$ourPageNr.'">
<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="webcam" value="'.$n.'">
			<button id="pic"  name = "pic"  style="width: 100px;">'.$buttonPic.'</button>'.PHP_EOL;
	if (isset($showCam1) ){
		echo 
'			<button id="cam1" name = "cam1" style="width: 100px;">'.$buttonCam1.'</button>'.PHP_EOL;
	}
	if (isset($showCam2) ){
		echo 	
'			<button id="cam2" name = "cam2" style="width: 100px;">'.$buttonCam2.'</button>'.PHP_EOL;
	}
	echo
'		</form>
	</div>'.PHP_EOL;
}
echo
'</div>'.PHP_EOL;

if ($showPic) {
echo '
<!--         static image -->
<img src="'.$staticImage.'" alt = "Our Webcam" style="width: 100%;"/>
<!--   end of static image -->
';
}
if (isset($showCam1) && $showCam1) {		##### example HTML code for a webcam server
?>
<!--   mac security spy  -->
	<iframe style="border: none; overflow: hidden; margin: 0 auto; width: 100%; height: 600px;"
		src="http://xyz.com:8010/++viewlive?imageSize=838x628&amp;viewMethod=0&amp;cameraNum=0&amp;imageOnly=1&amp; noBorder=1 ">
	   The movie will be displayed here
	</iframe>
<!-- end of security spy  -->
<?php
} 											##### example HTML code for a webcam server
#
#
if (isset($showCam2) && $showCam2) {		##### another example HTML code for a webcam server
?>
<!-- Ivideon  -->
	<iframe style="display:block;margin:0 auto; padding:0; border:0; width: 838px;  height: 628px;" 
		src="http://open.ivideon.com/embed/v2/?server=100-40aa5ba193b42d176c1242f1fe0dd9e4&amp;camera=65536&amp;width=&amp;height=&amp;lang=en">
	The movie will be displayed here</iframe>
	<script src="http://open.ivideon.com/embed/v2/embedded.js"></script>
<!-- end of Ivideon  -->
<?php
} 											##### another example HTML code for a webcam server
?>
</div>

