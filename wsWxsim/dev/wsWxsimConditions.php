<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without our menu system
}
$pageName		= 'wsWXSIMconditions.php';
$pageVersion	= '2.30 2013-09-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 2.30 2013-09-09 release version 
#-------------------------------------------------------------------------------------------------
ini_set('display_errors', 'On'); 
error_reporting(E_ALL);

?>
<script type="text/javascript" src="javaScripts/sorttable.js"></script>
<?php
include ('./wsWxsim/wsConditionsArr.php');
?>
<h3>Condition codes and description used in WXSIM data files</h3>
<p style="background-color: white; ">
<span><strong>English</strong></span>
<br />First column groups condition at highest level used in the scripts.
<br />Column "condition" contains the text used in the two WX fields in latest.csv and the WEATHER field in lastret.txt
<br />You will find a lot of "double" conditions which differ only in the last character. This is caused be the 1 character shorter description in lastret.txt as opposed to the WX descriptions in latest.csv
<br />Column "text" contains the text used to translate the condition description to the correct language
<br />Column "code' is used to determine the correct icon code of three digits.
<br />&nbsp;&nbsp;the group "sky" is used for the first digit, 
<br />&nbsp;&nbsp;the precipitation is used for the last two digits
<br />You can sort the table on column 2-4 by clicking in the heading. 
First click descending, second click ascending.
</p>
<p style="background-color: white;">
<span><strong>Nederlands</strong></span>
<br />De eerste kolom bevat de naam van de hoogste groep waartoe deze conditie behoort
<br />Kolom "condition" bevat de tekst uit de twee wx velden van latest.csv en uit weather van lastret.txt
<br />Kolom "text" bevat de door ons gebruikte interne tekst om te vertalen naar de ingesteld "language"
<br />Kolom "code" wordt gebruikt om de icoon-code (drie cijfers) van de weer conditie mee samen te stellen
<br />&nbsp;&nbsp;de group "sky"  is het eerste cijfer, 
<br />&nbsp;&nbsp;de neerslag e.d. het tweede en derde cijfer.  
<br />De tabel kan gesorteerd worden op de kolommen door te klikken op een kolomkop. 
Eerste klik sorteert aflopend, tweede klik oplopend.
</p>
<table class="sortable fields" style="background-color: white; border:1px solid black; text-align: left; border-collapse: collapse;">
<tr>
<th style="text-align: left; cursor: n-resize;">group</th>
<th style="text-align: left; cursor: n-resize;">condition</th>
<th style="text-align: right; cursor: n-resize;">code&nbsp;</th>
<th style="text-align: left; cursor: n-resize;">text</th>
</tr>
<?php
foreach ($conditionsArr as $key => $arr) {
	echo '<tr><td>'.$arr['cond'].'</td><td>'.$key.'</td><td style="text-align: right;">'.$arr['code'].'&nbsp;</td><td>'.$arr['text'].'</td></tr>'.PHP_EOL;
}
?>
</table>