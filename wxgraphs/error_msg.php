<?php
// Graphs Package V2.1 16th March 2008
//Send a generated image to the browser

function create_image(&$value)
{
    //Set the image width and height
    $width = 300;
    $height = 50;

    //Create the image resource
    $image = ImageCreate($width, $height);

    //We are making three colors, white, black and gray
    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, 0, 0, 0);
    $grey = ImageColorAllocate($image, 204, 204, 204);

    //Make the background black
    ImageFill($image, 0, 0, $grey);

    //Add randomly generated string in white to the image
    ImageString($image, 5, 20, 20, $value, $black);

    //Tell the browser what kind of file is come in
    header("Content-Type: image/jpeg");

    //Output the newly created image in jpeg format
    ImageJpeg($image);

    //Free up resources
    ImageDestroy($image);
}
$string = "Unable to find graphsconf.php";
create_image($string);
exit();
?>
