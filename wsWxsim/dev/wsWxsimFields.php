<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without our menu system
}
$pageName		= 'wsWxsimFields.php';		// #### change to exact page name
$pageVersion	= '2.30 2013-09-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 2.30 2013-09-09 release version 
#-----------------------------------------------------------------------------------------
# Support script to display table with known field-names and descriptions 
#  
#-----------------------------------------------------------------------------------------
#
echo '<script type="text/javascript" src="javaScripts/sorttable.js"></script>'.PHP_EOL;
#
include ($SITE['wxsimDir'].'wsFieldsArr.php');
$fields = array ();
#
foreach ($lrFieldsArr as $key => $value) {
	if (isset ($fields [$value]) ) {
		$fields [$value][$key] = 'Multiple defined lastret.txt';
	} else {
		$fields [$value]['lr'] = $key;
	}
}
foreach ($csvFieldsArr as $key => $value) {
	if (!isset ($fields [$value]) ) {   		// no lastret definition, first csv definition
		$fields [$value]['csv'] = $key;
		$fields [$value]['lr']  = 'zzzzz';
		continue;
	}
	if 	(!isset ($fields [$value]['csv']) ) {	// lastret defined and only one csv so far
		$fields [$value]['csv'] = $key;
		continue;		
	}  
	$fields [$value][$key] = 'Multiple defined latest.csv ';
}
ksort ($fields);
#
?>
<h3>Field names and description used in WXSIM data files</h3>
<p style="background-color: white; ">
<span><strong>English</strong></span>
<br />First column to be implemented.
<br />Column "lastret.txt contains the field names as used by WRET => lastret.txt output,
<br />&nbsp;&nbsp;&nbsp;not all fields are present in your own lastret.txt.
<br />Column "latest.csv contains the field names as contained in latest.csv,
<br />&nbsp;&nbsp;&nbsp;normally all fields should be present when using the professional version.
<br />The page is sorted on the field/column "field description".
<br />You can sort the table on column 2-4 by clicking in the heading. 
First click descending, second click ascending.
</p>
<p style="background-color: white;">
<span><strong>Nederlands</strong></span>
<br />De eerste kolom wordt binnenkort gebruikt om de velden te groeperen op type.
<br />Kolom "lastret.txt bevat de veldnamen zoals gebruikt door de WRET => lastret.txt output generatie ,
<br />&nbsp;&nbsp;&nbsp;niet alle velden zijn aanwezig in iedere lastret.txt omdat het aantal velden beperkt is.
<br />Kolom  "latest.csv calle veldnamen uit het latest.csv bestand (=backup laatste forecast),
<br />&nbsp;&nbsp;&nbsp;alle velden komen voor als u een professional version hebt van WXSIM.
<br />De pagina is gesorteerd op "field description".
<br />De table kan gesorteerd worden op kolom 2-4 door te klikken op een kolomkop. 
Eerste klik sorteert aflopend, tweede klik oplopend.
</p>

<table class="sortable fields" style="background-color: white; border:1px solid black; text-align: left; border-collapse: collapse;">
<tr>
<th style="text-align: center;" class="sorttable_nosort">fieldgroup</th>
<th style="text-align: center; cursor: n-resize;">Lastret.txt</th>
<th style="text-align: center; cursor: n-resize;">latest.csv</th>
<th style="text-align: center; cursor: n-resize;">field description</th>
</tr>
<?php
foreach ($fields as $key => $arr) {
	echo '<tr><td>&nbsp;</td><td';
	if (!isset ($arr['lr']) ) {$arr['lr'] = 'zzzzz';}
	if ($arr['lr'] <> 'zzzzz' ) {echo '>'.$arr['lr'];} else {echo ' style="text-align: right; color: red;">mising field name';}	
	echo '</td><td';
	if (!isset ($arr['csv']) ) {$arr['csv'] = 'xx';}
	if ($arr['csv'] <> 'xx' ) {echo '>'.$arr['csv'];} else {echo ' style="text-align: right; color: red;">mising lfield name';}	
	echo '</td><td>'.$key.'</td>';
	if (count($arr) > 2){
		foreach ($arr as $key2 => $field) {
			if ($key2 <> 'lr' &&  $key2 <> 'csv' ) {
				echo '</tr><tr><td>&nbsp;</td>';
				if ($field == 'Multiple defined lastret.txt') {
					echo '<td>'.$key2.'</td><td>&nbsp;</td>';
				} else {
					echo '<td>&nbsp;</td><td>'.$key2.'</td>';
				}
				echo '<td>'.$field.'</td>';
			}		
		}
	}
	echo '</tr>'.PHP_EOL;
}
?>
</table>