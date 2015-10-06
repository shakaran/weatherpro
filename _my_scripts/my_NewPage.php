<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;			# display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'my_NewPage.php';                     ######
$pageVersion	= '0.00 2015-07-22';                    ######
#-----------------------------------------------------------------------
# 0.00 2015-07-22 first version				######
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# ----------------------------------------------------------------------
#
# HOW TO:
# 1.0	change two lines above (####) to reflect your new page name and version (starting with 0.00 is a good idea)
#
# 1.1	save in directory weather2/_my_scripts/ with the correct name ( and with characterset UTF-8 when your editor has this option)
#
# 1.2	insert your code in PHP and/or HTML and save again
#  
# 2.0	make an entry in the menufile (wsMenuData.xml) for example: 
#	<item nr='400'  show='yes' link = '_my_scripts/wsNewPage.php'  caption='wsNewPage bla bla' />
#
#		for 	item:	choose free number  
#			link: 	the name of your page (1.1)   
#			caption:small description for menu
#
# 3.0	test your new page
#-------------------------------------------------------------------------------------------------
# settings:
#-------------------------------------------------------------------------------------------------
$page_title     = langtransstr('This is a new page');
#
?>
<div class="blockDiv"><!-- leave this opening div as it is needed  a nice page layout -->

<h3 class="blockHead"><?php echo $page_title; ?></h3>
<?php
# Example of how to include some php code
echo 'hello world from PHP &nbsp;&nbsp;&nbsp;<a href="http://www.php.net/" target="_blank">more info on PHP</a><br /><br />'.PHP_EOL;
?>
<!--  and an example here with some HTML  -->

hello world in HTML <a href="http://www.w3schools.com/tags/tag_iframe.asp" target="_blank">more info on HTML</a><br /><br />

<!--  if you want to include a page from some other site you can use an iframe   -->

or put your information in a frame, you can find all information about that at the link above:
<br /><br />
<iframe src="http://www.w3schools.com/tags/tag_iframe.asp" style ='width:100%; height: 500px; border: none; background: transparent; vertical-align: bottom;'></iframe>


</div><!-- leave this closing div as it is needed  for the opennig div -->