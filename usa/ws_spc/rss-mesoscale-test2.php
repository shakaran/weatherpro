<?php
// PHP script by Ken True, webmaster@saratoga-weather.org
// rss-tropical-test2.php  Version 1.00 - 14-Jun-2006
// rss-tropical-test2.php  Version 1.01 - 15-Jun-2006 Handle <pre> text
//    added pull of contents from links in feed to fill out the
//    missing description fields from the feeds.
// rss-mesoscale-test.php  Version 1.00 - 18-Aug-2006
//    adapted to handle RSS from the Storm Prediction Center
//    mesoscale discussions.
// rss-mesoscale-test.php  Version 1.01 - 19-Aug-2006 -- added pull text/image
//                          to function
$Version = "V1.01 19-Aug-2006"; # error_reporting(E_ALL ^ E_NOTICE); error_reporting(E_ALL);
//
// script available at http://saratoga-weather.org/scripts.php
//  
// you may copy/modify/use this script as you see fit,
// no warranty is expressed or implied.
//
// This script gets the current RSS Mesoscale Discussions 
// from http://www.spc.noaa.gov/products/spcmdrss.xml and provides either
// a summary (titles only, with links) or details.  It returns 
//'No Mesoscale Discussions are in effect as of Day, Mon dd, yyyy hh:mm:ss UTC'
//  if there are no current discussions.
//
// output: creates XHTML 1.0-Strict HTML page (default)
//
// Options on URL:
//      inc=Y           -- returns only the body code for inclusion
//                         in other webpages.  Omit to return full HTML.
//      summary=Y       -- returns only the titles of the cited discussions
//
// example URL:
//  http://your.website/rss-mesoscale-test.php?inc=Y
//  would return data without HTML header/footer 
//
// Usage:
//  you can use this webpage standalone (customize the HTML portion below)
//  or you can include it in an existing page:
//  no parms:    include("rss-mesoscale-test.php"); 
//  parms:    include("http://your.website/rss-mesoscale-test.php?inc=Y&summary=Y");
//
//
// settings:  
//    other settings are outlined below, and are optional
// 
// settings ----------------------------------------------------------
if(!isset($PHP_SELF)) {$PHP_SELF = $_SERVER['PHP_SELF'];}
$hurlURL = "$PHP_SELF"; //include("http://www.sacreysouthern.com/rss-mesoscale-test.php?inc=Y"); <=== change this default to webpage
//                                         to open for details
//  on that page, you can have the following PHP
//
//  include("http://your.website/rss-mesoscale-test.php?inc=Y");
//   
//
//
$HD = "";  // <=== type of heading for advisorys <$HD>...</$HD>
// end of settings -------------------------------------------------

$RSSURL = 'http://www.spc.noaa.gov/products/spcmdrss.xml';
//  get request parameters 

// full html output or just contents (inc=Y)?
if (isset($_REQUEST['inc']) ) {
        $includeOnly = $_REQUEST['inc']; // any nonblank is ok
}
if (!isset ($includeOnly) ) {$includeOnly = 'Y';}
if ($includeOnly) {$includeOnly = "Y";}

if ( isset($_REQUEST['summary'])) {
  $doSummary = TRUE;
  } else {
  $doSummary = FALSE;
}
// the following is another way to pass the name of the page for testing
if (isset($_REQUEST['detailpage']) ) {
  $hurlURL = $_REQUEST['detailpage'];
}

require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

// begin code -----------------------------------------------------------
$t = pathinfo($PHP_SELF);
$Program = $t['basename'];
$insideitem = false;
$tag = "";
$title = "";
$description = "";
$link = "";
$lastBuildDate = "";
$tracking = "";
$Summary = "";
$WLink = 0;
$PageTitle = "";


// function for XML parsing .. invoked at start of XML element
function startElement($parser, $name, $attrs) {
	global $insideitem, $tag, $title, $description, $link, $lastBuildDate,$doSummary,$Summary, $PageTitle;
	if ($insideitem) {
		$tag = $name;
	} elseif ($name == "ITEM") {
		$insideitem = true;
	}
	if ($name == "LASTBUILDDATE") {
	   $tag = $name;
	}
}

// function for XML parsing .. invoked at end of XML element
// bulk of the work is done here

function endElement($parser, $name) {
	global $insideitem, $tag, $title, $description, $link, $lastBuildDate, $tracking,$doSummary,$Summary,$Zone,$hurlURL,$WLink,$HD, $PageTitle;
	if ($name == "ITEM") {
	    if (preg_match("|No Mesoscale Discussions are in effect|i",$description)) {
		  if (! $doSummary) {
		     printf("%s",htmlspecialchars(trim($description)));
		  }
		  $Summary = htmlspecialchars(trim($description));

		} else {
			$WLink++;
		    $description = preg_replace("|<br>|s","<br />\n",$description);
			$description = preg_replace("|href=http([^>]+)>|is","href=\"http\\1\">",$description);
	    	if (! $doSummary) {
			  printf("<a name=\"WL$WLink\"></a><a href=\"%s\">\n%s</a>\n",			      trim($link),htmlspecialchars(trim($title)));
//		      printf("<p>Updated: <b>%s</b><br />\n<pre>%s</pre></p>\n",			       $lastBuildDate,trim($description)); 
			  print "<!-- len=".strlen($description)." -->\n";
			  if (strlen($description) < 200 ) {
  			      printf("<b>%s</b>\n",trim($description)); 
		    	  $text = grabContents($link);
			      print "<!-- from $link  for $title -->\n";
			      print "<pre>\n$text</pre>\n";
			  } else {
			      $nl = strpos($description,"\n");
				  print "<!-- nl= $nl -->\n";
			      if ($nl >= 80) {
		            printf("<p>%s</p>\n",trim($description)); 
				  } else {
		            printf("<pre>%s</pre>\n",trim($description)); 
				  }
			  
			  }
				}
			$Summary .= "<a href=\"$hurlURL#WL$WLink\"><b>" . 
			     htmlspecialchars(trim($title)) . "</b></a>\n";
		}
		$title = "";
		$description = "";
		$link = "";
		$insideitem = false;
	} // end insideitem
	if ($name == "LASTBUILDDATE") {
	   $tag = '';
	}
	$tracking .= "<!-- tag: '$name' -->\n";
}

// extract character data from within an XML tag

function characterData($parser, $data) {
	global $insideitem, $tag, $title, $description, $link, $lastBuildDate,$doSummary,$Summary, $PageTitle;
	if ($tag == "LASTBUILDDATE") {
		$lastBuildDate .= $data;
	}
	if ($tag == 'TITLE' and (! $insideitem) ) {
	    $PageTitle .= $data;
    }
	
	if ($insideitem) {
      switch ($tag) {
		case "TITLE":
		$title .= $data;
		break;
		case "DESCRIPTION":
		$description .= $data;
		break;
		case "LINK":
		$link .= $data;
		break;
	  } // end switch
	} // end insideitem

}

//  Main Code Start

if (! $includeOnly) {  // omit HTML headers if doing inc=Y
print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>RSS Mesoscale Discussions from Storm Prediction Center</title>
</head>

<body style="background-color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
';
} // end .. only printed if full html needed
print "<!-- $Program - $Version -->\n";
print "<!-- feed source='$RSSURL' -->\n";

// main routine -- get RSS, parse and display
// adapted from SitePoint sample code at:
//  http://www.sitepoint.com/examples/phpxml/sitepointcover.php.txt and article at
//  http://www.sitepoint.com/article/php-xml-parsing-rss-1-0
//  by Ken True -- webmaster@saratoga-weather.org

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");

$fp = fopen("$RSSURL","r")
	or die("Error reading RSS data.");
while ($data = fread($fp, 4096))
	xml_parse($xml_parser, $data, feof($fp))
		or die(sprintf("%s XML error: %s at line %d: %s",
		    $PHP_SELF, 
			xml_error_string(xml_get_error_code($xml_parser)), 
			xml_get_current_line_number($xml_parser),
			htmlspecialchars(trim($data)) ));
fclose($fp);
xml_parser_free($xml_parser);

// end of main program
if ($doSummary) {
  print $Summary;
  if ($WLink) {
    print "Click on link"; 
	if ($WLink > 1) {echo "s";}
	print " to see details on the $WLink Storm Prediction Center discussion";
	if ($WLink > 1) {echo "s"; } else { echo ""; };
	echo ".\n";
  }
}
if (! $includeOnly) {
//print "\n<!-- Zone=$Zone -->\n";
//print $tracking;
print '</body>
</html>
';
} // end - only printed if full html wanted (no inc=Y)
// ----------------------------functions ----------------------------------- 
 
 
function grabContents($dataURL) {
  $URL = trim($dataURL);
  $URLparts = parse_url($URL);
  $PATHparts = pathinfo($URLparts['path']);
  
  print "<!-- url='$URL' -->\n";
  $html = fetchUrlWithoutHanging($URL);
  print "<!-- length html = " . strlen($html) . " -->\n";
  
  preg_match('|<a name="contents">(.*)<!-- End of main body -->|Uis',$html,$stuff);
  $contents = $stuff[1];
  
  preg_match('|<img src="(.*)"\s*alt="(.*)">|Uis',$contents,$stuff);
  $img = $stuff[1];
  $alt = $stuff[2];
  $htmlfile = $PATHparts['basename'];
  
  $imgURL = str_replace($htmlfile,$img,$URL);
  print "<!-- imgURL='$imgURL' -->\n";
  
  preg_match('|<pre>(.*)</pre>|Uis',$contents,$stuff);
  $contentraw = $stuff[1];
//  $content = explode("\n",$contentraw);
//  print "<!-- $dataURL stuff \n";
//  print_r($contentraw);
//  print "-->";

  $imgString = "<img src=\"$imgURL\" alt=\"$alt\" title=\"$alt\"/><br />\n";

  return ($imgString . trim($contentraw) );

}


 function fetchUrlWithoutHanging($url) // thanks to Tom at Carterlake.org for this script fragment
   {
   // Set maximum number of seconds (can have floating-point) to wait for feed before displaying page without feed
   $numberOfSeconds=5;   

   // Suppress error reporting so Web site visitors are unaware if the feed fails
   error_reporting(0);

   // Extract resource path and domain from URL ready for fsockopen

   $URLparts = parse_url($url);
   $domain = $URLparts['host'];
   $resourcePath = $URLparts['path'];

   // Establish a connection
   $socketConnection = fsockopen($domain, 80, $errno, $errstr, $numberOfSeconds);

   if (!$socketConnection)
       {
       // You may wish to remove the following debugging line on a live Web site
       // print("<!-- Network error: $errstr ($errno) -->");
       }    // end if
   else    {
       $xml = '';
       fputs($socketConnection, "GET $resourcePath HTTP/1.0\r\nHost: $domain\r\n\r\n");
   
       // Loop until end of file
       while (!feof($socketConnection))
           {
           $xml .= fgets($socketConnection, 128);
           }    // end while

       fclose ($socketConnection);

       }    // end else

   return($xml);

   }    // end function

?>