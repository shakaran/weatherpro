<?php
$noaaSeverity['Extreme']	= array ('explanation'=> 'Extraordinary threat to life or property','color' => 'Red',	'colorCode' => '#FF6666');
$noaaSeverity['Severe']		= array ('explanation'=> 'Significant threat to life or property',	'color' => 'Orange','colorCode' => '#FF6666');
$noaaSeverity['Moderate']	= array ('explanation'=> 'Possible threat to life or property ',	'color' => 'Yellow','colorCode' => '#FECC33');
$noaaSeverity['Minor']		= array ('explanation'=> 'Minimal threat to life or property',		'color' => 'Yellow','colorCode' => '#FFFF66');
$noaaSeverity['Unknown']	= array ('explanation'=> 'Severity unknown',						'color' => 'White',	'colorCode' => '#FFFFFF');
$noaaSeverity['None']		= array ('explanation'=> 'No threats known',						'color' => 'Green',	'colorCode' => '#99ff99');

$noaaUrgency['Immediate']	= array ('explanation'=> 'Responsive action SHOULD be taken immediately');
$noaaUrgency['Expected']	= array ('explanation'=> 'Responsive action SHOULD be taken soon (within next hour)');
$noaaUrgency['Future']		= array ('explanation'=> 'Responsive action SHOULD be taken in the near future');
$noaaUrgency['Past']		= array ('explanation'=> 'Responsive action is no longer required');
$noaaUrgency['Unknown']		= array ('explanation'=> 'Urgency not known');

$noaaEventIcons['Wind']						='wind.jpg';
$noaaEventIcons['Hurricane']				='nsvrtsra.jpg';
$noaaEventIcons['Snow/Ice']					='mix.jpg';
$noaaEventIcons['Thunderstorms']			='tsra.jpg';
$noaaEventIcons['Fog']						='fg.jpg';
$noaaEventIcons['Extreme high temperature']	='hot.jpg';
$noaaEventIcons['Extreme low temperature']	='cold.jpg';
$noaaEventIcons['Coastal Event']			='m_wave.jpg';
$noaaEventIcons['Forestfire']				='warning.png';
$noaaEventIcons['Avalanches']				='warning.png';
$noaaEventIcons['Rain']						='ra.jpg';
$noaaEventIcons['Flood']					='warning.png';
$noaaEventIcons['Not available']			='warning.png';
$noaaEventIcons['xx']						='warning.png';

$noaaEvents['911 Telephone Outage']			= array ('types' => 'xx');
$noaaEvents['Administrative Message']		= array ('types' => 'xx');
$noaaEvents['Air Quality Alert']			= array ('types' => 'Not available');
$noaaEvents['Air Stagnation Advisory']		= array ('types' => 'xx');
$noaaEvents['Ashfall Advisory']				= array ('types' => 'xx');
$noaaEvents['Ashfall Warning']				= array ('types' => 'xx');
$noaaEvents['Avalanche Warning']			= array ('types' => 'Avalanches');
$noaaEvents['Avalanche Watch']				= array ('types' => 'Avalanches');
$noaaEvents['Beach Hazards Statement']		= array ('types' => 'Coastal Event');
$noaaEvents['Blizzard Warning']				= array ('types' => 'Snow/Ice');
$noaaEvents['Blizzard Watch']				= array ('types' => 'Snow/Ice');
$noaaEvents['Blowing Dust Advisory']		= array ('types' => 'xx');
$noaaEvents['Blowing Snow Advisory']		= array ('types' => 'Snow/Ice');
$noaaEvents['Brisk Wind Advisory']			= array ('types' => 'Wind');
$noaaEvents['Child Abduction Emergency']	= array ('types' => 'xx');
$noaaEvents['Civil Danger Warning']			= array ('types' => 'xx');
$noaaEvents['Civil Emergency Message']		= array ('types' => 'xx');
$noaaEvents['Coastal Flood Advisory']		= array ('types' => 'Coastal Event');
$noaaEvents['Coastal Flood Statement']		= array ('types' => 'Coastal Event');
$noaaEvents['Coastal Flood Warning']		= array ('types' => 'Coastal Event');
$noaaEvents['Coastal Flood Watch']			= array ('types' => 'Coastal Event');
$noaaEvents['Dense Fog Advisory']			= array ('types' => 'xx');
$noaaEvents['Dense Smoke Advisory']			= array ('types' => 'xx');
$noaaEvents['Dust Storm Warning']			= array ('types' => 'xx');
$noaaEvents['Earthquake Warning']			= array ('types' => 'xx');
$noaaEvents['Evacuation Immediate']			= array ('types' => 'xx');
$noaaEvents['Excessive Heat Warning']		= array ('types' => 'Extreme high temperature');
$noaaEvents['Excessive Heat Watch']			= array ('types' => 'Extreme high temperature');
$noaaEvents['Extreme Cold Warning']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Extreme Cold Watch']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Extreme Fire Danger']			= array ('types' => 'Forestfire');
$noaaEvents['Extreme Wind Warning']			= array ('types' => 'Wind');
$noaaEvents['Fire Warning']					= array ('types' => 'Forestfire');
$noaaEvents['Fire Weather Watch']			= array ('types' => 'Forestfire');
$noaaEvents['Flash Flood Statement']		= array ('types' => 'Flood');
$noaaEvents['Flash Flood Warning']			= array ('types' => 'Flood');
$noaaEvents['Flash Flood Watch']			= array ('types' => 'Flood');
$noaaEvents['Flood Advisory']				= array ('types' => 'Flood');
$noaaEvents['Flood Statement']				= array ('types' => 'Flood');
$noaaEvents['Flood Warning']				= array ('types' => 'Flood');
$noaaEvents['Flood Watch']					= array ('types' => 'Flood');
$noaaEvents['Freeze Warning']				= array ('types' => 'Snow/Ice');
$noaaEvents['Freeze Watch']					= array ('types' => 'Snow/Ice');
$noaaEvents['Freezing Drizzle Advisory']	= array ('types' => 'Snow/Ice');
$noaaEvents['Freezing Fog Advisory']		= array ('types' => 'Snow/Ice');
$noaaEvents['Freezing Rain Advisory']		= array ('types' => 'Snow/Ice');
$noaaEvents['Freezing Spray Advisory']		= array ('types' => 'Snow/Ice');
$noaaEvents['Frost Advisory']				= array ('types' => 'Snow/Ice');
$noaaEvents['Gale Warning']					= array ('types' => 'xx');
$noaaEvents['Gale Watch']					= array ('types' => 'xx');
$noaaEvents['Hard Freeze Warning']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Hard Freeze Watch']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Hazardous Materials Warning']	= array ('types' => 'xx');
$noaaEvents['Hazardous Seas Warning']		= array ('types' => 'xx');
$noaaEvents['Hazardous Seas Watch']			= array ('types' => 'xx');
$noaaEvents['Hazardous Weather Outlook']	= array ('types' => 'xx');
$noaaEvents['Heat Advisory']				= array ('types' => 'Extreme high temperature');
$noaaEvents['Heavy Freezing Spray Warning']	= array ('types' => 'Snow/Ice');
$noaaEvents['Heavy Freezing Spray Watch']	= array ('types' => 'Snow/Ice');
$noaaEvents['Heavy Snow Warning']			= array ('types' => 'Snow/Ice');
$noaaEvents['High Surf Advisory']			= array ('types' => 'Coastal Event');
$noaaEvents['High Surf Warning']			= array ('types' => 'Coastal Event');
$noaaEvents['High Wind Warning']			= array ('types' => 'Wind');
$noaaEvents['High Wind Watch']				= array ('types' => 'Wind');
$noaaEvents['Hurricane Force Wind Warning']	= array ('types' => 'Hurricane');
$noaaEvents['Hurricane Force Wind Watch']	= array ('types' => 'Hurricane');
$noaaEvents['Hurricane Statement']			= array ('types' => 'Hurricane');
$noaaEvents['Hurricane Warning']			= array ('types' => 'Hurricane');
$noaaEvents['Hurricane Watch']				= array ('types' => 'Hurricane');
$noaaEvents['Hurricane Wind Warning']		= array ('types' => 'Hurricane');
$noaaEvents['Hurricane Wind Watch']			= array ('types' => 'Hurricane');
$noaaEvents['Hydrologic Advisory']			= array ('types' => 'xx');
$noaaEvents['Hydrologic Outlook']			= array ('types' => 'Not available');
$noaaEvents['Ice Storm Warning']			= array ('types' => 'Snow/Ice');
$noaaEvents['Lake Effect Snow Advisory']	= array ('types' => 'Snow/Ice');
$noaaEvents['Lake Effect Snow and Blowing Snow Advisory']	= array ('types' => 'Snow/Ice');
$noaaEvents['Lake Effect Snow Warning']		= array ('types' => 'Snow/Ice');
$noaaEvents['Lake Effect Snow Watch']		= array ('types' => 'Snow/Ice');
$noaaEvents['Lakeshore Flood Advisory']		= array ('types' => 'Flood');
$noaaEvents['Lakeshore Flood Statement']	= array ('types' => 'Flood');
$noaaEvents['Lakeshore Flood Warning']		= array ('types' => 'Flood');
$noaaEvents['Lakeshore Flood Watch']		= array ('types' => 'Flood');
$noaaEvents['Lake Wind Advisory']			= array ('types' => 'Wind');
$noaaEvents['Law Enforcement Warning']		= array ('types' => 'xx');
$noaaEvents['Local Area Emergency']			= array ('types' => 'xx');
$noaaEvents['Low Water Advisory']			= array ('types' => 'xx');
$noaaEvents['Marine Weather Statement']		= array ('types' => 'xx');
$noaaEvents['Nuclear Power Plant Warning']	= array ('types' => 'xx');
$noaaEvents['Radiological Hazard Warning']	= array ('types' => 'xx');
$noaaEvents['Red Flag Warning']				= array ('types' => 'Forestfire');
$noaaEvents['Rip Current Statement']		= array ('types' => 'xx');
$noaaEvents['Severe Thunderstorm Warning']	= array ('types' => 'Thunderstorms');
$noaaEvents['Severe Thunderstorm Watch']	= array ('types' => 'Thunderstorms');
$noaaEvents['Severe Weather Statement']		= array ('types' => 'xx');
$noaaEvents['Shelter In Place Warning']		= array ('types' => 'xx');
$noaaEvents['Sleet Advisory']				= array ('types' => 'Snow/Ice');
$noaaEvents['Sleet Warning']				= array ('types' => 'Snow/Ice');
$noaaEvents['Small Craft Advisory']			= array ('types' => 'Coastal Event');
$noaaEvents['Snow Advisory']				= array ('types' => 'Snow/Ice');
$noaaEvents['Snow and Blowing Snow Advisory']= array ('types' => 'Snow/Ice');
$noaaEvents['Special Marine Warning']		= array ('types' => 'Coastal Event');
$noaaEvents['Special Weather Statement']	= array ('types' => 'xx');
$noaaEvents['Storm Warning']				= array ('types' => 'Wind');
$noaaEvents['Storm Watch']					= array ('types' => 'Wind');
$noaaEvents['Test']							= array ('types' => 'xx');
$noaaEvents['Tornado Warning']				= array ('types' => 'xx');
$noaaEvents['Tornado Watch']				= array ('types' => 'xx');
$noaaEvents['Tropical Storm Warning']		= array ('types' => 'xx');
$noaaEvents['Tropical Storm Watch']			= array ('types' => 'xx');
$noaaEvents['Tropical Storm Wind Warning']	= array ('types' => 'xx');
$noaaEvents['Tropical Storm Wind Watch']	= array ('types' => 'xx');
$noaaEvents['Tsunami Advisory']				= array ('types' => 'xx');
$noaaEvents['Tsunami Warning']				= array ('types' => 'xx');
$noaaEvents['Tsunami Watch']				= array ('types' => 'xx');
$noaaEvents['Typhoon Statement']			= array ('types' => 'xx');
$noaaEvents['Typhoon Warning']				= array ('types' => 'xx');
$noaaEvents['Typhoon Watch']				= array ('types' => 'xx');
$noaaEvents['Volcano Warning']				= array ('types' => 'xx');
$noaaEvents['Wind Advisory']				= array ('types' => 'Wind');
$noaaEvents['Wind Chill Advisory']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Wind Chill Warning']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Wind Chill Watch']				= array ('types' => 'Extreme low temperature');
$noaaEvents['Winter Storm Warning']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Winter Storm Watch']			= array ('types' => 'Extreme low temperature');
$noaaEvents['Winter Weather Advisory']		= array ('types' => 'Snow/Ice');
?>