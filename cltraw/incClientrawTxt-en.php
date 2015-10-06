<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName='incClientrawTxt-en.php';
$SITE['wsModules'][$pageName]	= 'version 0.01 2011-11-26';
#  check to see this is the real script
$pfile = basename(__FILE__);
if ($pfile <> $pageName) {
	$SITE['wsModules'][$pfile]	= 'this file loaded instead of '.$pagename;
}
echo '<!-- module '.$pfile.' ==== '.$SITE['wsModules'][$pfile]." -->\n";
#-------------------------------------------------------------------------------------------------

$lientrawTxt[0] = <<<EOT
<p>The following tables consist of about 2000 data points that are actively updating from the local Weather Station equipment to provide this website with data.
<br />The data is also shared across the internet to many weather related websites.</p>
<p>
In the input-box can enter your own website / path to your clientraw files :<br />
Please use   www.yourwebsite.com/path  for instance   : www.weerstation-wilsele.be/upload/   </p>
EOT;
$lientrawTxt[1] = <<<EOT
<p>This raw data is "translated" in the following table.
<br />You can sort the table on the first 3 rows by clicking in the corrosponding heading.
<br />F.i. First click on Type-heading will sort the table descending, second click ascending on Type-code. 
</p>
<p>The colomn "Type" contains the Type of data for which te following letters are used:
<br />
<b>B</b>ool, <b>C</b>elcius, <b>D</b>egrees, <b>F</b>eet, Baro in <b>H</b>pa, <b>I</b>con, <b>K</b>nots, 
<b>L</b>abel, Rain in <b>M</b>M, <b>N</b>umber, <b>P</b>ercent, <b>T</b>ime, <b>U</b>nused
</p>
EOT;
?>