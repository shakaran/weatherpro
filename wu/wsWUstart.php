<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without our menu system
}
$pageName		= 'wsWUstart.php';		
$pageVersion	= '2.30 2013-09-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 2.30 2013-09-10 release version 
#---------------------------------------------------------------------------
# To do: 
#   
#-------------------------------------------------------------------------------------------------
?>
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('Weather Underground History page Text') ?></h3>
<?php include('WU-History.php');?>


<h3 class="blockHead">
	<small><?php langtrans('Original script by'); ?>&nbsp;
	<a href="http://jcweather.us/scripts.php" target="_blank">Jim McMurry - jmcmurry@mwt.net</a>
	<?php langtrans('Adapted for the template by'); ?>&nbsp;
	Wim van der Kuil - <a href="http://leuven-template.eu/" target="_blank">Leuven-Template.eu</a>
	</small>
</h3>
</div>
