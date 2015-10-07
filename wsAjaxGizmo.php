<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsAjaxGizmo.php';
$pageVersion	= '3.00 2015-04-02';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2015-04-02 release 2.7 version
# -------------------------------------------------------------------------------------------------
#
#---------------------------------------------------------------------------
$uomTemp 	= $SITE['uomTemp'];  	// tempchangehour
$uomPerHour     =  langtransstr(trim($SITE['uomPerHour']) );  // tempchangehour
// --- end of settings -------------------
?>
<!--start of AJAX gizmo -->
<div class="ajaxgizmo doNotPrint">
	<noscript>[<?php langtrans('Enable JavaScript for live updates'); ?>]&nbsp;</noscript>
<span class="ajax" id="gizmoindicator"><?php echo $vars['ajaxindicator']; ?></span>:&nbsp;
<span class="ajax" id="gizmodate"><?php echo $vars['ajaxdate'];?></span>&nbsp; 
<span class="ajax" id="gizmotime"><?php echo $vars['ajaxtime'];?></span>
<span id="ajaxcounter"></span>&nbsp;<?php langtrans('seconds ago');?>
<br/>
<span class="ajaxcontent0" style="display: none">
	<span class="ajax" id="gizmocurrentcond"><?php echo $vars['ajaxcurrentcondalt']; ?></span>
</span>
<span class="ajaxcontent1" style="display: none"><?php
$from = array ('<br />','<br/>','<br>','-');
echo str_replace ($from,'',langtransstr('Temperature') ); 
?>: 
	<span class="ajax" id="gizmotemp"><?php echo $vars['ajaxtemp']; ?></span>            
	<span class="ajax" id="gizmotemparrow"><?php echo $vars['ajaxtemparrow'];?></span>&nbsp;
	<span class="ajax" id="gizmotemprate">
<?php if(isset($ws['tempDelta'])) {echo $ws['tempDelta'] . '&nbsp;' .$uomTemp.' '.$uomPerHour; }?>
	</span> 
</span>
<span class="ajaxcontent2" style="display: none"><?php 
echo str_replace ($from,'',langtransstr('Humidity'));
?>: 
	<span class="ajax" id="gizmohumidity"><?php echo $vars['ajaxhumidity']; ?>%</span>
	<span class="ajax" id="gizmohumidityarrow"><?php echo $vars['ajaxhumidityarrow'];?></span>&nbsp;
</span>
<span class="ajaxcontent3" style="display: none"><?php
echo str_replace ($from,'',langtransstr('Dew Point')); 
?>: 
	<span class="ajax" id="gizmodew"><?php echo $vars['ajaxdew'];  ?></span>
	<span class="ajax" id="gizmodewarrow"><?php echo $vars['ajaxdewarrow'];?></span>
</span>
<span class="ajaxcontent4" style="display: none"> 
	<span class="ajax" id="gizmowindicon"><?php echo $vars['gizmowindicon'];?></span> 
	<span class="ajax" id="gizmowinddir"><?php echo $vars['ajaxwinddir']; ?></span>&nbsp; 
	<span class="ajax" id="gizmowind"><?php echo $vars['ajaxwind']; ?></span>
</span>
<span class="ajaxcontent5" style="display: none"><?php
echo str_replace ($from,'',langtransstr('Gust') ); 
?>: 
	<span class="ajax" id="gizmogust"><?php echo $vars['ajaxgust']; ?></span>
</span>
<span class="ajaxcontent6" style="display: none"><?php 
echo str_replace ($from,'',langtransstr('Barometer') ); 
?>: 
	<span class="ajax" id="gizmobaro"><?php echo $vars['ajaxbaro']; ?></span>
	<span class="ajax" id="gizmobaroarrow"><?php echo $vars['ajaxbaroarrow'];?></span>
	<span class="ajax" id="gizmobarotrendtext"><?php echo $vars['ajaxbarotrendtext']; ?></span>
</span> 
<span class="ajaxcontent7" style="display: none"><?php 
echo 
str_replace ($from,'',langtransstr('Rain') ). ' '. 
str_replace ($from,'',langtransstr('today') ); 
?>: 
	<span class="ajax" id="gizmorain"><?php echo $vars['ajaxrain']; ?></span>
</span>
<?php
if ($SITE['UV']) {
?>
<span class="ajaxcontent8" style="display: none"><?php 
echo str_replace ($from,'',langtransstr('UV Index') ); 
?>: 
	<span class="ajax" id="gizmouv"><?php echo $vars['ajaxuv'] ?></span>&nbsp;
	<span style="color: #ffffff">
		<span class="ajax" id="gizmouvword"><?php echo $vars['ajaxuvword']."\n"; ?></span>
	</span>
</span>
<?php
}
?>
</div>	
<!--end  ajaxgizmo   -->
