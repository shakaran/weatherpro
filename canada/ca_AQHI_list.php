<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ca_AQHI_list.php';
$pageVersion	= '3.20 2015-08-02';
#-----------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version  ONLY
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->");
# ----------------------------------------------------------------------
# utility program to display during install of AQHI area codes /names
# the table is retrieved once and stored in the cache by ec_list_aqhi.php
# ----------------------------------------------------------------------
$script	= 'canada/ec_list_aqhi.php';
ws_message (  '<!-- module ca_AQHI_list.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
#
echo '<script type="text/javascript" src="javaScripts/sorttable.js"></script>
<div class="blockDiv" style="border-left: none; border-right: none;">
<h3 class="blockHead">'.$page_title.'<br />'.langtransstr('You can sort the table on the values in the columns by clicking in the corresponding heading').'</h3>
<table border="1" class="sortable genericTable" style="text-align: left;">
<thead><tr>
<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('code').'</th>
<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('zone').'</th>
<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('region').'</th>
<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('zone').'</th>
<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('region').'</th>
</tr>
</thead>
<tbody>';
#
$cnt_arr        = count ($region_array);
foreach ($region_array as $key => $arr) {
        echo    '<tr>
<td style=" padding-left: 5px;">'.$arr['region_code'].'</td>
<td style=" padding-left: 5px;">'.$arr['zone_name']['en'].'</td>
<td style=" padding-left: 5px;">'.$arr['region_name']['en'].'</td>
<td style=" padding-left: 5px;">'.$arr['zone_name']['fr'].'</td>
<td style=" padding-left: 5px;">'.$arr['region_name']['fr'].'</td>
</tr>'.PHP_EOL;      
}
echo'</tbody>
</table>
</div>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
