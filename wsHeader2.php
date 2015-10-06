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
$pageName	= 'wsHeader2.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-27-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
echo '<!-- header -->
<div id="header"> 
<div class="headerTitle">
<div style=" float: left; margin: 8px 0 5px 5px;">'.PHP_EOL;
#
if ($SITE['userChangeLang']) {			// insert code for language select 
	echo '<!-- begin language select -->'.PHP_EOL.print_language_selects($p).PHP_EOL.'<!-- end language select -->'.PHP_EOL;
} 	// end code for language select
#
echo '</div>'.PHP_EOL;
if (isset ($SITE['socialSiteSupport']) && $SITE['socialSiteSupport'] == "H") {	// insert code for facebook etc.
	echo '<div style=" float: left; padding-left: 10px;">'.PHP_EOL;
	ws_message ( '<!-- module wsHeader3.php ('.__LINE__.'): loading ./_widgets/social_buttons_header.txt -->');  
	include './_widgets/social_buttons_header.txt';
	echo '</div>'.PHP_EOL;
}

$from = array ('<br />','<br/>','<br>','-');
?>
<span  style=" float: right; margin: 0 5px 0 0;">
    	<a href="index.php?default&amp;lang=<?php echo $SITE['langBackup'] ?>" title="Browse to homepage"><?php echo langtransstr($SITE['organ']); ?></a></span>
</div>
<table class="genericTable" style=""><tr>
<td>&nbsp;</td>
<td class="headerUOM headerThermo">
	<div class="headerDiv">
		<span style="font-size: 12px;"><br /><?php echo str_replace ($from,'',langtransstr('Temperature') ); ?>:<br /></span>
		<span class="ajax" id="gizmotemparrow" style="font-size: 12px;"><?php echo $vars['ajaxtemparrow'];?></span><br />
		<span class="ajax" id="gizmotempNoU" style="font-size: 20px;"><?php echo $vars['ajaxtempNoU']; ?></span><span style="font-size: 12px;"><?php echo $SITE['uomTemp']; ?></span> 
	</div>
</td>
<td>&nbsp;</td>
<td class="headerUOM headerWind">
	<div class="headerDiv">
		<span style="font-size: 12px;"><br /><?php echo str_replace ($from,'',langtransstr('Wind') ); ?>:<br /></span>
		<span class="ajax" id="gizmowindicon" style="font-size: 12px;"><?php 	echo $vars['gizmowindicon'];?></span>&nbsp;<span class="ajax" id="gizmowinddirNoU"><?php echo $vars['ajaxwinddirNoU']; ?></span><br /> 
		<span style="font-size: 12px;" id="gizmobeaufortnum"><?php echo $vars['ajaxbeaufortnum']; ?></span>
		 Bft <span style="font-size: 12px;"  id="gizmobeaufort"><?php echo $vars['ajaxbeaufort']; ?> </span><br />
		<span class="ajax" id="gizmowindNoU" style="font-size: 20px;"><?php echo $vars['ajaxwindNoU']; ?></span><span style="font-size: 12px;"><?php echo $SITE['uomWind'] ?></span>
	</div>
</td>
<td>&nbsp;</td>
<td class="headerUOM headerRain">
	<div class="headerDiv">
		<span style="font-size: 12px;"><br /><?php echo str_replace ($from,'',langtransstr('Rain') ); ?>:<br /></span>
		<span style="font-size: 12px;"><br /><?php echo str_replace ($from,'',langtransstr('today') )?><br /></span>
		<span class="ajax" id="gizmorainNoU" style="font-size: 20px;"><?php echo $vars['ajaxrainNoU']; ?></span><span style="font-size: 12px;"><?php echo $SITE['uomRain'] ?></span>	
	</div>
</td>
<td>&nbsp;</td>
<td class="headerUOM headerHumid">
	<div class="headerDiv">
		<span style="font-size: 12px;"><br /><?php echo str_replace ($from,'',langtransstr('Humidity') ); ?>:<br /></span>
		<span class="ajax" id="gizmohumidityarrow"><?php echo $vars['ajaxhumidityarrow'];?></span><br />
		<span class="ajax" id="gizmohumidity" style="font-size: 20px;"><?php echo $vars['ajaxhumidity']; ?></span>
	</div>
</td>
<td>&nbsp;</td>
<td class="headerUOM headerBaro">
	<div class="headerDiv">
		<span style="font-size: 12px;"><br /><?php echo str_replace ($from,'',langtransstr('Pressure') ); ?>:<br /></span>
		<span class="ajax" id="gizmobaroarrow"><?php echo $vars['ajaxbaroarrow'];?><br /></span>
		<span class="ajax" id="gizmobarotrendtext"><?php echo $vars['ajaxbarotrendtext']; ?></span><br />
		<span class="ajax" id="gizmobaroNoU"  style="font-size: 16px;"><?php echo $vars['ajaxbaroNoU']; ?></span><span style="font-size: 12px;"><?php echo $SITE['uomBaro'];?></span>
	</div>
</td>
<td>&nbsp;</td>
</tr>
</table>
<?php if ((int)($gizmo) >= 1 && isset($SITE['ajaxGizmoShow']) and $SITE['ajaxGizmoShow']) 
	{ ?>
<div class="headerGizmo" >		
		<noscript>[<?php langtrans('Enable JavaScript for live updates'); ?>]&nbsp;</noscript>
		<span class="ajax" id="gizmoindicator"><?php echo $vars['ajaxindicator']; ?></span>:&nbsp;
		<span class="ajax" id="gizmodate"><?php echo $vars['ajaxdate'];?></span>&nbsp; 
		<span class="ajax" id="gizmotime"><?php echo $vars['ajaxtime'];?></span>
		<span id="ajaxcounter"></span>&nbsp;<?php langtrans('seconds ago');?>&nbsp;&nbsp;&nbsp;
</div>
<?php 
	} else echo '<br />'.PHP_EOL; ?>
</div>
<!-- end header -->
<?php
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
