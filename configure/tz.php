<?php ini_set('display_errors', 'On');   error_reporting(E_ALL);


// taken from Toland Hon's Answer
function prettyOffset($offset) {
    $offset_prefix = $offset < 0 ? '-' : '+';
    $offset_formatted = gmdate( 'H:i', abs($offset) );

    $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

    return $pretty_offset;
}
function timezone_canada() {
	$timezone_canada ["CA"]["name"]  = "Canada";	
	foreach ($timezone_canada as $k => $v) {
	    $tz = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $k);
	    foreach ($tz as $value) {
	    	$t = new DateTimeZone($value);
	    	$new = new DateTime("now", $t);
	    	$offset = $new->getOffset();
	    	$timezone_canada[$k]['timezones'][$value] = prettyOffset($offset);
	    }   
	}
	return $timezone_canada;
}
function timezone_europe() {
 $timezone_europe["AL"]["name"] = "Albania";
 $timezone_europe["AD"]["name"] = "Andorra";
 $timezone_europe["AM"]["name"] = "Armenia";
 $timezone_europe["AT"]["name"] = "Austria";
 $timezone_europe["AZ"]["name"] = "Azerbaijan";
 $timezone_europe["BY"]["name"] = "Belarus";
 $timezone_europe["BE"]["name"] = "Belgium";
 $timezone_europe["BA"]["name"] = "Bosnia & Herzegovina";
 $timezone_europe["BG"]["name"] = "Bulgaria";
 $timezone_europe["HR"]["name"] = "Croatia";
 $timezone_europe["CY"]["name"] = "Cyprus";
 $timezone_europe["CZ"]["name"] = "Czech Republic";
 $timezone_europe["DK"]["name"] = "Denmark";
 $timezone_europe["FI"]["name"] = "Finland";
 $timezone_europe["FR"]["name"] = "France";
 $timezone_europe["GE"]["name"] = "Georgia";
 $timezone_europe["DE"]["name"] = "Germany";
 $timezone_europe["GI"]["name"] = "Gibraltar";
 $timezone_europe["GR"]["name"] = "Greece";
 $timezone_europe["GL"]["name"] = "Greenland";
 $timezone_europe["HU"]["name"] = "Hungary";
 $timezone_europe["IS"]["name"] = "Iceland";
 $timezone_europe["IE"]["name"] = "Ireland";
 $timezone_europe["IT"]["name"] = "Italy";
 $timezone_europe["LV"]["name"] = "Latvia";
 $timezone_europe["LT"]["name"] = "Lithuania";
 $timezone_europe["LU"]["name"] = "Luxembourg";
 $timezone_europe["MK"]["name"] = "Macedonia (FYROM)";
 $timezone_europe["MT"]["name"] = "Malta";
 $timezone_europe["MC"]["name"] = "Monaco";
 $timezone_europe["NL"]["name"] = "Netherlands";
 $timezone_europe["NO"]["name"] = "Norway";
 $timezone_europe["PL"]["name"] = "Poland";
 $timezone_europe["PT"]["name"] = "Portugal";
 $timezone_europe["RU"]["name"] = "Russia";
 $timezone_europe["RS"]["name"] = "Serbia";
 $timezone_europe["SM"]["name"] = "San Marino";
 $timezone_europe["SK"]["name"] = "Slovakia";
 $timezone_europe["SI"]["name"] = "Slovenia";
 $timezone_europe["ES"]["name"] = "Spain";
 $timezone_europe["SE"]["name"] = "Sweden";
 $timezone_europe["CH"]["name"] = "Switzerland";
 $timezone_europe["GB"]["name"] = "United Kingdom";
 $timezone_europe["UA"]["name"] = "Ukraine";
 $timezone_europe["VA"]["name"] = "Vatican City";
        
	foreach ($timezone_europe as $k => $v) {
	    $tz = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $k);
	    foreach ($tz as $value) {
	    	$t = new DateTimeZone($value);
	    	$new = new DateTime("now", $t);
	    	$offset = $new->getOffset();
	    	$timezone_europe[$k]['timezones'][$value] = prettyOffset($offset);
	    }   
	}
	return $timezone_europe;
}
function timezone_america() {
	$timezone_america["US"]["name"] = "United States";
	foreach ($timezone_america as $k => $v) {
	    $tz = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $k);
	    foreach ($tz as $value) {
	    	$t = new DateTimeZone($value);
	    	$new = new DateTime("now", $t);
	    	$offset = $new->getOffset();
	    	$timezone_america[$k]['timezones'][$value] = prettyOffset($offset);
	    }   
	}
	return $timezone_america;
}
function timezone_other() {
 $timezone_other["AF"]["name"] = "Afghanistan";
 $timezone_other["DZ"]["name"] = "Algeria";
 $timezone_other["AS"]["name"] = "American Samoa";
 $timezone_other["AQ"]["name"] = "Antarctica";
 $timezone_other["AG"]["name"] = "Antigua & Barbuda";
 $timezone_other["AR"]["name"] = "Argentina";
 $timezone_other["AM"]["name"] = "Armenia";
 $timezone_other["AU"]["name"] = "Australia";
 $timezone_other["BS"]["name"] = "Bahamas";
 $timezone_other["BD"]["name"] = "Bangladesh";
 $timezone_other["BB"]["name"] = "Barbados";
 $timezone_other["BY"]["name"] = "Belarus";
 $timezone_other["BZ"]["name"] = "Belize";
 $timezone_other["BM"]["name"] = "Bermuda";
 $timezone_other["BT"]["name"] = "Bhutan";
 $timezone_other["BO"]["name"] = "Bolivia";
 $timezone_other["BR"]["name"] = "Brazil";
 $timezone_other["IO"]["name"] = "British Indian Ocean Territory";
 $timezone_other["BN"]["name"] = "Brunei";
 $timezone_other["CV"]["name"] = "Cape Verde";
 $timezone_other["KY"]["name"] = "Cayman Islands";
 $timezone_other["TD"]["name"] = "Chad";
 $timezone_other["CL"]["name"] = "Chile";
 $timezone_other["CN"]["name"] = "China";
 $timezone_other["CX"]["name"] = "Christmas Island";
 $timezone_other["CC"]["name"] = "Cocos (Keeling) Islands";
 $timezone_other["CO"]["name"] = "Colombia";
 $timezone_other["CK"]["name"] = "Cook Islands";
 $timezone_other["CR"]["name"] = "Costa Rica";
 $timezone_other["CI"]["name"] = "Côte d’Ivoire";
 $timezone_other["CU"]["name"] = "Cuba";
 $timezone_other["CW"]["name"] = "Curaçao";
 $timezone_other["DO"]["name"] = "Dominican Republic";
 $timezone_other["EC"]["name"] = "Ecuador";
 $timezone_other["EG"]["name"] = "Egypt";
 $timezone_other["SV"]["name"] = "El Salvador";
 $timezone_other["EE"]["name"] = "Estonia";
 $timezone_other["FK"]["name"] = "Falkland Islands (Islas Malvinas)";
 $timezone_other["FO"]["name"] = "Faroe Islands";
 $timezone_other["FJ"]["name"] = "Fiji";
 $timezone_other["GF"]["name"] = "French Guiana";
 $timezone_other["PF"]["name"] = "French Polynesia";
 $timezone_other["TF"]["name"] = "French Southern Territories";
 $timezone_other["GH"]["name"] = "Ghana";
 $timezone_other["GU"]["name"] = "Guam";
 $timezone_other["GT"]["name"] = "Guatemala";
 $timezone_other["GW"]["name"] = "Guinea-Bissau";
 $timezone_other["GY"]["name"] = "Guyana";
 $timezone_other["HT"]["name"] = "Haiti";
 $timezone_other["HN"]["name"] = "Honduras";
 $timezone_other["HK"]["name"] = "Hong Kong";
 $timezone_other["IN"]["name"] = "India";
 $timezone_other["ID"]["name"] = "Indonesia";
 $timezone_other["IR"]["name"] = "Iran";
 $timezone_other["IQ"]["name"] = "Iraq";
 $timezone_other["IL"]["name"] = "Israel";
 $timezone_other["JM"]["name"] = "Jamaica";
 $timezone_other["JP"]["name"] = "Japan";
 $timezone_other["JO"]["name"] = "Jordan";
 $timezone_other["KZ"]["name"] = "Kazakhstan";
 $timezone_other["KE"]["name"] = "Kenya";
 $timezone_other["KI"]["name"] = "Kiribati";
 $timezone_other["KG"]["name"] = "Kyrgyzstan";
 $timezone_other["LB"]["name"] = "Lebanon";
 $timezone_other["LR"]["name"] = "Liberia";
 $timezone_other["LY"]["name"] = "Libya";
 $timezone_other["MO"]["name"] = "Macau";
 $timezone_other["MY"]["name"] = "Malaysia";
 $timezone_other["MV"]["name"] = "Maldives";
 $timezone_other["MH"]["name"] = "Marshall Islands";
 $timezone_other["MQ"]["name"] = "Martinique";
 $timezone_other["MU"]["name"] = "Mauritius";
 $timezone_other["MX"]["name"] = "Mexico";
 $timezone_other["FM"]["name"] = "Micronesia";
 $timezone_other["MD"]["name"] = "Moldova";
 $timezone_other["MN"]["name"] = "Mongolia";
 $timezone_other["MA"]["name"] = "Morocco";
 $timezone_other["MZ"]["name"] = "Mozambique";
 $timezone_other["MM"]["name"] = "Myanmar (Burma)";
 $timezone_other["NA"]["name"] = "Namibia";
 $timezone_other["NR"]["name"] = "Nauru";
 $timezone_other["NP"]["name"] = "Nepal";
 $timezone_other["NC"]["name"] = "New Caledonia";
 $timezone_other["NZ"]["name"] = "New Zealand";
 $timezone_other["NI"]["name"] = "Nicaragua";
 $timezone_other["NG"]["name"] = "Nigeria";
 $timezone_other["NU"]["name"] = "Niue";
 $timezone_other["NF"]["name"] = "Norfolk Island";
 $timezone_other["KP"]["name"] = "North Korea";
 $timezone_other["MP"]["name"] = "Northern Mariana Islands";
 $timezone_other["PK"]["name"] = "Pakistan";
 $timezone_other["PW"]["name"] = "Palau";
 $timezone_other["PS"]["name"] = "Palestine";
 $timezone_other["PA"]["name"] = "Panama";
 $timezone_other["PG"]["name"] = "Papua New Guinea";
 $timezone_other["PY"]["name"] = "Paraguay";
 $timezone_other["PE"]["name"] = "Peru";
 $timezone_other["PH"]["name"] = "Philippines";
 $timezone_other["PN"]["name"] = "Pitcairn Islands";
 $timezone_other["PR"]["name"] = "Puerto Rico";
 $timezone_other["QA"]["name"] = "Qatar";
 $timezone_other["RE"]["name"] = "Réunion";
 $timezone_other["RO"]["name"] = "Romania";
 $timezone_other["RU"]["name"] = "Russia";
 $timezone_other["WS"]["name"] = "Samoa";
 $timezone_other["SA"]["name"] = "Saudi Arabia";
 $timezone_other["SC"]["name"] = "Seychelles";
 $timezone_other["SG"]["name"] = "Singapore";
 $timezone_other["SB"]["name"] = "Solomon Islands";
 $timezone_other["ZA"]["name"] = "South Africa";
 $timezone_other["GS"]["name"] = "South Georgia & South Sandwich Islands";
 $timezone_other["KR"]["name"] = "South Korea";
 $timezone_other["LK"]["name"] = "Sri Lanka";
 $timezone_other["PM"]["name"] = "St. Pierre & Miquelon";
 $timezone_other["SD"]["name"] = "Sudan";
 $timezone_other["SR"]["name"] = "Suriname";
 $timezone_other["SJ"]["name"] = "Svalbard & Jan Mayen";
 $timezone_other["SY"]["name"] = "Syria";
 $timezone_other["TW"]["name"] = "Taiwan";
 $timezone_other["TJ"]["name"] = "Tajikistan";
 $timezone_other["TH"]["name"] = "Thailand";
 $timezone_other["TL"]["name"] = "Timor-Leste";
 $timezone_other["TK"]["name"] = "Tokelau";
 $timezone_other["TO"]["name"] = "Tonga";
 $timezone_other["TT"]["name"] = "Trinidad & Tobago";
 $timezone_other["TN"]["name"] = "Tunisia";
 $timezone_other["TR"]["name"] = "Turkey";
 $timezone_other["TM"]["name"] = "Turkmenistan";
 $timezone_other["TC"]["name"] = "Turks & Caicos Islands";
 $timezone_other["TV"]["name"] = "Tuvalu";
 $timezone_other["UM"]["name"] = "U.S. Outlying Islands";
 $timezone_other["AE"]["name"] = "United Arab Emirates";
 $timezone_other["UY"]["name"] = "Uruguay";
 $timezone_other["UZ"]["name"] = "Uzbekistan";
 $timezone_other["VU"]["name"] = "Vanuatu";
 $timezone_other["VE"]["name"] = "Venezuela";
 $timezone_other["VN"]["name"] = "Vietnam";
 $timezone_other["WF"]["name"] = "Wallis & Futuna";
 $timezone_other["EH"]["name"] = "Western Sahara";
	foreach ($timezone_other as $k => $v) {
	    $tz = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $k);
	    foreach ($tz as $value) {
	    	$t = new DateTimeZone($value);
	    	$new = new DateTime("now", $t);
	    	$offset = $new->getOffset();
	    	$timezone_other[$k]['timezones'][$value] = prettyOffset($offset);
	    }   
	}
	return $timezone_other;
}
/*
$timezone = timezone_canada();
echo  '<pre>'; print_r ($timezone);
foreach ($timezone as $xx => $arr_tz) {
#	print_r ( $arr_tz);
	$name = $arr_tz['name'].' - ';
	if (count($timezone == 1) ){$name = '';}
	foreach ($arr_tz['timezones'] as $tz_code => $tz_utc) {
		echo $name.'('. $tz_utc.')&nbsp;'.$tz_code.PHP_EOL;
	}
}
*/
