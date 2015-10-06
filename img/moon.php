<?php
// File and rotation
$filename=$_REQUEST['img'];
$degrees =-$_REQUEST['deg'];

// Content type
header('Content-type: image/gif');

// Load
$source = imagecreatefromgif($filename);

// Rotate
$rotate = imagerotate($source, $degrees, 0);

// Output
imagegif($rotate);
?>