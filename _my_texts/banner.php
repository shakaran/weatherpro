<?php
#-----------------------------------------------------------------------------------------
# Example how to create an extra banner on top of the page of below footer
# The settings are found in wsUserSettings.php
#$SITE['banners']		= true;                 // set to true to display extra banners
#$SITE['bannerTop']		= true;                 // true on top of the page
#$SITE['bannerBottom']		= true;                 // true below the page
#$SITE['bannerTopTxt']		= './banner.php';       // set the file names to display
#$SITE['bannerBottomTxt']	= './banner.txt';
#
# This is the .php example
#
echo '<div class="blockDiv ajaxHead">'.PHP_EOL;          // enclosing div with same 
#                                                       same formatting (borders ,size) as the other boxes  == blockDiv
#                                                       and background colors as dark areas on the page      == ajaxHead
echo '<iframe ';                                        // we use an iframe for this example
echo 'src="./ws_gauge_frame.phplang='.$lang.'&amp;wp='.$SITE['WXsoftware'].'" ';
#                                                       // as this script is included we can use variables
#                                                       $lang as example from within the normal page
echo 'style="width:100%; height: 190px; border: none; overflow: hidden; vertical-align: bottom;"></iframe>'.PHP_EOL;
echo '</div>'.PHP_EOL;
?>