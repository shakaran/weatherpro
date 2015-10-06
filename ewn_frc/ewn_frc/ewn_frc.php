<?php
include_once($path_to_wrfmap.'wrf_map.php');

/* Head and footer-definations are moved to wrf_map.php */
$ewndata='
<div id="ewnwrapper" style="width:100%;max-width:1045px;">

<div class="statsearch" style="height:50px;">
<div class="gaugebox">
<div style="margin: 10px;">
<select id="country" name="country" class="form-control">
<option DISABLED> -- Choose -- </option>
<option value="AD" data-co="Andorra">'.defcountries("Andorra").'</option>
<option value="AL" data-co="Albania">'.defcountries("Albania").'</option>
<option value="AT" data-co="Austria">'.defcountries("Austria").'</option>
<option value="AX" data-co="Aland Islands">'.defcountries("Aland Islands").'</option>
<option value="BA" data-co="Bosnia and Herzegovina">'.defcountries("Bosnia &amp; Herzegovina").'</option>
<option value="BE" data-co="Belgium">'.defcountries("Belgium").'</option>
<option value="BG" data-co="Bulgaria">'.defcountries("Bulgaria").'</option>
<option value="BY" data-co="Belarus">'.defcountries("Belarus").'</option>
<option value="CH" data-co="Switzerland">'.defcountries("Switzerland").'</option>
<option value="CS" data-co="Serbia and Montenegro">'.defcountries("Serbia &amp; Montenegro").'</option>
<option value="CY" data-co="Cyprus">'.defcountries("Cyprus").'</option>
<option value="CZ" data-co="Czech Replublik">'.defcountries("Czech Republic").'</option>
<option value="DE" data-co="Germany">'.defcountries("Germany").'</option>
<option value="DK" data-co="Denmark">'.defcountries("Denmark").'</option>
<option value="EE" data-co="Estonia">'.defcountries("Estonia").'</option>
<option value="ES" data-co="Spain">'.defcountries("Spain").'</option>
<option value="FI" data-co="Finland">'.defcountries("Finland").'</option>
<option value="FO" data-co="Faroe Islands">'.defcountries("Faroe Islands").'</option>
<option value="FR" data-co="France">'.defcountries("France").'</option>
<option value="GB" data-co="United Kingdom">'.defcountries("United Kingdom").'</option>
<option value="GI" data-co="Gibraltar">'.defcountries("Gibraltar").'</option>
<option value="GR" data-co="Greece">'.defcountries("Greece").'</option>
<option value="HR" data-co="Croatia">'.defcountries("Croatia").'</option>
<option value="HU" data-co="Hungary">'.defcountries("Hungary").'</option>
<option value="IE" data-co="Ireland">'.defcountries("Ireland").'</option>
<option value="IS" data-co="Iceland">'.defcountries("Iceland").'</option>
<option value="IT" data-co="Italy">'.defcountries("Italy").'</option>
<option value="LI" data-co="Liechtenstein">'.defcountries("Liechtenstein").'</option>
<option value="LT" data-co="Lithuania">'.defcountries("Lithuania").'</option>
<option value="LU" data-co="Luxembourg">'.defcountries("Luxembourg").'</option>
<option value="LV" data-co="Latvia">'.defcountries("Latvia").'</option>
<option value="MC" data-co="Monaco">'.defcountries("Monaco").'</option>
<option value="MK" data-co="Macedonia">'.defcountries("Macedonia").'</option>
<option value="MT" data-co="Malta">'.defcountries("Malta").'</option>
<option value="NL" data-co="Netherlands">'.defcountries("Netherlands").'</option>
<option value="NO" data-co="Norway">'.defcountries("Norway").'</option>
<option value="PL" data-co="Poland">'.defcountries("Poland").'</option>
<option value="PT" data-co="Portugal">'.defcountries("Portugal").'</option>
<option value="RO" data-co="Romania">'.defcountries("Romania").'</option>
<option value="RU" data-co="Russia">'.defcountries("Russia").'</option>
<option value="SE" data-co="Sweden">'.defcountries("Sweden").'</option>
<option value="SK" data-co="Slovakia">'.defcountries("Slovakia").'</option>
<option value="SM" data-co="San Marino">'.defcountries("San Marino").'</option>
<option value="VA" data-co="Vatican">'.defcountries("Vatican").'</option>
</select>
</div>
</div>

<div class="gaugebox">
<div style="margin: 10px;">
<input id="city" type="search" class="form-control" placeholder="'.PLACESEARCH.'"/>
</div>
</div>

<div class="gaugebox">
<div id="quickoptions" style="height:32px;margin: 10px;"></div>
</div>

</div>

<div style="padding:6px">
<!--span style="float:right;display:inline-block;margin-top:18px;">
  '.WINDUNIT.': <select id="wspd">
    <option selected>m/s</option>
    <option>km/h</option>
    <option>mph</option>
</select></span-->
<h2 class="frcheader">'.$frcdata[frc].' '.DAYFRC2.' <span id="frcname">'.$frcdata[pname].'</span></h2>
</div>

<div style="margin-bottom:10px">
<div id="ewnnav">
<ul>
<li><span class="ewnnav col800" data-l="1" id="quickPanel">'.SHORTFRC.'</span></li>
<li><span class="ewnnav" data-l="1" id="hourPanel">'.HOURFRC.'</span></li>
<li><span class="ewnnav" data-l="1" id="dayPanel">'.$frcdata[frc].' '.DAYFRC.'</span></li>
<li><span class="ewnnav col650" data-l="1" id="nerdPanel">'.NERDFRC.'</span></li>
<li><span class="ewnnav col800" data-l="1" id="compPanel">'.COMPFRC.'</span></li>
<li><span class="ewnnav col800" data-l="1" id="mapPanel">'.MAPFRC.'</span></li>
<li><span class="ewnnav col950" data-l="1" id="dailymapPanel">'.DAILYMAP.'</span></li>
<li><span class="ewnnav col950" data-l="0" id="more">'.MORE.'</span></li>
</ul>

</div>
<div id="ewnsubnav">
<ul>
<li><span class="ewnnav" data-l="2" id="infoPanel">Info</span></li>
<li><span class="ewnnav" data-l="2" id="statsPanel">'.STSTATS.'</span></li>
<li><span class="ewnnav" data-l="2" id="linkPanel">'.QUICKLINKS.'</span></li>
</ul>
</div>
</div>

<div id="ewndiv" style="">
'.$frcdata[data].'
</div>
<div class="tabs noone" id="map_table" style="padding-top:5px;">
'.$nfrcbody.'
</div>
<div class="tabs noone" id="dailymap_table" style="padding-top:5px;">
'.$ndailybody.'
</div>
<div class="tabs noone" id="info_table" style="padding-top:5px;">
<div class="frccwiki" style="">
'.FRCCINFO.'
</div>
</div>';
if(!$is_mobile){
  $ewndata.='
  <div class="tabs noone" id="stats_table" style="padding-top:5px;">
  '.$frcdata[stats].'
  </div>
  <div class="tabs" id="link_table" style="padding-top:5px;">
  <h2 class="frcheader" style="font-size: 18px;">'.QUICKLINKS.'</h2>

  <div class="frctablewrap">
  <table style="width:100%"><tr>
  ';


$links[defcountries("Austria")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Austria").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2761369&city=Vienna">Vienna</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2778067&city=Graz">Graz</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2772400&city=Linz">Linz</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2766824&city=Salzburg">Salzburg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2775220&city=Innsbruck">Innsbruck</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2774326&city=Klagenfurt am Wörthersee">Klagenfurt am Wör...</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2762372&city=Villach">Villach</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2761524&city=Wels">Wels</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2766429&city=Sankt Pölten">Sankt Pölten</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2780741&city=Dornbirn">Dornbirn</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2764359&city=Steyr">Steyr</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2761353&city=Wiener Neustadt">Wiener Neustadt</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2779674&city=Feldkirch">Feldkirch</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2781503&city=Bregenz">Bregenz</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2760910&city=Wolfsberg">Wolfsberg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2782067&city=Baden">Baden</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2773913&city=Klosterneuburg">Klosterneuburg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2772649&city=Leoben">Leoben</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2763423&city=Traun">Traun</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2773549&city=Krems an der Donau">Krems an der Donau</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2782555&city=Amstetten">Amstetten</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2772635&city=Leonding">Leonding</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2774773&city=Kapfenberg">Kapfenberg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2771335&city=Mödling">Mödling</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2772173&city=Lustenau">Lustenau</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2776951&city=Hallein">Hallein</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2781520&city=Braunau am Inn">Braunau am Inn</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2764786&city=Spittal an der Drau">Spittal an der Drau</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2763460&city=Traiskirchen">Traiskirchen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2766922&city=Saalfelden am Steinernen Meer">Saalfelden am Ste...</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2773300&city=Kufstein">Kufstein</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2765388&city=Schwechat">Schwechat</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3323063&city=Ansfelden">Ansfelden</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2763795&city=Ternitz">Ternitz</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2782480&city=Ansfelden">Ansfelden</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2764279&city=Stockerau">Stockerau</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2779669&city=Feldkirchen in K&auml;rnten">Feldkirchen in K&auml;...</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2782052&city=Bad Ischl">Bad Ischl</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2763266&city=Tulln">Tulln</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2775742&city=Hohenems">Hohenems</a></td></tr></table></td>
';
$links[defcountries("Belgium")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Belgium").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2800866&city=Brussels">Brussels</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2803138&city=Antwerpen">Antwerpen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2797656&city=Gent">Gent</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2800481&city=Charleroi">Charleroi</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2792413&city=Liège">Liège</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2800931&city=Brugge">Brugge</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2790471&city=Namur">Namur</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2792482&city=Leuven">Leuven</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2790869&city=Mons">Mons</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2803448&city=Aalst">Aalst</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2791537&city=Mechelen">Mechelen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2793508&city=La Louvière">La Louvière</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2794055&city=Kortrijk">Kortrijk</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2796491&city=Hasselt">Hasselt</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2789786&city=Oostende">Oostende</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2786578&city=Sint-Niklaas">Sint-Niklaas</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2785341&city=Tournai">Tournai</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2797670&city=Genk">Genk</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2786824&city=Seraing">Seraing</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2787889&city=Roeselare">Roeselare</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2784821&city=Verviers">Verviers</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2790595&city=Mouscron">Mouscron</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2802031&city=Beveren">Beveren</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2799645&city=Dendermonde">Dendermonde</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2802170&city=Beringen">Beringen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2785141&city=Turnhout">Turnhout</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2799365&city=Dilbeek">Dilbeek</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2796153&city=Heist-op-den-Berg">Heist-op-den-Berg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2786545&city=Sint-Truiden">Sint-Truiden</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2792196&city=Lokeren">Lokeren</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2801154&city=Braine-lAlleud">Braine-lAlleud</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2801117&city=Brasschaat">Brasschaat</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2784604&city=Vilvoorde">Vilvoorde</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2795930&city=Herstal">Herstal</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2791961&city=Maasmechelen">Maasmechelen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2784068&city=Waregem">Waregem</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2800448&city=Châtelet">Châtelet</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2795100&city=Ieper">Ieper</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2790114&city=Ninove">Ninove</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2797779&city=Geel">Geel</a></td></tr></table></td>
';
$links[defcountries("Denmark")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Denmark").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2618425&city=Copenhagen">Copenhagen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2624652&city=Århus">Århus</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2615876&city=Odense">Odense</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2624886&city=Aalborg">Aalborg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2621942&city=Frederiksberg">Frederiksberg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2622447&city=Esbjerg">Esbjerg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2615006&city=Randers">Randers</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2618528&city=Kolding">Kolding</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2610613&city=Vejle">Vejle</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2619771&city=Horsens">Horsens</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2619528&city=Hvidovre">Hvidovre</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2621215&city=Greve">Greve</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2620425&city=Herning">Herning</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2614481&city=Roskilde">Roskilde</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2614030&city=Silkeborg">Silkeborg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2616038&city=Næstved">Næstved</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2623188&city=Charlottenlund">Charlottenlund</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2624341&city=Ballerup">Ballerup</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2621951&city=Fredericia">Fredericia</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2619856&city=Hørsholm">Hørsholm</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2620473&city=Helsingør">Helsingør</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2610319&city=Viborg">Viborg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2618415&city=Køge">Køge</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2620046&city=Holstebro">Holstebro</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2613460&city=Slagelse">Slagelse</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2611828&city=Taastrup">Taastrup</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2614600&city=Rødovre">Rødovre</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2624906&city=Albertslund">Albertslund</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2620320&city=Hillerød">Hillerød</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2612045&city=Svendborg">Svendborg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2613102&city=Sønderborg">Sønderborg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2620214&city=Hjørring">Hjørring</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2620147&city=Holbæk">Holbæk</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2621927&city=Frederikshavn">Frederikshavn</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2620964&city=Haderslev">Haderslev</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2613731&city=Skive">Skive</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2621356&city=Glostrup">Glostrup</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2614764&city=Ringsted">Ringsted</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2619377&city=Ishøj">Ishøj</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2612629&city=Stenløse">Stenløse</a></td></tr></table></td>
';
$links[defcountries("Finland")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Finland").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'658225&city=Helsinki">Helsinki</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'660158&city=Espoo">Espoo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'634963&city=Tampere">Tampere</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'632453&city=Vantaa">Vantaa</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'633679&city=Turku">Turku</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'643492&city=Oulu">Oulu</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'854735&city=Lahti">Lahti</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'650224&city=Kuopio">Kuopio</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'655194&city=Jyv&auml;skyl&auml;">Jyv&auml;skyl&auml;</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'640999&city=Pori">Pori</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'648900&city=Lappeenranta">Lappeenranta</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'632978&city=Vaasa">Vaasa</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'650946&city=Kotka">Kotka</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'655808&city=Joensuu">Joensuu</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'659180&city=H&auml;meenlinna">H&auml;meenlinna</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'660561&city=Porvoo">Porvoo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'646005&city=Mikkeli">Mikkeli</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'656913&city=Hyvink&auml;&auml;">Hyvink&auml;&auml;</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'655958&city=J&auml;rvenp&auml;&auml;">J&auml;rvenp&auml;&auml;</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'644171&city=Nurmij&auml;rvi">Nurmij&auml;rvi</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'639734&city=Rauma">Rauma</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'647751&city=Lohja">Lohja</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'651943&city=Karleby">Karleby</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'654899&city=Kajaani">Kajaani</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'638936&city=Rovaniemi">Rovaniemi</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'633591&city=Tuusula">Tuusula</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'649630&city=Kirkkonummi">Kirkkonummi</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'637219&city=Sein&auml;joki">Sein&auml;joki</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'653185&city=Kerava">Kerava</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'650859&city=Kouvola">Kouvola</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'656688&city=Imatra">Imatra</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'644450&city=Nokia">Nokia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'637292&city=Savonlinna">Savonlinna</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'639406&city=Riihim&auml;ki">Riihim&auml;ki</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'631707&city=Vihti">Vihti</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'637948&city=Salo">Salo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'654440&city=Kangasala">Kangasala</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'640124&city=Raisio">Raisio</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'654137&city=Karhula">Karhula</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'653281&city=Kemi">Kemi</a></td></tr></table></td>
';
$links[defcountries("France")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("France").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2988507&city=Paris">Paris</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2995469&city=Marseille">Marseille</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2996944&city=Lyon">Lyon</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2972315&city=Toulouse">Toulouse</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2990440&city=Nice">Nice</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2990969&city=Nantes">Nantes</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2973783&city=Strasbourg">Strasbourg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2992166&city=Montpellier">Montpellier</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3031582&city=Bordeaux">Bordeaux</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2998324&city=Lille">Lille</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2983990&city=Rennes">Rennes</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2984114&city=Reims">Reims</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3003796&city=Le Havre">Le Havre</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2980291&city=Saint-Étienne">Saint-Étienne</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2972328&city=Toulon">Toulon</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3037656&city=Angers">Angers</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3014728&city=Grenoble">Grenoble</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3021372&city=Dijon">Dijon</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2990363&city=Nîmes">Nîmes</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3038354&city=Aix-en-Provence">Aix-en-Provence</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3030300&city=Brest">Brest</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3003603&city=Le Mans">Le Mans</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3037854&city=Amiens">Amiens</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2972191&city=Tours">Tours</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2998286&city=Limoges">Limoges</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3024635&city=Clermont-Ferrand">Clermont-Ferrand</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2968254&city=Villeurbanne">Villeurbanne</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3033123&city=Besançon">Besançon</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2989317&city=Orléans">Orléans</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2994160&city=Metz">Metz</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2982652&city=Rouen">Rouen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2991214&city=Mulhouse">Mulhouse</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2987914&city=Perpignan">Perpignan</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3029241&city=Caen">Caen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3031137&city=Boulogne-Billancourt">Boulogne-Billancourt</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2990999&city=Nancy">Nancy</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3037044&city=Argenteuil">Argenteuil</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2980916&city=Saint-Denis">Saint-Denis</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2982681&city=Roubaix">Roubaix</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2972284&city=Tourcoing">Tourcoing</a></td></tr></table></td>
';
$links[defcountries("Germany")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Germany").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2950159&city=Berlin">Berlin</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2911298&city=Hamburg">Hamburg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2867714&city=München">München</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2886242&city=Köln">Köln</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2925533&city=Frankfurt am Main">Frankfurt am Main</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2928810&city=Essen">Essen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2825297&city=Stuttgart">Stuttgart</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2935517&city=Dortmund">Dortmund</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2934246&city=Düsseldorf">Düsseldorf</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2944388&city=Bremen">Bremen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2910831&city=Hannover">Hannover</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2879139&city=Leipzig">Leipzig</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2934691&city=Duisburg">Duisburg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2861650&city=Nuremberg">Nuremberg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2935022&city=Dresden">Dresden</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2947416&city=Bochum">Bochum</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2805753&city=Wuppertal">Wuppertal</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2949186&city=Bielefeld">Bielefeld</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2946447&city=Bonn">Bonn</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2873891&city=Mannheim">Mannheim</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2892794&city=Karlsruhe">Karlsruhe</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2809346&city=Wiesbaden">Wiesbaden</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2867543&city=Münster">Münster</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2921466&city=Gelsenkirchen">Gelsenkirchen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3247449&city=Aachen">Aachen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2869894&city=Mönchengladbach">Mönchengladbach</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2954172&city=Augsburg">Augsburg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2940132&city=Chemnitz">Chemnitz</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2945024&city=Braunschweig">Braunschweig</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'7289614&city=Halle Neustadt">Halle Neustadt</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2884509&city=Krefeld">Krefeld</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2911522&city=Halle">Halle</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2891122&city=Kiel">Kiel</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2874545&city=Magdeburg">Magdeburg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2860410&city=Oberhausen">Oberhausen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2925177&city=Freiburg">Freiburg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2875601&city=Lübeck">Lübeck</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2929670&city=Erfurt">Erfurt</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2912621&city=Hagen">Hagen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2844588&city=Rostock">Rostock</a></td></tr></table></td>
';
$links[defcountries("Ireland")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Ireland").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2964574&city=Dublin">Dublin</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2965140&city=Cork">Cork</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2964506&city=Dún Laoghaire">Dún Laoghaire</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2962943&city=Limerick">Limerick</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2964180&city=Galway">Galway</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2961284&city=Tallaght">Tallaght</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2960992&city=Waterford">Waterford</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2964661&city=Drogheda">Drogheda</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2964540&city=Dundalk">Dundalk</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2966022&city=Bray">Bray</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2961297&city=Swords">Swords</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2962308&city=Navan">Navan</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2964405&city=Ennis">Ennis</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2961123&city=Tralee">Tralee</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2963398&city=Kilkenny">Kilkenny</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2966131&city=Blackrock">Blackrock</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2962334&city=Naas">Naas</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2961423&city=Sligo">Sligo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2965768&city=Carlow">Carlow</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2962290&city=Droichead Nua">Droichead Nua</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2965529&city=Celbridge">Celbridge</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2960964&city=Loch Garman">Loch Garman</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2965353&city=Clonmel">Clonmel</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2962361&city=An Muileann gCearr">An Muileann gCearr</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2962961&city=Letterkenny">Letterkenny</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2966839&city=Baile Átha Luain">Baile Átha Luain</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2962974&city=Leixlip">Leixlip</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2962725&city=Malahide">Malahide</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2962026&city=Portlaoise">Portlaoise</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2965715&city=Carrigaline">Carrigaline</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2965654&city=Castlebar">Castlebar</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2963848&city=Greystones">Greystones</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2961086&city=Tullamore">Tullamore</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2962668&city=Maynooth">Maynooth</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2966794&city=Balbriggan">Balbriggan</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2966883&city=Arklow">Arklow</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2965260&city=Cobh">Cobh</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2960936&city=Wicklow">Wicklow</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2966778&city=Béal An Átha">Béal An Átha</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2961461&city=Skerries">Skerries</a></td></tr></table></td>
';
$links[defcountries("Italy")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Italy").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3169070&city=Roma">Roma</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2523227&city=Sardinia">Sardinia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3173435&city=Milano">Milano</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3172394&city=Napoli">Napoli</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3165524&city=Torino">Torino</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2523920&city=Palermo">Palermo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3176219&city=Genova">Genova</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3176959&city=Florence">Florence</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3181928&city=Bologna">Bologna</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3182351&city=Bari">Bari</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2525068&city=Catania">Catania</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3164603&city=Venice">Venice</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3164527&city=Verona">Verona</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2524170&city=Messina">Messina</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3165185&city=Trieste">Trieste</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3171728&city=Padova">Padova</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3165926&city=Taranto">Taranto</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3181554&city=Brescia">Brescia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2523630&city=Reggio di Calabria">Reggio di Calabria</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3173529&city=Mestre">Mestre</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3173331&city=Modena">Modena</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3169921&city=Prato">Prato</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2525473&city=Cagliari">Cagliari</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3171457&city=Parma">Parma</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3174659&city=Livorno">Livorno</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3176885&city=Foggia">Foggia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3171180&city=Perugia">Perugia</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3169522&city=Reggio nellEmilia">Reggio nellEmilia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3168673&city=Salerno">Salerno</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3169561&city=Ravenna">Ravenna</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3177090&city=Ferrara">Ferrara</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3169361&city=Rimini">Rimini</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2523083&city=Siracusa">Siracusa</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3167096&city=Sassari">Sassari</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3172629&city=Monza">Monza</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3171168&city=Pescara">Pescara</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3182164&city=Bergamo">Bergamo</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3176746&city=Forlì">Forlì</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3175058&city=Latina">Latina</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3164419&city=Vicenza">Vicenza</a></td></tr></table></td>
';
$links[defcountries("Netherlands")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Netherlands").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2759794&city=Amsterdam">Amsterdam</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2747891&city=Rotterdam">Rotterdam</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2745912&city=Utrecht">Utrecht</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2756253&city=Eindhoven">Eindhoven</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2746301&city=Tilburg">Tilburg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2755251&city=Groningen">Groningen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2759879&city=Almere Stad">Almere Stad</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2758401&city=Breda">Breda</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2750053&city=Nijmegen">Nijmegen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2756071&city=Enschede">Enschede</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2755003&city=Haarlem">Haarlem</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2759661&city=Arnhem">Arnhem</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2744114&city=Zaanstad">Zaanstad</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2759821&city=Amersfoort">Amersfoort</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2759706&city=Apeldoorn">Apeldoorn</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2747351&city=s-Hertogenbosch">s-Hertogenbosch</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2753801&city=Hoofddorp">Hoofddorp</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2751283&city=Maastricht">Maastricht</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2751773&city=Leiden">Leiden</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2756669&city=Dordrecht">Dordrecht</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2743856&city=Zoetermeer">Zoetermeer</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2743477&city=Zwolle">Zwolle</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2756987&city=Deventer">Deventer</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2758602&city=Born">Born</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2757345&city=Delft">Delft</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2759899&city=Alkmaar">Alkmaar</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2754652&city=Heerlen">Heerlen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2745641&city=Venlo">Venlo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2751792&city=Leeuwarden">Leeuwarden</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2754064&city=Hilversum">Hilversum</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2754394&city=Hengelo">Hengelo</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2759798&city=Amstelveen">Amstelveen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2747930&city=Roosendaal">Roosendaal</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2748413&city=Purmerend">Purmerend</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2749234&city=Oss">Oss</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2747596&city=Schiedam">Schiedam</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2746932&city=Spijkenisse">Spijkenisse</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2754447&city=Helmond">Helmond</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2745467&city=Vlaardingen">Vlaardingen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2759887&city=Almelo">Almelo</a></td></tr></table></td>
';
$links[defcountries("Norway")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Norway").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3143244&city=Oslo">Oslo</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3161732&city=Bergen">Bergen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3133880&city=Trondheim">Trondheim</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3137115&city=Stavanger">Stavanger</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3159016&city=Drammen">Drammen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3156529&city=Fredrikstad">Fredrikstad</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3149318&city=Kristiansand">Kristiansand</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3140321&city=Sandnes">Sandnes</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3133895&city=Tromsø">Tromsø</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3140084&city=Sarpsborg">Sarpsborg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3139075&city=Skien">Skien</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3163392&city=Ålesund">Ålesund</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3140390&city=Sandefjord">Sandefjord</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3153623&city=Haugesund">Haugesund</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3134331&city=Tønsberg">Tønsberg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3145375&city=Moss">Moss</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3142657&city=Porsgrunn">Porsgrunn</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3160881&city=Bodø">Bodø</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3162955&city=Arendal">Arendal</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3154084&city=Hamar">Hamar</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3148129&city=Larvik">Larvik</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3154209&city=Halden">Halden</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3136947&city=Steinkjer">Steinkjer</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3153823&city=Harstad">Harstad</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3147474&city=Lillehammer">Lillehammer</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3145580&city=Molde">Molde</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3145614&city=Mo i Rana">Mo i Rana</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3149563&city=Kongsberg">Kongsberg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3151770&city=Horten">Horten</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3155573&city=Gjøvik">Gjøvik</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3336587&city=Askøy">Askøy</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3149312&city=Kristiansund">Kristiansund</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3144987&city=Narvik">Narvik</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3147465&city=Lillestrøm">Lillestrøm</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3151917&city=Hønefoss">Hønefoss</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3139081&city=Ski">Ski</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3158300&city=Elverum">Elverum</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3162651&city=Askim">Askim</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3150851&city=Jessheim">Jessheim</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'847633&city=Alta">Alta</a></td></tr></table></td>
';
$links[defcountries("Portugal")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Portugal").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2267057&city=Lisbon">Lisbon</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2735943&city=Porto">Porto</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3373385&city=Azores">Azores</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2271772&city=Amadora">Amadora</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2742032&city=Braga">Braga</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2262963&city=Setúbal">Setúbal</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2740637&city=Coimbra">Coimbra</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2264268&city=Queluz">Queluz</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2267827&city=Funchal">Funchal</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2270503&city=Cacém">Cacém</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2732544&city=Vila Nova de Gaia">Vila Nova de Gaia</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2266977&city=Loures">Loures</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2268406&city=Évora">Évora</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2263827&city=Rio de Mouro">Rio de Mouro</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2265467&city=Odivelas">Odivelas</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2742611&city=Aveiro">Aveiro</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2271680&city=Amora">Amora</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2269041&city=Corroios">Corroios</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2271071&city=Barreiro">Barreiro</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2266249&city=Monsanto">Monsanto</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2735083&city=Rio Tinto">Rio Tinto</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2263352&city=São Domingos de Rana">São Domingos de Rana</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2267095&city=Leiria">Leiria</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2736041&city=Ponte do Lima">Ponte do Lima</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2268339&city=Faro">Faro</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2262957&city=Sesimbra">Sesimbra</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2738752&city=Guimarães">Guimarães</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2739997&city=Ermezinde">Ermezinde</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2264456&city=Portimão">Portimão</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2269594&city=Cascais">Cascais</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2738014&city=Maia">Maia</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2739848&city=Esposende">Esposende</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2739849&city=Esposende">Esposende</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2742027&city=Bragança">Bragança</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2271961&city=Almada">Almada</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2269514&city=Castelo Branco">Castelo Branco</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2272215&city=Alcabideche">Alcabideche</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2270380&city=Câmara de Lobos">Câmara de Lobos</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2271473&city=Arrentela">Arrentela</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2265788&city=Montijo">Montijo</a></td></tr></table></td>
';
$links[defcountries("Sweden")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Sweden").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2673730&city=Stockholm">Stockholm</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2711537&city=Göteborg">Göteborg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2692969&city=Malmö">Malmö</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2666199&city=Uppsala">Uppsala</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2700791&city=Kista">Kista</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2664454&city=V&auml;sterås">V&auml;sterås</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2686657&city=Örebro">Örebro</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2694762&city=Linköping">Linköping</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2706767&city=Helsingborg">Helsingborg</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2704620&city=Huddinge">Huddinge</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2702979&city=Jönköping">Jönköping</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2688368&city=Norrköping">Norrköping</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2693678&city=Lund">Lund</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2707953&city=Haninge">Haninge</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'602150&city=Umeå">Umeå</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2712414&city=G&auml;vle">G&auml;vle</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2675397&city=Solna">Solna</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2720501&city=Borås">Borås</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2676176&city=Södert&auml;lje">Södert&auml;lje</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2663536&city=V&auml;xjö">V&auml;xjö</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2691459&city=Mölndal">Mölndal</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2701680&city=Karlstad">Karlstad</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2715953&city=Eskilstuna">Eskilstuna</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2675408&city=Sollentuna">Sollentuna</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2669772&city=T&auml;by">T&auml;by</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2708365&city=Halmstad">Halmstad</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2670781&city=Sundsvall">Sundsvall</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'604490&city=Luleå">Luleå</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2667303&city=Trollh&auml;ttan">Trollh&auml;ttan</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2696334&city=Lidingö">Lidingö</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2685750&city=Östersund">Östersund</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2720383&city=Borl&auml;nge">Borl&auml;nge</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2664881&city=V&auml;rmdö">V&auml;rmdö</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2666238&city=Upplands V&auml;sby">Upplands V&auml;sby</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2715459&city=Falun">Falun</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2667094&city=Tumba">Tumba</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2702261&city=Kalmar">Kalmar</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2670879&city=Sundbyberg">Sunbyberg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2677234&city=Skövde">Skövde</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2701713&city=Karlskrona">Karlskrona</a></td></tr></table></td>
';
$links[defcountries("Switzerland")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Switzerland").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2657896&city=Zürich">Zürich</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660646&city=Genève">Genève</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2661604&city=Basel">Basel</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2661552&city=Bern">Bern</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2659994&city=Lausanne">Lausanne</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2657970&city=Winterthur">Winterthur</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2658822&city=Sankt Gallen">Sankt Gallen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2659811&city=Luzern">Luzern</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2661513&city=Biel">Biel</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2658377&city=Thun">Thun</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2660119&city=Köniz">Köniz</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660076&city=La Chaux-de-Fonds">La Chaux-de-Fonds</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2659099&city=Rapperswil">Rapperswil</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'7521936&city=Schaffhausen, City Center">Schaffhausen, Cit...</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2658761&city=Schaffhausen">Schaffhausen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660718&city=Fribourg">Fribourg</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2661169&city=Chur">Chur</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2659496&city=Neuchâtel">Neuchâtel</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2658154&city=Vernier">Vernier</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2658576&city=Sion">Sion</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'6691640&city=Lancy">Lancy</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660911&city=Emmen">Emmen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2659836&city=Lugano">Lugano</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660104&city=Kriens">Kriens</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2657941&city=Yverdon-les-Bains">Yverdon-les-Bains</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2657908&city=Zug">Zug</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2658216&city=Uster">Uster</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2659977&city=Le Châtelard">Le Châtelard</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2659601&city=Montreux">Montreux</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660727&city=Frauenfeld">Frauenfeld</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2661653&city=Baar">Baar</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3206590&city=Riehen">Riehen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2660971&city=Dübendorf">Dübendorf</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2659667&city=Meyrin">Meyrin</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2661265&city=Carouge">Carouge</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2658011&city=Wettingen">Wettingen</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2661810&city=Allschwil">Allschwil</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2659070&city=Renens">Renens</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2660108&city=Kreuzlingen">Kreuzlingen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2660221&city=Jona">Jona</a></td></tr></table></td>
';
$links[defcountries("Spain")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("Spain").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3117735&city=Madrid">Madrid</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2520308&city=Canary Islands">Canary Islands</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3128760&city=Barcelona">Barcelona</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2509954&city=Valencia">Valencia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2510911&city=Sevilla">Sevilla</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3104324&city=Zaragoza">Zaragoza</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2514256&city=Málaga">Málaga</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2513416&city=Murcia">Murcia</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2512989&city=Palma">Palma</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2515270&city=Las Palmas de Gran Canaria">Las Palmas de Gra...</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3128026&city=Bilbao">Bilbao</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2521978&city=Alicante">Alicante</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2519240&city=Córdoba">Córdoba</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3106672&city=Valladolid">Valladolid</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3105976&city=Vigo">Vigo</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3121424&city=Gijón">Gijón</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3120619&city=LHospitalet de Llobregat">LHospitalet de Ll...</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3119841&city=A Coruña">A Coruña</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3104499&city=Vitoria-Gasteiz">Vitoria-Gasteiz</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2517117&city=Granada">Granada</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2518559&city=Elx">Elx</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3114711&city=Oviedo">Oviedo</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2511174&city=Santa Cruz de Tenerife">Santa Cruz de Ten...</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3129028&city=Badalona">Badalona</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2520058&city=Cartagena">Cartagena</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3108286&city=Terrassa">Terrassa</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2516326&city=Jerez de la Frontera">Jerez de la Frontera</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3111199&city=Sabadell">Sabadell</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3116025&city=Móstoles">Móstoles</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3130616&city=Alcalá de Henares">Alcalá de Henares</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3114472&city=Pamplona">Pamplona</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3121960&city=Fuenlabrada">Fuenlabrada</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2521886&city=Almería">Almería</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3118594&city=Leganés">Leganés</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'3110044&city=San Sebastián">San Sebastián</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3109718&city=Santander">Santander</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2519752&city=Castelló de la Plana">Castelló de la Plana</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3127461&city=Burgos">Burgos</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2522258&city=Albacete">Albacete</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'3130564&city=Alcorcón">Alcorcón</a></td></tr></table></td>
';
$links[defcountries("United Kingdom")] = '
<td style="width:16.6%"><table class="nordui-table" style="width:100%;border:0">
<tr class="nordui-table-header"><td style="padding:6px;">'.defcountries("United Kingdom").'</td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2643743&city=London">London</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2655603&city=Birmingham">Birmingham</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2648579&city=Glasgow">Glasgow</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2644210&city=Liverpool">Liverpool</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2644688&city=Leeds">Leeds</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2638077&city=Sheffield">Sheffield</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2650225&city=Edinburgh">Edinburgh</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2654675&city=Bristol">Bristol</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2643123&city=Manchester">Manchester</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2644668&city=Leicester">Leicester</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2646003&city=Islington">Islington</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2652221&city=Coventry">Coventry</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2645425&city=Hull">Hull</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2653822&city=Cardiff">Cardiff</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2654993&city=Bradford">Bradford</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2655984&city=Belfast">Belfast</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2636841&city=Stoke-on-Trent">Stoke-on-Trent</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2633691&city=Wolverhampton">Wolverhampton</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2640194&city=Plymouth">Plymouth</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2641170&city=Nottingham">Nottingham</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2637487&city=Southampton">Southampton</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2639577&city=Reading">Reading</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2651347&city=Derby">Derby</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'7535661&city=London Borough of Harrow">London Borough of...</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2650839&city=Dudley">Dudley</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2641430&city=Northampton">Northampton</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2639996&city=Portsmouth">Portsmouth</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2643339&city=Luton">Luton</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2641673&city=Newcastle upon Tyne">Newcastle upon Tyne</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2639912&city=Preston">Preston</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2636503&city=Sutton">Sutton</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2642465&city=Milton Keynes">Milton Keynes</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2657832&city=Aberdeen">Aberdeen</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2636531&city=Sunderland">Sunderland</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2641181&city=Norwich">Norwich</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2634853&city=Walsall">Walsall</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2636432&city=Swansea">Swansea</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2655095&city=Bournemouth">Bournemouth</a></td></tr>
<tr class="nordui-table-b"><td class="linktd"><a href="'.$baseurl.'2637433&city=Southend-on-Sea">Southend-on-Sea</a></td></tr>
<tr class="nordui-table-a"><td class="linktd"><a href="'.$baseurl.'2636389&city=Swindon">Swindon</a></td></tr></table></td>
';
asort($links);
$i=0;
foreach ($links as $key => $value) {
  if($i % 5 == 0&&$i>0) {$ewndata.='<tr>';}
  $ewndata.= $links[$key];
  if($i % 5 == 4) {$ewndata.='</tr>';}
  $i++;
}

$ewndata.='</table>
</div>
</div>
';
} // Is mobile
$ewndata.='
<div class="info-box">
'.FRCINFOB.'
</div>
'.$nfrccreds.'
</div>
';

?>