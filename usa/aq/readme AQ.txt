readme AQHI USA


1. UNZIP
========
Unzip folder usa and place in main folder of the Leuven-Template (weather2/)

2. MENU
=======
For the AQHI page place in your weather2/wsMenuData.xml

<item	nr      = "15"  show    = "yes"
        caption = "Regional Air Quality"
        link    = "usa/wsAQHIusPage.php"
/>


3. Dashboard items
==================
Two new dashboard modules are included

3.1 dashAQHIus.php  displays current AQHI and forecasted AQHI
        
3.2 dashAQHIusMap.php  displays up to 4 AQ maps


4. Use of dash items
====================
4.1 in old wsDashboard.php =>  
        On the right spot use an   include 'usa/dashAQHIus.php';  or include 'usa/dashAQHIusMap.php';
        
4.2 On wsStartPage.php
        Add in top definition lines
        
$dashboard 	= array();
$dashboard[]	= 'ajax';		// ajax  wdlive meteoplug  wulive steelseries
#$dashboard[]	= 'wdlive'; 		// ajax  wdlive meteoplug  wulive steelseries
#$dashboard[]	= 'wulive';		// ajax  wdlive meteoplug  wulive steelseries

on the spot you want them to appear:

$dashboard 	= array();
$dashboard[]    = 'aqhi_map';
$dashboard[]	= 'ajax';		// ajax  wdlive meteoplug  wulive steelseries
$dashboard[]    = 'aqhi';
#$dashboard[]	= 'wdlive'; 		// ajax  wdlive meteoplug  wulive steelseries
#$dashboard[]	= 'wulive';		// ajax  wdlive meteoplug  wulive steelseries

So here we included one line before the ajax dashboard and one line below.

Now add the some extra lines to wsStartPage.php at the end which is now:

if ($dashboard[$iDash] == 'earth') {
	include ('wsDashEarth.php');
	continue;
}

}
?>
change to
if ($dashboard[$iDash] == 'earth') {
	include ('wsDashEarth.php');
	continue;
}

if ($dashboard[$iDash] == 'aqhi_map') {
	include ('usa/dashAQHIusMap.php');
	continue;
}
if ($dashboard[$iDash] == 'aqhi') {
	include ('usa/dashAQHIus.php');
	continue;
}

}
?>

5. Adapt the scripts:
=====================
1. Nothing to for dashAQHIus.php    and  wsAQHIusPage.php

Find the correct maps for the maps display.

1       => http://www.airnow.gov/
2       => select your state => go => select the nearest / best city/area
        I selected Houston-Galveston-Brazoria
3       Grab the image for the forecast, in Chrome right click => open image in new tab.
        copy the url to the correct line in dashAQHIusMaps.php in lines 25-27
        
        There is a line with a comment mark, that is your example, the other lines are for you to modify
        
        The first (usa) line is probably already correct. 
        
        If you do not want a certain map ==> set a # on the first position of the line and 1 map less will be displayed

IMPORTANT       The date in the forecast map should be completely replace with ***