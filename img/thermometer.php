<?php
//
# version 2.5x  due to errors 
// -------------------begin code ------------------------------------------
if( ! function_exists("gd_info")){
  die("Sorry.. this script requires the GD library in PHP to function.");
}
if (isset($_REQUEST['uom']))  {$uom=$_REQUEST['uom'];} else {$uom='C';}
if (isset($_REQUEST['tmin'])) {$min=floor($_REQUEST['tmin']);} else {$min=-10;}
if (isset($_REQUEST['tmax'])) {$max=ceil($_REQUEST['tmax']);} else {$max=40;}
if (isset($_REQUEST['t']))    {$current=$_REQUEST['t'];} else {$current = (($tMin+$tMax)/2);}

if ($uom == 'F') {		// use Fahrenheit settings
    $Tincr 		= 5;	// increment number of degrees for major/minor ticks on thermometer
    $TMajTick	= 5;	// major tick with value when scale number divisible by this
	$freezePoint= 32;
  } else { 				// use Centigrade settings
    $Tincr 		= 1; 	// increment number of degrees for major/minor ticks on thermometer
    $TMajTick	= 5;	// major tick with value when scale number divisible by this
	$freezePoint= 0;
}

# set thermometer range based on the in max values 
$tMin	= 5*(round(($min-4)/5));
$tMax	= 5*(round(($max+4)/5));
$rangeT	= $tMax - $tMin;		// total temperature range

$BlankGraphic      = 'thermometer-blank.png';       
$image = LoadPNG($BlankGraphic); 
# settings relative to the thermometer image file defines the drawing area for the thermometer filling
# these settings are SPECIFICALLY for the thermometer-blank.png image background 
$minX	= 20; // left
$maxX	= 24; // right
$minY	= 20; // top
$maxY	= 140;// bottom
$rangePx= $maxY - $minY;

$width	= imagesx($image);
$height = imagesy($image);
$font	= 1;

$tx 	= imagecolorallocate($image,0,0,0);		// tick color
$blue	= imagecolorallocate($image,0,0,255);   // below freezing
$red	= imagecolorallocate($image,255,0,0);	// above freezing

$pctT	= ($current	-	$tMin)	/	($rangeT);	// percent for current temperature of range

$Y 		=  $minY + (1-$pctT) * ($rangePx); 	// upper location for fill

# first we fill the whole range with blue color
imagefilledrectangle( $image,
	$minX,
	$Y,
	$maxX,
	$maxY,
	$red );

# Draw tick marks and scale values on left
			 
for ($T = $tMin; $T <= $tMax; $T += $Tincr) { 
	$pctT	= ($T-$tMin)/($rangeT);
	$Y		= $minY + (1-$pctT)*($rangePx);

	if ($T == $freezePoint or ($T % $TMajTick) == 0) { // Major Tick
	    imagefilledrectangle( $image,
            $minX-7 ,
            $Y ,
            $minX-12,
            $Y +1, $tx );
        imagestring($image, $font,
            0,
            $Y - (ImageFontHeight($font)/2),
            sprintf( "%2d", $T),$tx);
	 } else { // Minor tick
     	imagefilledrectangle( $image,
            $minX-7,
            $Y ,
            $minX-9,
            $Y +1, $tx );
	 } 
} // end do ticks legend

# put on minimum temp bar/value
$pctT	= ($min - $tMin)/($rangeT);
$Y		= $minY + (1-$pctT)*($rangePx);
if ($Y > $maxY) {$Y = $maxY;}
imagefilledrectangle( $image,
	$maxX + 18,
	$Y ,
	$maxX + 5,
	$Y +1, $blue );
$tstr	= sprintf('%2d',round($min-.4,0));
$tsize	= strlen($tstr)*imagefontwidth($font+1);
imagestring($image, $font+1,
	$maxX + $tsize -5,
	$Y,
	$tstr,$blue);

# put on normal temp bar/value
$pctT = ($current-$tMin)/($rangeT);
$Y		= $minY + (1-$pctT)*($rangePx);
if ($Y > $maxY) {$Y = $maxY;}
imagefilledrectangle( $image,
	$maxX + 7,
	$Y ,
	$maxX + 5,
	$Y +1, $tx );
$tstr	= sprintf('%2d',round($current,0));
$tsize = strlen($tstr)*imagefontwidth($font+1);
imagestring($image, $font+1,
	$maxX + 9 ,
	$Y - imagefontheight($font-1),
	$tstr,$tx);

# put on maximum temp bar/value
$pctT = ($max-$tMin)/($rangeT);
$Y = (1-$pctT)*($maxY-$minY)+$minY;
imagefilledrectangle( $image,
	$maxX + 18,
	$Y ,
	$maxX + 5,
	$Y +1, $red );
$tstr = sprintf('%2d',round($max+.4,0));
$tsize = strlen($tstr)*imagefontwidth($font+1);
imagestring($image, $font+1,
	$maxX + $tsize - 5 ,
	$Y - imagefontheight($font+1),
	$tstr,$red);

# finished
header("content-type: image/png");
imagepng($image);
imagedestroy($image);



# load PNG image 
function LoadPNG ($imgname) { 
   $im = @imagecreatefrompng ($imgname); /* Attempt to open */ 
   if (!$im) { /* See if it failed */ 
       $im  = imagecreate (150, 30); /* Create a blank image */ 
       $bgc = imagecolorallocate ($im, 255, 255, 255); 
       $tc  = imagecolorallocate ($im, 0, 0, 0); 
       imagefilledrectangle ($im, 0, 0, 150, 30, $bgc); 
       /* Output an errmsg */ 
       imagestring ($im, 1, 5, 5, "Error loading $imgname", $tc); 
   } 
return $im; 
} 
?>