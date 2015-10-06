<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName='incClientrawTxt-fr.php';
$SITE['wsModules'][$pageName]	= 'version 0.01 2011-11-26';
#  check to see this is the real script
$pfile = basename(__FILE__);
if ($pfile <> $pageName) {
	$SITE['wsModules'][$pfile]	= 'this file loaded instead of '.$pagename;
}
echo '<!-- module '.$pfile.' ==== '.$SITE['wsModules'][$pfile]." -->\n";
#-------------------------------------------------------------------------------------------------

$lientrawTxt[0] = <<<EOT
<p>Le tableau suivant contient environ 2000 données météorologiques qui sont transmises de l'ordinateur pour mettre notre propre site web  à jour.
<br />Ces données sont aussi mises à disponibilité à d'autres sites météorologiques ...
<p>
Sélectionnez le fichier à afficher:
EOT;
$lientrawTxt[1] = <<<EOT
<p>Ces mesures météorologiques  brutes sont à nouveau  "traduites / expliqués" dans le tableau suivant.
<br />Vous pouvez appuyer sur l' entête de colonne pour trier les trois premières colonnes.
<br />Par exemple, un premier clic sur l'entête de la colonne "Type"  trie  la table descendante sur le type de code, un deuxième clic la trie ascendante.
</p>
<p>La colonne "type" contient une indication du sorte de données qui sont représentées. À cette fin, les abréviations suivantes sont utilisées:
<br /><br />
<b>B</b>ool, <b>C</b>elcius, <b>D</b>egrés, <b>F</b>eet (pieds), baro en <b>H</b>pa, <b>I</b>cône, <b>K</b>nots (noeuds), 
<b>L</b>abel,<br />la pluie en <b>M</b>M, <b>N</b>umber (quantité), <b>P</b>ourcentage, <b>T</b>emps, <b>U</b>nused (inutilisé)
</p>
EOT;
?>