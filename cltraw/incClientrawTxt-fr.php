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
<p>Le tableau suivant contient environ 2000 donn�es m�t�orologiques qui sont transmises de l'ordinateur pour mettre notre propre site web  � jour.
<br />Ces donn�es sont aussi mises � disponibilit� � d'autres sites m�t�orologiques ...
<p>
S�lectionnez le fichier � afficher:
EOT;
$lientrawTxt[1] = <<<EOT
<p>Ces mesures m�t�orologiques  brutes sont � nouveau  "traduites / expliqu�s" dans le tableau suivant.
<br />Vous pouvez appuyer sur l' ent�te de colonne pour trier les trois premi�res colonnes.
<br />Par exemple, un premier clic sur l'ent�te de la colonne "Type"  trie  la table descendante sur le type de code, un deuxi�me clic la trie ascendante.
</p>
<p>La colonne "type" contient une indication du sorte de donn�es qui sont repr�sent�es. � cette fin, les abr�viations suivantes sont utilis�es:
<br /><br />
<b>B</b>ool, <b>C</b>elcius, <b>D</b>egr�s, <b>F</b>eet (pieds), baro en <b>H</b>pa, <b>I</b>c�ne, <b>K</b>nots (noeuds), 
<b>L</b>abel,<br />la pluie en <b>M</b>M, <b>N</b>umber (quantit�), <b>P</b>ourcentage, <b>T</b>emps, <b>U</b>nused (inutilis�)
</p>
EOT;
?>