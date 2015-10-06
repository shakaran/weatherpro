<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName='incClientrawTxt-nl.php';
$SITE['wsModules'][$pageName]	= 'version 0.01 2011-11-26';
#  check to see this is the real script
$pfile = basename(__FILE__);
if ($pfile <> $pageName) {
	$SITE['wsModules'][$pfile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pfile.' ==== '.$SITE['wsModules'][$pfile]." -->\n";
#-------------------------------------------------------------------------------------------------

$lientrawTxt[0] = <<<EOT
<p>De volgende tabel bevat ongeveer 2000 weersgegevens die doorgegeven worden vanuit de weercomputer om onze eigen website up-to-date te houden.
<br />Deze gegevens worden tevens aan andere weerwebsites ter beschikking gesteld..</p>
<p>
In het invoerveld hieronder kunt u het webadres van uw eigen clientraw files intypen.<br /> <br />
Gebruik de vorm:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;www.yourwebsite.com/path&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bijvoorbeeld:&nbsp;&nbsp;www.weerstation-wilsele.be/upload/ 
</p>
EOT;
$lientrawTxt[1] = <<<EOT
<p>Deze onbewerkte weermetingen worden "vertaald / toegelicht" in de volgende tabel.
<br /><br />
<b>SORTEREN</b> U kunt de tabel op de eerste drie kolommen sorteren door in de kolomkop te klikken.
<br />Bijvoorbeeld: een eerste klik op de kolomkop "Type" sorteert de tabel aflopend op de code voor Type, een tweede klik sorteert oplopend.
</p>
<b>INDEX</b> Er is ook een aparte pagina waar de gegevens van alle velden uit de 4 clientraw bestanden zijn gesorteerd op veldnaam. Dus alle "temp" velden staan bijelkaar, etc.
Kijk hiervoor in het menu.
<p style="margin-bottom: 1px;">
<b>KODES</b> De kolom "Type" bevat een aanduiding van de soort gegevens die worden afgebeeld.
<br />Hiervoor worden de volgende codes gebruikt:
<br />
</p>
<table class="genericTable" style="text-align: left;">
<tr><td><b>B</b>ool</td><td><b>C</b>elcius</td><td><b>D</b>egrees (graden)</td><td><b>F</b>eet</td><td>baro in <b>H</b>pa</td><td><b>I</b>coon</td></tr>
<tr><td><b>K</b>nots (knopen)</td><td><b>L</b>abel</td><td>regen in <b>M</b>M</td><td><b>N</b>umber (getal)</td><td><b>P</b>ercent</td><td><b>T</b>ijd</td></tr>
<tr><td colspan="6"><b>U</b>nused (niet gebruikt)</td></tr>
</table>
<p>

EOT;
?>