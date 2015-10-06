<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName='incClientrawTxt-de.php';
$SITE['wsModules'][$pageName]	= 'version 0.01 2011-11-26';
#  check to see this is the real script
$pfile = basename(__FILE__);
if ($pfile <> $pageName) {
	$SITE['wsModules'][$pfile]	= 'this file loaded instead of '.$pagename;
}
echo '<!-- module '.$pfile.' ==== '.$SITE['wsModules'][$pfile]." -->\n";
#-------------------------------------------------------------------------------------------------

$lientrawTxt[0] = <<<EOT
<p>Die folgende Tabelle enthält etwa 2000 Wetter-Daten die aus unseren Computer weitergeleitet werden damit wir unsere eigene Website up-to-date zu halten.
<br />Diese Wetterdaten stehen auch anderen Websiten zur Verfügung ..
<p>
Wählen Sie die Datei die angezeigt werden soll:
EOT;
$lientrawTxt[1] = <<<EOT
<p>Diese Messungen werden "übersetzt / erklärt" in der folgenden Tabelle.
<br />Sie können die Tabelle auf den ersten drei Spalten sortieren. Klicken Sie einfach in der Spaltenüberschrift.
<br />Zum Beispiel, Einen ersten Klick auf der Spalte "Type" und die Tabelle wird absteigend sortiert.
ein zweiter klick sortiert aufsteigend.</p>
<p>Die Spalte "Type" enthält einen Hinweis auf die Art der Daten. Zu diesem Zweck werden die folgenden Abkürzungen verwendet:
<br />
<b>B</b>ool, <b>C</b>elcius, <b>D</b>egrees (Grad) , <b>F</b>eet, baro in <b>H</b>pa, <b>I</b>coon, <b>K</b>noten, 
<b>L</b>abel,<br />Regen in <b>M</b>M, <b>N</b>umber (Anzahl), <b>P</b>ercent, Zei<b>T</b>, <b>U</b>ngenutzt
</p>
EOT;
?>
