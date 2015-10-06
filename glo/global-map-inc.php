<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'global-map-inc.php';
$pageVersion	= '3.20 2015-09-25';
#-------------------------------------------------------------------------------
# 3.20 2015-09-25 release 2.8 version & remove eror new language
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
############################################################################
# Main processing for Affiliated Regional Networks Global Google Map
#
# Version 2.00 - 27-Nov-2012 - initial release for Google Maps V3 API
#
# note: settings for this script should be done in the calling script, not here.
############################################################################
$GMVersion = "Version 2.00 - 27-Nov-2012";

ws_message (  "<!-- global-map-inc.php - $GMVersion -->\n");

print '<script type="text/javascript">';
print "\n  var langTransLookup = new Object;  // storage area for key/value for current conditions translation\n";

global $firstPart,$secondPart,$thirdPart,$creditPart;
$script = $folder.'global-lang-'.$SITE['lang'].'.html';
if (file_exists($script) ) {
	include $script;
	ws_message (  "<!-- module global-map-inc.php (".__LINE__."): $script loaded. -->");
} 
else {	ws_message (  "<!-- module global-map-inc.php (".__LINE__."): Sorry, no  $script can be found. -->");
        $script = $folder.'global-lang-en.html';
	if (file_exists($script) ) {
		include $script;
		ws_message (  "<!-- module global-map-inc.php (".__LINE__."): $script loaded. -->");
	} 
}
$conditions = array('Dry','Light rain','Moderate drizzle','Moderate rain','Heavy rain','Stopped raining',
	'Dawn','Dusk','Night time',
	'Clear','Sunny','Clear skies','Mostly sunny','A few clouds|','Sc','Scattered clouds','Partly cloudy','Mostly cloudy','Cloudy with clear p','Cloudy with clear patches','Overcast',
);
if(isset($SITE['lang'])) { // processing for Leuven template support

// load translation settings if need be
ws_message (  '<!-- module global-map-inc.php ('.__LINE__.'): Lang='.$Lang.' -->');
ws_message (  '// module global-map-inc.php ('.__LINE__.'): Language translation for conditions ');
$count	= count ($conditions);
for ($n = 0; $n <  $count; $n++) {
	$key	= $conditions[$n];
	$val	= langtransstr($key);
	print "  langTransLookup['$key'] = '$val';\n";
	$t = ucwords(strtolower($key));
	if ($t <> $key) {
	     print "  langTransLookup['$t'] = '$val';\n";
	}
}
ws_message (  '// module global-map-inc.php ('.__LINE__.'): Language translation for wind directions ');

$WindDirs = array( /* used for alt and title tags on wind dir arrow and wind direction display */
	"N", "NNE", "NE", "ENE",
	"E", "ESE", "SE", "SSE",
	"S", "SSW", "SW", "WSW",
	"W", "WNW", "NW", "NNW",
);
foreach ($WindDirs as $key => $val) {
    $trans = langtransstr($val);
    print "  langTransLookup['$val'] = '".$trans."';\n";
}
ws_message (  '// module global-map-inc.php ('.__LINE__.'): Language translation for baro trends');
$BaroTrend = array (
	"Steady", "Rising Slowly", "Rising Rapidly", "Falling Slowly", "Falling Rapidly",
);
 foreach ($BaroTrend as $key => $val) {
	print "  langTransLookup['$val'] = '".langtransstr($val)."';\n";
	$t = ucfirst(strtolower($val));
	if ($t <> $val) {
	     print "  langTransLookup['$t'] = '".langtransstr($val)."';\n";
	}
}
$SITE['langTransloaded'] = true;
} // end special template support

print "</script>\n";
?>

<script type="text/javascript">
//<![CDATA[
<?php
$doDebug = (isset($_REQUEST['debug']))?true:false;
print "var doDebug = ";
print $doDebug?'true':'false';
print "; // enable debug\n";
print "var doLinkTarget = ";
print $doLinkTarget?'true':'false';
print "; // generate links with target=\"_blank\"\n";
print "var doRotatingLegends = ";
print $doRotatingLegends?'true':'false';
print "; // generate rotating legends\n\n";
?>
var condIconsDir = '<?php echo $condIconsDir;?>';
//*
var gmTempUOM = '<?php echo $gmTempUOM; ?>';  // units for Temperature ='C' or ='F';
var gmWindUOM = '<?php echo $gmWindUOM; ?>';  // units for Wind Speed ='mph', ='km/h', ='m/s', ='kts'
var gmBaroUOM = '<?php echo $gmBaroUOM; ?>';  // units for Barometer ='inHg', ='hPa', ='mb'
var gmRainUOM = '<?php echo $gmRainUOM; ?>';  // units for Rain ='in', ='mm'
// global variables
var map = null;
var gmInfoWindow = null;
var gmCurrentInfoWindowMarker = null;
var markerImageRed = null;
var markerImageYellow = null;
var markerImageGreen = null;
var markerImageBlue = null;
var markerImageShadow = null;
var markersArray = [];
var labelsArray = [];
var popupArray = [];
var markerClusterer = null;
var cOptions = { // options for markerCluster
  'gridSize': 30,
  'minimumClusterSize': 4,
  'averageCenter': true,
  'imagePath' : condIconsDir+"m",
  'imageExtension' : 'png'
};

// --- main function ----------------------
function initialize() {
  map = new google.maps.Map(document.getElementById('map'), {
	zoom: <?php echo $gmMapZoom; ?>,
	center: new google.maps.LatLng(<?php echo $gmMapCenter; ?>),
	scaleControl: true,
	scrollwheel: false,
	mapTypeId: google.maps.MapTypeId.<?php echo $gmMapType; ?>
  });


  // Make the info window close when clicking anywhere on the map.
  google.maps.event.addListener(map, 'click', GM_closeInfoWindow);
  // Create a single instance of the InfoWindow object which will be shared
  // by all Map objects to display information to the user.
  gmInfoWindow = new google.maps.InfoWindow({maxWidth:400});

  markerImageRed    = new google.maps.MarkerImage(condIconsDir+"mma_20_red.png", new google.maps.Size(12, 20));
  markerImageBlue   = new google.maps.MarkerImage(condIconsDir+"mma_20_blue.png", new google.maps.Size(12, 20));
  markerImageGreen  = new google.maps.MarkerImage(condIconsDir+"mma_20_green.png", new google.maps.Size(12, 20));
  markerImageYellow = new google.maps.MarkerImage(condIconsDir+"mma_20_yellow.png", new google.maps.Size(12, 20));
  markerImageShadow = new google.maps.MarkerImage(condIconsDir+"mma_20_shadow.png",
												  new google.maps.Size(22, 20),
												  new google.maps.Point(0,0),
												  new google.maps.Point(0,20)
													  );

  GM_generateMarkers();
  markerClusterer = new MarkerClusterer(map, markersArray, cOptions);
  google.maps.event.addListener(map, 'idle', GM_redraw_content);
  if(doRotatingLegends) {GM_rotate_content();} // start the rotation of the content lables
} // end initialize function

//
function GM_generateMarkers() {
// Create the markers based on the data array

//var data = {"markers": [
// {"town":"Swakopmund, Erongo, Namibia, Africa",
//  "lat":"-22.679005",
//  "long":"14.531050",
//  "surl":"other.weather.namsearch.com/swakop/wxindex.php",
//  "fcode":"cam",
//  "nets":"NAMWN",
//  "conds":"Offline"},

  var i;
  for (i = 0; i < data.markers.length; i++) {
	var lat = parseFloat(data.markers[i].lat);
	var lng = parseFloat(data.markers[i].long);
	var town = data.markers[i].town;
	var stationURL = "http://"+data.markers[i].surl;
	var rawnets = data.markers[i].nets;
	var nets = GM_gen_netlinks(rawnets);
	var fcode = data.markers[i].fcode;
	var features = GM_gen_features(fcode);
	var rawconds = data.markers[i].conds;
	var conds = GM_gen_conds(rawconds);
	var latLng = new google.maps.LatLng(lat,lng);
	var title = data.markers[i].town+' ('+data.markers[i].nets+")";
	var useMarkerIcon = markerImageBlue;  // default to WX marker
	if (fcode == 'all') { useMarkerIcon = markerImageRed;     }
	if (fcode == 'lgt') { useMarkerIcon = markerImageYellow;  }
	if (fcode == 'cam') { useMarkerIcon = markerImageGreen;   }
	var tgt = '';
	if(doLinkTarget) {tgt = ' target="_blank"'; }
	
	var popupHtmlTemplate = "<div style=\"line-height:1.35;overflow:hidden;white-space:nowrap;\"><small><a href=\""+stationURL+"\""+tgt+">"+town+"</a><br/>"+features+
	   " Nets: "+nets+"<br clear=\"left\"/><hr/></small>"+
	   "CONDITIONS"+"&nbsp;<br clear=\"left\"/></div>";

	var rotateHtml = '';
	if (doRotatingLegends) {rotateHtml = GM_genRotateHtml(rawconds);}
	GM_createMarker(map,latLng, useMarkerIcon, markerImageShadow, title, popupHtmlTemplate,conds,rotateHtml);

  }
}

// internal function: create the marker with all the fiddly bits included
function  GM_createMarker (map,latLng, useMarkerIcon, markerImageShadow, title, popupHtmlTemplate,conds,rotateHtml) {

  var marker = new google.maps.Marker({
	map: map,
	position: latLng,
	clickable: true,
	draggable: false,
	icon: useMarkerIcon,
	shadow: markerImageShadow,
	title: title
  });
  var popupHtml = popupHtmlTemplate;
  marker.popupHtml = popupHtml.replace("CONDITIONS",conds);
  if(doRotatingLegends) {
	var label = new Label({
	 map: map
	});
	label.bindTo('position', marker, 'position');
	label.set('textnew',rotateHtml);
	label.bindTo('text', marker, 'position');
	marker.legend = label;
	marker.rotateHtml = rotateHtml; 
  }
  google.maps.event.addListener(marker, 'click', function() {
	GM_openInfoWindow(marker);
  });
  // save this for later use
  markersArray.push(marker);
  popupArray.push(popupHtmlTemplate);
  if(doRotatingLegends) { labelsArray.push(label); }
}

// function called when clicking anywhere on the map and closes the info window.
function GM_closeInfoWindow () {
  gmInfoWindow.close();
  gmCurrentInfoWindowMarker = null;
};

// Opens the shared info window when marker is clicked
function GM_openInfoWindow (marker) {
  gmInfoWindow.setContent(marker.popupHtml);
  gmInfoWindow.open(map, marker);
  gmCurrentInfoWindowMarker = marker;
}

// generate HTML for features based on fcode (only four options there)
function GM_gen_features(fcode) {
  var features = '';

  if (fcode == 'all') {
	 features = "<img src=\""+condIconsDir+"feat_all.jpg\" alt=\"<?php langtrans('Weather, Lightning, WebCam'); ?>\" title=\"<?php langtrans('Weather, Lightning, WebCam'); ?>\" align=\"left\" />";
  }
  else if (fcode == 'lgt') {
	 features = "<img src=\""+condIconsDir+"feat_li.jpg\" alt=\"<?php langtrans('Weather, Lightning'); ?>\" title=\"<?php langtrans('Weather, Lightning'); ?>\" align=\"left\" />";
  }
  else if (fcode == 'cam') {
	 features = "<img src=\""+condIconsDir+"feat_cam.jpg\" alt=\"<?php langtrans('Weather, WebCam'); ?>\" title=\"<?php langtrans('Weather, WebCam'); ?>\" align=\"left\" />";
  }
  else {
	 features = "<img src=\""+condIconsDir+"feat_we.jpg\" alt=\"<?php langtrans('Weather'); ?>\" title=\"<?php langtrans('Weather'); ?>\" align=\"left\" />";
  }
  return(features);
}

// generate HTML for network links based on rawnets net membership
function GM_gen_netlinks(rawnets) {
  var nets = rawnets.split(',');
  var netHtml = '';
  var tgt = '';
  if(doLinkTarget) {tgt = ' target="_blank"'; }

  // JSON "AKWN":{
  // "name":"Alaskan Weather Network",
  // "url":"http://alaskanweather.net/",
  // "short":"Alaska",
  // "region":"USA",
  // "units":"F,mph,inHg,in,ft"}

  for (var i = 0;i<nets.length;i++) {
	var net = nets[i];
	netHtml += "<a href=\""+data.nets[net]['url']+"\" title=\""+data.nets[net]['name']+"\""+tgt+">"+
	   data.nets[net]['short']+"</a> ";
  }

  return(netHtml);
}

// generate infowindow popup HTML from the rawconds JSON input
function GM_gen_conds(rawconds) {
// 'day_partly_cloudy.gif,Partly Cloudy,78 F,67%,66 F,ENE,10 mph,11,0.00 in,29.94 inHg,Steady'
//   0                     1             2    3  4    5   6       7  8       9          10

  var conds = rawconds.split(',');
  if (conds[0] == 'Offline') {
	  return ("<small><?php langtrans('Conditions not available'); ?>.</small>");
  }

  var condsHtml = '<small>';
  var testPattern = /.gif$/;
  if (testPattern.test(conds[0]) && condIconsDir) {
	  conds[0] = conds[0].replace(".gif",".png"); // use the .png images
	condsHtml += "<img src=\""+condIconsDir+conds[0]+"\" height=\"25\" width=\"25\" alt=\""+GM_langTrans(conds[1])+"\" title=\""+GM_langTrans(conds[1])+"\" align=\"left\" /> "+GM_langTrans(conds[1])+" <br clear=\"left\"/> ";
  } else {
	condsHtml += "";
  }

	condsHtml += "<?php langtrans('Temp'); ?>: <b>"+GM_convertTemp(conds[2])+"&deg;"+gmTempUOM+"</b>, <?php langtrans('Hum'); ?>: <b>"+conds[3]+"</b>,";
  if(conds[4] != '') {
	condsHtml += " <?php langtrans('DewPT'); ?>: <b>"+GM_convertTemp(conds[4])+"&deg;"+gmTempUOM+"</b>";
  }
  condsHtml += "<br/>";

  condsHtml += "<?php langtrans('Wind'); ?>: ";
  var windTranslated = GM_langTrans(conds[5]);
  var gustUOM = conds[6].split(' ');

  if (conds[5] == '' || conds[5] == '---') {
	condsHtml += "n/a ";
  } else {
	condsHtml += "<b>"+windTranslated+"</b> <img src=\""+condIconsDir+conds[5]+".gif\" height=\"14\" width=\"14\" alt=\"<?php langtrans('Wind from'); ?> "+windTranslated+"\" title=\"<?php langtrans('Wind from'); ?> "+windTranslated+"\" />";
	condsHtml +=  " <b>"+GM_convertWind(conds[6])+" "+gmWindUOM+"</b>, ";
	if(conds[7] != '') {condsHtml +=  "<?php langtrans('Gust'); ?>: <b>"+GM_convertWind(conds[7]+" "+gustUOM[1])+"</b>, ";}
  }

  condsHtml += "<?php langtrans('Rain'); ?>: <b>"+GM_convertRain(conds[8])+" "+gmRainUOM+"</b><br/>";

  condsHtml += "<?php langtrans('Baro'); ?>: <b>"+GM_convertBaro(conds[9])+" "+gmBaroUOM+"</b> ("+GM_langTrans(conds[10])+")";
  if(doDebug) {
    condsHtml += "<br/><small>Station units<br/>T="+conds[2]+", DP="+conds[4]+", W="+conds[6]+", G="+conds[7]+", R="+conds[8]+", B="+conds[9]+"</small>";
  }
  condsHtml += "</small>";
  return(condsHtml)
}

// generate rotating HTML from the rawconds JSON input
function GM_genRotateHtml(rawconds) {
// 'day_partly_cloudy.gif,Partly Cloudy,78 F,67%,66 F,ENE,10 mph,11,0.00 in,29.94 inHg,Steady'
//   0                     1             2    3  4    5   6       7  8       9          10

  var conds = rawconds.split(',');
  if (conds[0] == 'Offline') {
	  return ("N/A");
  }

  var rotateHtml = '';
  
  rotateHtml += '<span class="GMcontent0">'+GM_convertTemp(conds[2])+'</span>';
  rotateHtml += '<span class="GMcontent1">'+conds[3]+'</span>';
  rotateHtml += '<span class="GMcontent2">'+GM_langTrans(conds[5])+' '+GM_convertWind(conds[6])+'</span>';
  rotateHtml += '<span class="GMcontent3">'+GM_convertRain(conds[8])+'</span>';
  rotateHtml += '<span class="GMcontent4">'+GM_convertBaro(conds[9])+'</span>';
  rotateHtml += '<span class="GMcontent5">'+GM_langTrans(conds[10])+'</span>';

  return (rotateHtml);
}

// utility functions to handle conversions from JSON data to desired units-of-measure

// convert input temperature to C then to target gmTempUOM value
function GM_convertTemp ( inrawtemp ) {
  var p = inrawtemp.split(" ");
  var cpat=/C/i;
  var rawtemp = p[0];
  if(cpat.test(p[1])) { // temperature already in C
	  rawtemp = p[0] * 1.0;
  } else { // convert F to C
	  rawtemp = (p[0] - 32.0) * (100.0/(212.0-32.0));
  }
  // now convert to gmTempUOM value
  if (cpat.test(gmTempUOM)) { // leave as C
	  return (rawtemp * 1.0).toFixed(0);
  } else {  // convert C to F
	  return ((1.8 * rawtemp) + 32.0).toFixed(0);
  }
}

// convert input wind to knots, then to target gmWindUOM value
function GM_convertWind  ( inrawwind ) {
  var p = inrawwind.split(" ");
  var rawwind = p[0];
  var cpat=/kts|knots/i;
  if(cpat.test(p[1])) { // wind already in knots
	  rawwind = p[0] * 1.0;
  }
  cpat=/mph/i;
  if(cpat.test(p[1])) { // wind in mph -> knots
	  rawwind = p[0] * 0.868976242;
  }
  cpat=/kmh|km\/h/i;
  if(cpat.test(p[1])) { // wind in kmh -> knots
	  rawwind = p[0] * 0.539956803;
  }
  cpat=/mps|m\/s/i;
  if(cpat.test(p[1])) { // wind in mps -> knots
	  rawwind = p[0] * 1.9438444924406;
  }

  // now convert knots to desired gmWindUOM value
  cpat=/kts|knots/i;
  if(cpat.test(gmWindUOM)) {
    return (rawwind * 1.0).toFixed(0);
  }
  cpat=/mph/i;
  if (cpat.test(gmWindUOM)) { // convert knots to mph
	 return (rawwind * 1.1507794).toFixed(0);
  }
  cpat=/mps|m\/s/i;
  if (cpat.test(gmWindUOM)) { // convert knots to m/s
	return (rawwind * 0.514444444).toFixed(1);
  }
  // convert knots to km/hr
	return (rawwind * 1.852).toFixed(0);
}

// convert input pressure to hPa then to gmBaroUOM value
function GM_convertBaro ( inrawpress ) {
  var cpat=/mb|hpa/i;
  var p = inrawpress.split(" ");
  var rawbaro = p[0];
  if(cpat.test(p[1])) { // baro already in mb/hPa
	  rawbaro = p[0] * 1.0;
  } else { // convert inHg to mb/hPa
	  rawbaro = p[0] * 33.86;
  }
  // now convert to target gmBaroUOM value
  if (cpat.test(gmBaroUOM)) {
	 return(rawbaro * 1.0).toFixed(1); // leave in hPa
  } else { // convert hPa to inHg
	 return(rawbaro  / 33.86388158).toFixed(2);
  }
}

// convert input rain to mm then to target gmRainUOM value
function GM_convertRain ( inrawrain ) {
  var cpat=/mm/i;
  var p = inrawrain.split(" ");
  var rawrain = p[0];
  if(cpat.test(p[1])) { // rain already in mm
	  rawrain = p[0] * 1.0;
  } else { // convert inches to mm
	  rawrain = p[0] * 25.4;
  }
  // now convert to gmRainUOM value
  if (cpat.test(gmRainUOM)) {  // leave in mm
	 return (rawrain * 1.0).toFixed(1);
  } else { // convert mm to inches
	 return (rawrain * .0393700787).toFixed(2);
  }
}

// regenerate rotating and popup conditions after change of UOM
function GM_updateConditions () {
  for (var i=0;i<data.markers.length;i++) {
	var rawconds = data.markers[i].conds;
	var conds = GM_gen_conds(rawconds);
	var rotateHtml = GM_genRotateHtml(rawconds);
	var popupHtmlTemplate = popupArray[i];
	var marker = markersArray[i];
	marker.popupHtml = popupHtmlTemplate.replace("CONDITIONS",conds);
	if(doRotatingLegends) {
	  var label = marker.legend;
	  label.set('textnew',rotateHtml);
	  label.bindTo('text', marker, 'position');
	  marker.rotateHtml = rotateHtml;
	}
  }
  if(gmCurrentInfoWindowMarker != null) { // common infoWindow is open.. close/reopen to refresh
    gmInfoWindow.close();
    gmInfoWindow.setContent(gmCurrentInfoWindowMarker.popupHtml);
    gmInfoWindow.open(map, gmCurrentInfoWindowMarker);
  }
}

// Change Temperature UOM to selection from combo-box
function GM_ChangeSelTemp (selValue) {
	gmTempUOM = selValue;
	var element = document.getElementById('curTempUOM');
	if (element) {element.innerHTML = selValue;	}
	if(GMtimeoutID != null) {clearTimeout(GMtimeoutID); }
	GM_set_run(0); // stop rotation
	GM_updateConditions();
	GM_set_run(1); // start rotation
}

// Change Wind UOM to selection from combo-box
function GM_ChangeSelWind (selValue) {
	gmWindUOM = selValue;
	var element = document.getElementById('curWindUOM');
	if (element) {element.innerHTML = selValue;	}
	if(GMtimeoutID != null) {clearTimeout(GMtimeoutID); }
	GM_set_run(0); // stop rotation
	GM_updateConditions();
	GM_set_run(1); // start rotation
}

// Change Rain UOM to selection from combo-box
function GM_ChangeSelRain (selValue) {
	gmRainUOM = selValue;
	var element = document.getElementById('curRainUOM');
	if (element) {element.innerHTML = selValue;	}
	if(GMtimeoutID != null) {clearTimeout(GMtimeoutID); }
	GM_set_run(0); // stop rotation
	GM_updateConditions();
	GM_set_run(1); // start rotation
}

// Change Barometer UOM to selection from combo-box
function GM_ChangeSelBaro (selValue) {
	gmBaroUOM = selValue;
	var element = document.getElementById('curBaroUOM');
	if (element) {element.innerHTML = selValue;	}
	if(GMtimeoutID != null) {clearTimeout(GMtimeoutID); }
	GM_set_run(0); // stop rotation
	GM_updateConditions();
	GM_set_run(1); // start rotation
}
function GM_langTrans(words) {

	var newwords = words;

	if(langTransLookup[words]) { newwords = langTransLookup[words]; }

	return(newwords);

}

// now load it all up and display the map

google.maps.event.addDomListener(window, 'load', initialize);
// ]]>
    </script>
<div id="map-container" style="width: 100%; margin: 0 auto;">
  <div id="map" style="width: 100%; margin: 0 auto;"></div>
  <table style="width: 60%; margin: 0 auto; border: none;">
  <tr>
    <?php if($doRotatingLegends) { ?>
    <td style="">
    <form action="#">
      <div id="GMcontrols">
        <input type="button" value="<?php echo MESO_RUN; ?>"   name="run"   onclick="GM_set_run(1);" />
        <input type="button" value="<?php echo MESO_PAUSE; ?>" name="pause" onclick="GM_set_run(0);" />
        <input type="button" value="<?php echo MESO_STEP; ?>"  name="step"  onclick="GM_step_content();" />
      </div>
    </form>
    <?php } else { ?>
    <td>&nbsp;
    <?php } // end no rotating legends ?>
    </td>
    <?php if($doRotatingLegends) { ?>
    <td style="">
    <div id="legend">
      <span class="GMcontent0" style="text-align: left;"><?php langtrans('Temperature'); ?> [ <span id="curTempUOM"><?php print $gmTempUOM; ?></span>&deg; ]</span>
      <span class="GMcontent1" style="text-align: left;"><?php langtrans('Humidity'); ?> [ % ]</span>
      <span class="GMcontent2" style="text-align: left;"><?php langtrans('Wind'); ?> [ <span id="curWindUOM"><?php print $gmWindUOM; ?></span> ]</span>
      <span class="GMcontent3" style="text-align: left;"><?php langtrans('Rain'); ?> [ <span id="curRainUOM"><?php print $gmRainUOM; ?></span> ]</span>
      <span class="GMcontent4" style="text-align: left;"><?php langtrans('Barometer'); ?> [ <span id="curBaroUOM"><?php print $gmBaroUOM; ?></span> ]</span>
      <span class="GMcontent5" style="text-align: left;"><?php langtrans('Baro Trend'); ?></span>
    </div>
    <?php } else { ?>
    <td>&nbsp;
    <?php } // end no rotating legends ?>
    </td>
    <td style="text-align:right;">
    <form action="#">
      <div id="GMcontrolsUOM">
      <select id="selTemp" name="selTemp" onchange="GM_ChangeSelTemp(this.value);">
<?php
foreach (array('C','F') as $i => $val) {
  if($val == $gmTempUOM) {
    print "        <option value=\"$val\" selected=\"selected\">&deg;$val</option>\n";
  } else {
    print "        <option value=\"$val\">&deg;$val</option>\n";
  }
}
?>
      </select>
      <select id="selWind" name="selWind" onchange="GM_ChangeSelWind(this.value);">
<?php
foreach (array('km/h','mph','m/s','kts') as $i => $val) {
  if($val == $gmWindUOM) {
    print "        <option value=\"$val\" selected=\"selected\">$val</option>\n";
  } else {
    print "        <option value=\"$val\">$val</option>\n";
  }
}
?>
      </select>
      <select id="selRain" name="selRain" onchange="GM_ChangeSelRain(this.value);">
<?php
foreach (array('mm','in') as $i => $val) {
  if($val == $gmRainUOM) {
    print "        <option value=\"$val\" selected=\"selected\">$val</option>\n";
  } else {
    print "        <option value=\"$val\">$val</option>\n";
  }
}
?>
      </select>
      <select id="selBaro" name="selBaro" onchange="GM_ChangeSelBaro(this.value);">
<?php
foreach (array('hPa','inHg','mb') as $i => $val) {
  if($val == $gmBaroUOM) {
    print "        <option value=\"$val\" selected=\"selected\">$val</option>\n";
  } else {
    print "        <option value=\"$val\">$val</option>\n";
  }
}
?>
      </select>
      </div>
    </form>
    </td>
   </tr>
  </table>
  </div>

<?php    
if(isset($firstPart)) {
   	echo '<div style="width: 60%; margin: 0 auto; text-align: center;">'.$firstPart.'</div>'.PHP_EOL; 
}
echo '
<p style="text-align: center;"><small>
[<img src="'.$condIconsDir.'mma_20_red.png" 	height="20" width="12" alt="Weather, Webcam, Lightning" style="vertical-align:middle"/>] Weather, Lightning, WebCam,
[<img src="'.$condIconsDir.'mma_20_yellow.png" 	height="20" width="12" alt="Weather, Lightning" style="vertical-align:middle"/>] Weather, Lightning,
[<img src="'.$condIconsDir.'mma_20_green.png" 	height="20" width="12" alt="Weather, Webcam" style="vertical-align:middle"/>] Weather, WebCam,
[<img src="'.$condIconsDir.'mma_20_blue.png" 	height="20" width="12" alt="Weather"  style="vertical-align:middle"/>] Weather</small></p>';
?>
<script type="text/javascript">
//<![CDATA[
/*
This is the collection of scripts to support the global Google map for the display of weather conditions
from the Regional Affillated Weather Networks.

http://saratoga-weather.org/scripts-mesomap.php#googlemap

// Version 2.00 - 27-Nov-2013 - initial release for Google Map API V3

Note: no customization of this file is required.  
  
*/
// ----------------------------------------------------------------------
// Rotate content display -- Ken True -- saratoga-weather.org
//
// --------- begin settings ---------------------------------------------------------------
var GMrotatedelay=3000; // Rotate display every 3 secs (= 3000 ms)
// --------- end settings -----------------------------------------------------------------
//
// you shouldn\'t need to change things below this line
//
var ie4=document.all;
var ie8 = false;
if (ie4 && /MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
 var ieversion=new Number(RegExp.$1) // capture x.x portion and store as a number
 if (ieversion>=8) {
   ie4=false;
   ie8=true;
 }
}
var GMcurindex = 0;
var GMtotalcontent = 0;
var GMrunrotation = 1;
var browser = navigator.appName;
var GMtimeoutID = null;

function get_content_tags ( tag ) {
// search all the span tags and return the list with class=tag 
//
  if (ie4 && browser != "Opera" && ! ie8) {
    var elem = document.getElementsByTagName("span");
	var lookfor = "className";
  } else {
    var elem = document.getElementsByTagName("span");
	var lookfor = "class";
  }
     var arr = new Array();
     for(var i = 0, iarr = 0; i < elem.length; i++) {
          var att = elem[i].getAttribute(lookfor);
          if(att == tag) {
               arr[iarr] = elem[i];
               iarr++;
          }
     }

     return arr;
}


function GM_get_total() {

  GMtotalcontent = 5; // content0 .. content4

}

function GM_contract_all() {
  for (var y=0;y<GMtotalcontent;y++) {
      var elements = get_content_tags("GMcontent"+y);
	  var numelements = elements.length;
//	  alert("GM_contract_all: content"+y+" numelements="+numelements);
	  for (var index=0;index!=numelements;index++) {
         var element = elements[index];
		 element.style.display="none";
      }
  }
}

function GM_expand_one(which) {
  GM_contract_all();
  var elements = get_content_tags("GMcontent"+which);
  var numelements = elements.length;
  for (var index=0;index!=numelements;index++) {
     var element = elements[index];
	 element.style.display="inline";
  }
}
function GM_step_content() {
  GM_get_total();
  GM_contract_all();
  GMcurindex=(GMcurindex<GMtotalcontent-1)? GMcurindex+1: 0;
  GM_expand_one(GMcurindex);
}

function GM_set_run(val) {
  GMrunrotation = val;
  GM_rotate_content();
}

function GM_rotate_content() {
  if (GMrunrotation) {
    GM_get_total();
    GM_contract_all();
    GM_expand_one(GMcurindex);
    GMcurindex=(GMcurindex<GMtotalcontent-1)? GMcurindex+1: 0;
    GMtimeoutID = setTimeout("GM_rotate_content()",GMrotatedelay);
  }
}

function GM_redraw_content() {
    GM_get_total();
    GM_contract_all();
    GM_expand_one(GMcurindex);
}
// --------------------------------------------------------------------------------------------
// Define the overlay, derived from google.maps.OverlayView
// Label script from http://blog.mridey.com/2011/05/label-overlay-example-for-google-maps.html
// Define the overlay, derived from google.maps.OverlayView
function Label(opt_options) {
  // Initialization
  this.setValues(opt_options);
  
  // Label specific
  var span = this.span_ = document.createElement('span');
  /* span.style.cssText = 'position: relative; left: -50%; top: -8px; ' +
					  'white-space: nowrap; border: 1px solid blue; ' +
					  'padding: 2px; background-color: white;font-size: 8px;';
  */
  span.style.cssText = 'position: relative; left: -50%; top: -10px; ' +
					  'white-space: nowrap; border: none; ' +
					  'padding: 1px; font-size: 8px; color: blue; background-color: white;';
  
  
  var div = this.div_ = document.createElement('div');
  div.appendChild(span);
  div.style.cssText = 'position: absolute; display: none';
};
Label.prototype = new google.maps.OverlayView;

// Implement onAdd
Label.prototype.onAdd = function() {
  var pane = this.getPanes().overlayLayer;
  pane.appendChild(this.div_);
  
  // Ensures the label is redrawn if the text or position is changed.
  var me = this;
  this.listeners_ = [
   google.maps.event.addListener(this, 'position_changed',
	   function() { me.draw(); }),
   google.maps.event.addListener(this, 'text_changed',
	   function() { me.draw(); })
  ];
};

// Implement onRemove
Label.prototype.onRemove = function() {
  this.div_.parentNode.removeChild(this.div_);
  
  // Label is removed from the map, stop updating its position/text.
  for (var i = 0, I = this.listeners_.length; i < I; ++i) {
   google.maps.event.removeListener(this.listeners_[i]);
  }
};

// Implement draw
Label.prototype.draw = function() {
  var projection = this.getProjection();
  var position = projection.fromLatLngToDivPixel(this.get('position'));
  
  var div = this.div_;
  div.style.left = position.x + 'px';
  div.style.top = position.y + 'px';
  div.style.display = 'block';
  
  this.span_.innerHTML = this.get('textnew');
};
 
// --------------------------------------------------------------------------------------------
// MarkerClusterer script adapted for use with Label by Ken True - 24-Nov-2013
//
// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @externs_url http://closure-compiler.googlecode.com/svn/trunk/contrib/externs/maps/google_maps_api_v3_3.js
// ==/ClosureCompiler==

/**
 * @name MarkerClusterer for Google Maps v3
 * @version version 1.0
 * @author Luke Mahe
 * @fileoverview
 * The library creates and manages per-zoom-level clusters for large amounts of
 * markers.
 * <br/>
 * This is a v3 implementation of the
 * <a href="http://gmaps-utility-library-dev.googlecode.com/svn/tags/markerclusterer/"
 * >v2 MarkerClusterer</a>.
 */

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


/**
 * A Marker Clusterer that clusters markers.
 *
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>=} opt_markers Optional markers to add to
 *   the cluster.
 * @param {Object=} opt_options support the following options:
 *     'gridSize': (number) The grid size of a cluster in pixels.
 *     'maxZoom': (number) The maximum zoom level that a marker can be part of a
 *                cluster.
 *     'zoomOnClick': (boolean) Whether the default behaviour of clicking on a
 *                    cluster is to zoom into it.
 *     'averageCenter': (boolean) Wether the center of each cluster should be
 *                      the average of all markers in the cluster.
 *     'minimumClusterSize': (number) The minimum number of markers to be in a
 *                           cluster before the markers are hidden and a count
 *                           is shown.
 *     'styles': (object) An object that has style properties:
 *       'url': (string) The image url.
 *       'height': (number) The image height.
 *       'width': (number) The image width.
 *       'anchor': (Array) The anchor position of the label text.
 *       'textColor': (string) The text color.
 *       'textSize': (number) The text size.
 *       'backgroundPosition': (string) The position of the backgound x, y.
 * @constructor
 * @extends google.maps.OverlayView
 */
function MarkerClusterer(map, opt_markers, opt_options) {
  // MarkerClusterer implements google.maps.OverlayView interface. We use the
  // extend function to extend MarkerClusterer with google.maps.OverlayView
  // because it might not always be available when the code is defined so we
  // look for it at the last possible moment. If it doesn't exist now then
  // there is no point going ahead :)
  this.extend(MarkerClusterer, google.maps.OverlayView);
  this.map_ = map;

  /**
   * @type {Array.<google.maps.Marker>}
   * @private
   */
  this.markers_ = [];

  /**
   *  @type {Array.<Cluster>}
   */
  this.clusters_ = [];

  this.sizes = [53, 56, 66, 78, 90];

  /**
   * @private
   */
  this.styles_ = [];

  /**
   * @type {boolean}
   * @private
   */
  this.ready_ = false;

  var options = opt_options || {};

  /**
   * @type {number}
   * @private
   */
  this.gridSize_ = options['gridSize'] || 60;

  /**
   * @private
   */
  this.minClusterSize_ = options['minimumClusterSize'] || 2;


  /**
   * @type {?number}
   * @private
   */
  this.maxZoom_ = options['maxZoom'] || null;

  this.styles_ = options['styles'] || [];

  /**
   * @type {string}
   * @private
   */
  this.imagePath_ = options['imagePath'] ||
      this.MARKER_CLUSTER_IMAGE_PATH_;

  /**
   * @type {string}
   * @private
   */
  this.imageExtension_ = options['imageExtension'] ||
      this.MARKER_CLUSTER_IMAGE_EXTENSION_;

  /**
   * @type {boolean}
   * @private
   */
  this.zoomOnClick_ = true;

  if (options['zoomOnClick'] != undefined) {
    this.zoomOnClick_ = options['zoomOnClick'];
  }

  /**
   * @type {boolean}
   * @private
   */
  this.averageCenter_ = false;

  if (options['averageCenter'] != undefined) {
    this.averageCenter_ = options['averageCenter'];
  }

  this.setupStyles_();

  this.setMap(map);

  /**
   * @type {number}
   * @private
   */
  this.prevZoom_ = this.map_.getZoom();

  // Add the map event listeners
  var that = this;
  google.maps.event.addListener(this.map_, 'zoom_changed', function() {
    var zoom = that.map_.getZoom();

    if (that.prevZoom_ != zoom) {
      that.prevZoom_ = zoom;
      that.resetViewport();
    }
  });

  google.maps.event.addListener(this.map_, 'idle', function() {
    that.redraw();
  });

  // Finally, add the markers
  if (opt_markers && opt_markers.length) {
    this.addMarkers(opt_markers, false);
  }
}


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ =
    'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/' +
    'images/m';


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';


/**
 * Extends a objects prototype by anothers.
 *
 * @param {Object} obj1 The object to be extended.
 * @param {Object} obj2 The object to extend with.
 * @return {Object} The new extended object.
 * @ignore
 */
MarkerClusterer.prototype.extend = function(obj1, obj2) {
  return (function(object) {
    for (var property in object.prototype) {
      this.prototype[property] = object.prototype[property];
    }
    return this;
  }).apply(obj1, [obj2]);
};


/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.onAdd = function() {
  this.setReady_(true);
};

/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.draw = function() {};

/**
 * Sets up the styles object.
 *
 * @private
 */
MarkerClusterer.prototype.setupStyles_ = function() {
  if (this.styles_.length) {
    return;
  }

  for (var i = 0, size; size = this.sizes[i]; i++) {
    this.styles_.push({
      url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
      height: size,
      width: size
    });
  }
};

/**
 *  Fit the map to the bounds of the markers in the clusterer.
 */
MarkerClusterer.prototype.fitMapToMarkers = function() {
  var markers = this.getMarkers();
  var bounds = new google.maps.LatLngBounds();
  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }

  this.map_.fitBounds(bounds);
};


/**
 *  Sets the styles.
 *
 *  @param {Object} styles The style to set.
 */
MarkerClusterer.prototype.setStyles = function(styles) {
  this.styles_ = styles;
};


/**
 *  Gets the styles.
 *
 *  @return {Object} The styles object.
 */
MarkerClusterer.prototype.getStyles = function() {
  return this.styles_;
};


/**
 * Whether zoom on click is set.
 *
 * @return {boolean} True if zoomOnClick_ is set.
 */
MarkerClusterer.prototype.isZoomOnClick = function() {
  return this.zoomOnClick_;
};

/**
 * Whether average center is set.
 *
 * @return {boolean} True if averageCenter_ is set.
 */
MarkerClusterer.prototype.isAverageCenter = function() {
  return this.averageCenter_;
};


/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The markers.
 */
MarkerClusterer.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 *  Returns the number of markers in the clusterer
 *
 *  @return {Number} The number of markers.
 */
MarkerClusterer.prototype.getTotalMarkers = function() {
  return this.markers_.length;
};


/**
 *  Sets the max zoom for the clusterer.
 *
 *  @param {number} maxZoom The max zoom level.
 */
MarkerClusterer.prototype.setMaxZoom = function(maxZoom) {
  this.maxZoom_ = maxZoom;
};


/**
 *  Gets the max zoom for the clusterer.
 *
 *  @return {number} The max zoom level.
 */
MarkerClusterer.prototype.getMaxZoom = function() {
  return this.maxZoom_;
};


/**
 *  The function for calculating the cluster icon image.
 *
 *  @param {Array.<google.maps.Marker>} markers The markers in the clusterer.
 *  @param {number} numStyles The number of styles available.
 *  @return {Object} A object properties: 'text' (string) and 'index' (number).
 *  @private
 */
MarkerClusterer.prototype.calculator_ = function(markers, numStyles) {
  var index = 0;
  var count = markers.length;
  var dv = count;
  while (dv !== 0) {
    dv = parseInt(dv / 10, 10);
    index++;
  }

  index = Math.min(index, numStyles);
  return {
    text: count,
    index: index
  };
};


/**
 * Set the calculator function.
 *
 * @param {function(Array, number)} calculator The function to set as the
 *     calculator. The function should return a object properties:
 *     'text' (string) and 'index' (number).
 *
 */
MarkerClusterer.prototype.setCalculator = function(calculator) {
  this.calculator_ = calculator;
};


/**
 * Get the calculator function.
 *
 * @return {function(Array, number)} the calculator function.
 */
MarkerClusterer.prototype.getCalculator = function() {
  return this.calculator_;
};


/**
 * Add an array of markers to the clusterer.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarkers = function(markers, opt_nodraw) {
  for (var i = 0, marker; marker = markers[i]; i++) {
    this.pushMarkerTo_(marker);
  }
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Pushes a marker to the clusterer.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.pushMarkerTo_ = function(marker) {
  marker.isAdded = false;
  if (marker['draggable']) {
    // If the marker is draggable add a listener so we update the clusters on
    // the drag end.
    var that = this;
    google.maps.event.addListener(marker, 'dragend', function() {
      marker.isAdded = false;
      that.repaint();
    });
  }
  this.markers_.push(marker);
};


/**
 * Adds a marker to the clusterer and redraws if needed.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarker = function(marker, opt_nodraw) {
  this.pushMarkerTo_(marker);
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Removes a marker and returns true if removed, false if not
 *
 * @param {google.maps.Marker} marker The marker to remove
 * @return {boolean} Whether the marker was removed or not
 * @private
 */
MarkerClusterer.prototype.removeMarker_ = function(marker) {
  var index = -1;
  if (this.markers_.indexOf) {
    index = this.markers_.indexOf(marker);
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        index = i;
        break;
      }
    }
  }

  if (index == -1) {
    // Marker is not in our list of markers.
    return false;
  }

  marker.setMap(null);
//  if(marker.legend && false) {marker.legend.setMap(null); }
  this.markers_.splice(index, 1);

  return true;
};


/**
 * Remove a marker from the cluster.
 *
 * @param {google.maps.Marker} marker The marker to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 * @return {boolean} True if the marker was removed.
 */
MarkerClusterer.prototype.removeMarker = function(marker, opt_nodraw) {
  var removed = this.removeMarker_(marker);

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  } else {
   return false;
  }
};


/**
 * Removes an array of markers from the cluster.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 */
MarkerClusterer.prototype.removeMarkers = function(markers, opt_nodraw) {
  var removed = false;

  for (var i = 0, marker; marker = markers[i]; i++) {
    var r = this.removeMarker_(marker);
    removed = removed || r;
  }

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  }
};


/**
 * Sets the clusterer's ready state.
 *
 * @param {boolean} ready The state.
 * @private
 */
MarkerClusterer.prototype.setReady_ = function(ready) {
  if (!this.ready_) {
    this.ready_ = ready;
    this.createClusters_();
  }
};


/**
 * Returns the number of clusters in the clusterer.
 *
 * @return {number} The number of clusters.
 */
MarkerClusterer.prototype.getTotalClusters = function() {
  return this.clusters_.length;
};


/**
 * Returns the google map that the clusterer is associated with.
 *
 * @return {google.maps.Map} The map.
 */
MarkerClusterer.prototype.getMap = function() {
  return this.map_;
};


/**
 * Sets the google map that the clusterer is associated with.
 *
 * @param {google.maps.Map} map The map.
 */
MarkerClusterer.prototype.setMap = function(map) {
  this.map_ = map;
};


/**
 * Returns the size of the grid.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getGridSize = function() {
  return this.gridSize_;
};


/**
 * Sets the size of the grid.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setGridSize = function(size) {
  this.gridSize_ = size;
};


/**
 * Returns the min cluster size.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getMinClusterSize = function() {
  return this.minClusterSize_;
};

/**
 * Sets the min cluster size.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setMinClusterSize = function(size) {
  this.minClusterSize_ = size;
};


/**
 * Extends a bounds object by the grid size.
 *
 * @param {google.maps.LatLngBounds} bounds The bounds to extend.
 * @return {google.maps.LatLngBounds} The extended bounds.
 */
MarkerClusterer.prototype.getExtendedBounds = function(bounds) {
  var projection = this.getProjection();

  // Turn the bounds into latlng.
  var tr = new google.maps.LatLng(bounds.getNorthEast().lat(),
      bounds.getNorthEast().lng());
  var bl = new google.maps.LatLng(bounds.getSouthWest().lat(),
      bounds.getSouthWest().lng());

  // Convert the points to pixels and the extend out by the grid size.
  var trPix = projection.fromLatLngToDivPixel(tr);
  trPix.x += this.gridSize_;
  trPix.y -= this.gridSize_;

  var blPix = projection.fromLatLngToDivPixel(bl);
  blPix.x -= this.gridSize_;
  blPix.y += this.gridSize_;

  // Convert the pixel points back to LatLng
  var ne = projection.fromDivPixelToLatLng(trPix);
  var sw = projection.fromDivPixelToLatLng(blPix);

  // Extend the bounds to contain the new bounds.
  bounds.extend(ne);
  bounds.extend(sw);

  return bounds;
};


/**
 * Determins if a marker is contained in a bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @param {google.maps.LatLngBounds} bounds The bounds to check against.
 * @return {boolean} True if the marker is in the bounds.
 * @private
 */
MarkerClusterer.prototype.isMarkerInBounds_ = function(marker, bounds) {
  return bounds.contains(marker.getPosition());
};


/**
 * Clears all clusters and markers from the clusterer.
 */
MarkerClusterer.prototype.clearMarkers = function() {
  this.resetViewport(true);

  // Set the markers a empty array.
  this.markers_ = [];
};


/**
 * Clears all existing clusters and recreates them.
 * @param {boolean} opt_hide To also hide the marker.
 */
MarkerClusterer.prototype.resetViewport = function(opt_hide) {
  // Remove all the clusters
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    cluster.remove();
  }

  // Reset the markers to not be added and to be invisible.
  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    marker.isAdded = false;
    if (opt_hide) {
      marker.setMap(null);
//      if(marker.legend && false) {marker.legend.setMap(null); }
    }
  }

  this.clusters_ = [];
};

/**
 *
 */
MarkerClusterer.prototype.repaint = function() {
  var oldClusters = this.clusters_.slice();
  this.clusters_.length = 0;
  this.resetViewport();
  this.redraw();

  // Remove the old clusters.
  // Do it in a timeout so the other clusters have been drawn first.
  window.setTimeout(function() {
    for (var i = 0, cluster; cluster = oldClusters[i]; i++) {
      cluster.remove();
    }
  }, 0);
};


/**
 * Redraws the clusters.
 */
MarkerClusterer.prototype.redraw = function() {
  this.createClusters_();
};


/**
 * Calculates the distance between two latlng locations in km.
 * @see http://www.movable-type.co.uk/scripts/latlong.html
 *
 * @param {google.maps.LatLng} p1 The first lat lng point.
 * @param {google.maps.LatLng} p2 The second lat lng point.
 * @return {number} The distance between the two points in km.
 * @private
*/
MarkerClusterer.prototype.distanceBetweenPoints_ = function(p1, p2) {
  if (!p1 || !p2) {
    return 0;
  }

  var R = 6371; // Radius of the Earth in km
  var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
  var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d;
};


/**
 * Add a marker to a cluster, or creates a new cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.addToClosestCluster_ = function(marker) {
  var distance = 40000; // Some large number
  var clusterToAddTo = null;
  var pos = marker.getPosition();
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    var center = cluster.getCenter();
    if (center) {
      var d = this.distanceBetweenPoints_(center, marker.getPosition());
      if (d < distance) {
        distance = d;
        clusterToAddTo = cluster;
      }
    }
  }

  if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
    clusterToAddTo.addMarker(marker);
  } else {
    var cluster = new Cluster(this);
    cluster.addMarker(marker);
    this.clusters_.push(cluster);
  }
};


/**
 * Creates the clusters.
 *
 * @private
 */
MarkerClusterer.prototype.createClusters_ = function() {
  if (!this.ready_) {
    return;
  }

  // Get our current map view bounds.
  // Create a new bounds object so we don't affect the map.
  var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),
      this.map_.getBounds().getNorthEast());
  var bounds = this.getExtendedBounds(mapBounds);

  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
      this.addToClosestCluster_(marker);
    }
  }
};


/**
 * A cluster that contains markers.
 *
 * @param {MarkerClusterer} markerClusterer The markerclusterer that this
 *     cluster is associated with.
 * @constructor
 * @ignore
 */
function Cluster(markerClusterer) {
  this.markerClusterer_ = markerClusterer;
  this.map_ = markerClusterer.getMap();
  this.gridSize_ = markerClusterer.getGridSize();
  this.minClusterSize_ = markerClusterer.getMinClusterSize();
  this.averageCenter_ = markerClusterer.isAverageCenter();
  this.center_ = null;
  this.markers_ = [];
  this.bounds_ = null;
  this.clusterIcon_ = new ClusterIcon(this, markerClusterer.getStyles(),
      markerClusterer.getGridSize());
}

/**
 * Determins if a marker is already added to the cluster.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker is already added.
 */
Cluster.prototype.isMarkerAlreadyAdded = function(marker) {
  if (this.markers_.indexOf) {
    return this.markers_.indexOf(marker) != -1;
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        return true;
      }
    }
  }
  return false;
};


/**
 * Add a marker the cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @return {boolean} True if the marker was added.
 */
Cluster.prototype.addMarker = function(marker) {
  if (this.isMarkerAlreadyAdded(marker)) {
    return false;
  }

  if (!this.center_) {
    this.center_ = marker.getPosition();
    this.calculateBounds_();
  } else {
    if (this.averageCenter_) {
      var l = this.markers_.length + 1;
      var lat = (this.center_.lat() * (l-1) + marker.getPosition().lat()) / l;
      var lng = (this.center_.lng() * (l-1) + marker.getPosition().lng()) / l;
      this.center_ = new google.maps.LatLng(lat, lng);
      this.calculateBounds_();
    }
  }

  marker.isAdded = true;
  this.markers_.push(marker);

  var len = this.markers_.length;
  if (len < this.minClusterSize_ && marker.getMap() != this.map_) {
    // Min cluster size not reached so show the marker.
    marker.setMap(this.map_);
//    if(marker.legend && false) {marker.legend.setMap(this.map); }
  }

  if (len == this.minClusterSize_) {
    // Hide the markers that were showing.
    for (var i = 0; i < len; i++) {
      this.markers_[i].setMap(null);
//     if(this.markers_[i].legend && false) {this.markers_[i].setMap(null); }
    }
  }

  if (len >= this.minClusterSize_) {
    marker.setMap(null);
//    if(marker.legend && false) {marker.legend.setMap(null); }
  }

  this.updateIcon();
  return true;
};


/**
 * Returns the marker clusterer that the cluster is associated with.
 *
 * @return {MarkerClusterer} The associated marker clusterer.
 */
Cluster.prototype.getMarkerClusterer = function() {
  return this.markerClusterer_;
};


/**
 * Returns the bounds of the cluster.
 *
 * @return {google.maps.LatLngBounds} the cluster bounds.
 */
Cluster.prototype.getBounds = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  var markers = this.getMarkers();
  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }
  return bounds;
};


/**
 * Removes the cluster
 */
Cluster.prototype.remove = function() {
  this.clusterIcon_.remove();
  this.markers_.length = 0;
  delete this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {number} The cluster center.
 */
Cluster.prototype.getSize = function() {
  return this.markers_.length;
};


/**
 * Returns the center of the cluster.
 *
 * @return {Array.<google.maps.Marker>} The cluster center.
 */
Cluster.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {google.maps.LatLng} The cluster center.
 */
Cluster.prototype.getCenter = function() {
  return this.center_;
};


/**
 * Calculated the extended bounds of the cluster with the grid.
 *
 * @private
 */
Cluster.prototype.calculateBounds_ = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};


/**
 * Determines if a marker lies in the clusters bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker lies in the bounds.
 */
Cluster.prototype.isMarkerInClusterBounds = function(marker) {
  return this.bounds_.contains(marker.getPosition());
};


/**
 * Returns the map that the cluster is associated with.
 *
 * @return {google.maps.Map} The map.
 */
Cluster.prototype.getMap = function() {
  return this.map_;
};


/**
 * Updates the cluster icon
 */
Cluster.prototype.updateIcon = function() {
  var zoom = this.map_.getZoom();
  var mz = this.markerClusterer_.getMaxZoom();

  if (mz && zoom > mz) {
    // The zoom is greater than our max zoom so show all the markers in cluster.
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
      marker.setMap(this.map_);
//      if(marker.legend && false) {marker.legend.setMap(this.map_); }
    }
    return;
  }

  if (this.markers_.length < this.minClusterSize_) {
    // Min cluster size not yet reached.
    this.clusterIcon_.hide();
    return;
  }

  var numStyles = this.markerClusterer_.getStyles().length;
  var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);
  this.clusterIcon_.setCenter(this.center_);
  this.clusterIcon_.setSums(sums);
  this.clusterIcon_.show();
};


/**
 * A cluster icon
 *
 * @param {Cluster} cluster The cluster to be associated with.
 * @param {Object} styles An object that has style properties:
 *     'url': (string) The image url.
 *     'height': (number) The image height.
 *     'width': (number) The image width.
 *     'anchor': (Array) The anchor position of the label text.
 *     'textColor': (string) The text color.
 *     'textSize': (number) The text size.
 *     'backgroundPosition: (string) The background postition x, y.
 * @param {number=} opt_padding Optional padding to apply to the cluster icon.
 * @constructor
 * @extends google.maps.OverlayView
 * @ignore
 */
function ClusterIcon(cluster, styles, opt_padding) {
  cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);

  this.styles_ = styles;
  this.padding_ = opt_padding || 0;
  this.cluster_ = cluster;
  this.center_ = null;
  this.map_ = cluster.getMap();
  this.div_ = null;
  this.sums_ = null;
  this.visible_ = false;

  this.setMap(this.map_);
}


/**
 * Triggers the clusterclick event and zoom's if the option is set.
 */
ClusterIcon.prototype.triggerClusterClick = function() {
  var markerClusterer = this.cluster_.getMarkerClusterer();

  // Trigger the clusterclick event.
  google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_);

  if (markerClusterer.isZoomOnClick()) {
    // Zoom into the cluster.
    this.map_.fitBounds(this.cluster_.getBounds());
  }
};


/**
 * Adding the cluster icon to the dom.
 * @ignore
 */
ClusterIcon.prototype.onAdd = function() {
  this.div_ = document.createElement('DIV');
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.innerHTML = this.sums_.text;
  }

  var panes = this.getPanes();
  panes.overlayMouseTarget.appendChild(this.div_);

  var that = this;
  google.maps.event.addDomListener(this.div_, 'click', function() {
    that.triggerClusterClick();
  });
};


/**
 * Returns the position to place the div dending on the latlng.
 *
 * @param {google.maps.LatLng} latlng The position in latlng.
 * @return {google.maps.Point} The position in pixels.
 * @private
 */
ClusterIcon.prototype.getPosFromLatLng_ = function(latlng) {
  var pos = this.getProjection().fromLatLngToDivPixel(latlng);
  pos.x -= parseInt(this.width_ / 2, 10);
  pos.y -= parseInt(this.height_ / 2, 10);
  return pos;
};


/**
 * Draw the icon.
 * @ignore
 */
ClusterIcon.prototype.draw = function() {
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.top = pos.y + 'px';
    this.div_.style.left = pos.x + 'px';
  }
};


/**
 * Hide the icon.
 */
ClusterIcon.prototype.hide = function() {
  if (this.div_) {
    this.div_.style.display = 'none';
  }
  this.visible_ = false;
};


/**
 * Position and show the icon.
 */
ClusterIcon.prototype.show = function() {
  if (this.div_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.style.display = '';
  }
  this.visible_ = true;
};


/**
 * Remove the icon from the map
 */
ClusterIcon.prototype.remove = function() {
  this.setMap(null);
//  if(this.legend) {this.legend.setMap(null); }
};


/**
 * Implementation of the onRemove interface.
 * @ignore
 */
ClusterIcon.prototype.onRemove = function() {
  if (this.div_ && this.div_.parentNode) {
    this.hide();
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
};


/**
 * Set the sums of the icon.
 *
 * @param {Object} sums The sums containing:
 *   'text': (string) The text to display in the icon.
 *   'index': (number) The style index of the icon.
 */
ClusterIcon.prototype.setSums = function(sums) {
  this.sums_ = sums;
  this.text_ = sums.text;
  this.index_ = sums.index;
  if (this.div_) {
    this.div_.innerHTML = sums.text;
  }

  this.useStyle();
};


/**
 * Sets the icon to the the styles.
 */
ClusterIcon.prototype.useStyle = function() {
  var index = Math.max(0, this.sums_.index - 1);
  index = Math.min(this.styles_.length - 1, index);
  var style = this.styles_[index];
  this.url_ = style['url'];
  this.height_ = style['height'];
  this.width_ = style['width'];
  this.textColor_ = style['textColor'];
  this.anchor_ = style['anchor'];
  this.textSize_ = style['textSize'];
  this.backgroundPosition_ = style['backgroundPosition'];
};


/**
 * Sets the center of the icon.
 *
 * @param {google.maps.LatLng} center The latlng to set as the center.
 */
ClusterIcon.prototype.setCenter = function(center) {
  this.center_ = center;
};


/**
 * Create the css text based on the position of the icon.
 *
 * @param {google.maps.Point} pos The position.
 * @return {string} The css style text.
 */
ClusterIcon.prototype.createCss = function(pos) {
  var style = [];
  style.push('background-image:url(' + this.url_ + ');');
  var backgroundPosition = this.backgroundPosition_ ? this.backgroundPosition_ : '0 0';
  style.push('background-position:' + backgroundPosition + ';');

  if (typeof this.anchor_ === 'object') {
    if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 &&
        this.anchor_[0] < this.height_) {
      style.push('height:' + (this.height_ - this.anchor_[0]) +
          'px; padding-top:' + this.anchor_[0] + 'px;');
    } else {
      style.push('height:' + this.height_ + 'px; line-height:' + this.height_ +
          'px;');
    }
    if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 &&
        this.anchor_[1] < this.width_) {
      style.push('width:' + (this.width_ - this.anchor_[1]) +
          'px; padding-left:' + this.anchor_[1] + 'px;');
    } else {
      style.push('width:' + this.width_ + 'px; text-align:center;');
    }
  } else {
    style.push('height:' + this.height_ + 'px; line-height:' +
        this.height_ + 'px; width:' + this.width_ + 'px; text-align:center;');
  }

  var txtColor = this.textColor_ ? this.textColor_ : 'black';
  var txtSize = this.textSize_ ? this.textSize_ : 11;

  style.push('cursor:pointer; top:' + pos.y + 'px; left:' +
      pos.x + 'px; color:' + txtColor + '; position:absolute; font-size:' +
      txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');
  return style.join('');
};
</script>
<?php
# ----------------------  version history
# 3.20 2015-09-25 release 2.8 version 
