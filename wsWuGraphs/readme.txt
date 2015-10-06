Installatie instructies WU Graphs binnen de Leuven Template

Alleen instaleren als u informatie aanlevert aan WeatherUnderground!

Punt 1 - 4.4 is de installatie van de WU Graphs

1.	Download de WU Graphs van   http://pocasi.hovnet.cz/wxwug.php?lang=en
	Hier vindt u ook allerlei toelichting.
	
	De huidige versie bij het schrijven van dit document is v 1.8.0 (2011/09/05 11:30 UTC)
	Directe downloadlink: http://pocasi.hovnet.cz/ccount/click.php?id=1
	
2.	Unzip de download

3. 	Verplaats naar de map van de template  (normaal is dat weather2/)
	
	3.1	de map wxwugraphs
	
	3.2	het script wugraphs.php 
	
4.  Dubbelklik het dokument README-wxwugraphs.html
	Ga verder met het tweede aandachtspunt, immers de unzip e.d. hebt u al hierboven gedaan.
	Hieronder de Nederlandse vertaling van die teksten.
	Type voor www.uwwebsite.com/ natuurlijk wewl uw eigen website-naam.
	En als de template in een andere map dan weather2/ is geinstaleerd vervangt u weather2/ door de naam van uw map. 
	
	4.1	Maak de map cache/ in de map weather2/wxwugraphs/ beschrijfbaar voor PHP door de rechten aan te passen naar 777

	4.2 Type in de browser  www.uwwebsite.com/weather2/wxwugraphs/configurator.php
		Het password is wugraphs
		Pas alles aan wat aangepast moet worden.
		LET OP: 
		a.	$cookieEnabled = false;  als u aan de nl cookiewetgeving wilt voldoen.
		b.	De plaats van de clientraw voor WD  is ../uploadWD   en MH  ../uploadMH
	
	4.2 alternatief: Als u problemen hebt met de configurator kunt u ook de settings in WUG-settings.php handmatig aanpassen.
	
	4.3	Controleer of alles goed staat middels www.uwwebsite.com/weather2/wxwugraphs/WUG-test.php
	
	4.4 Test of de grafieken verschijnen met www.uwwebsite.com/weather2/wxwugraphs/wugraphs.php

Punt 5 en verder is de installatie van het scripts om WU Graphs aan te roepen vanuit de Leuven Template.

5.  Maak een backup van weather2/wsMenuData.xml
	Plaats in weather2/wsMenuData.xml de verwijzing naar de juiste scripts
	
	<item 	nr="62-2" 			show='yes'
		link="wsWuGraphs/wsWuGraphs.php"
		caption="WU History Graphs" 
		title="Weather Underground History page Graphs"
		head="wsWuGraphs/wsWuGraphsHead.php"
	/>

6. 	Kopieer de map wsWuGraphs naar de template map weather2/

7. 	Roep uw website op en controleer dat er in het menu een keuze is voor WU History Graphs

8. 	Voer deze keuze uit en de grafieken moeten verschijnen.

9.	De keuze-kalender is soms wat te smal waardoor het jaar niet goed zichtbaar is.
	Verander regel 88 EN 110  in WUG-form.php
	van
	      .ui-datepicker {width:17.5em!important;}
	naar
	      .ui-datepicker {width:19.5em!important;}

Veel succes, Wim