<?php
/**
 * Project:   WU GRAPHS
 * Module:    WUG-tabsm.php 
 * Copyright: (C) 2010 Radomir Luza
 * Email: luzar(a-t)post(d-o-t)cz
 * WeatherWeb: http://pocasi.hovnet.cz 
 */
################################################################################
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 3
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>. 
################################################################################

include_once('./WUG-settings.php');
$tabsGpath = 'wxwugraphs.php';
if ($standAlone) {
  $tabsGpath = 'wugraphs.php';
}

header('Content-Type: text/html; charset='.strtolower($WUGcharset));
// prevent caching (php)
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: ' . gmdate(DATE_RFC1123, time()-1));

$tabsGpath = str_replace('wxwugraphs/'.basename($_SERVER["PHP_SELF"]), $tabsGpath, $_SERVER["PHP_SELF"]);
echo '
<script type="text/javascript">
	    $(function() {
        $("#WU-Gm-Tabs").tabs({
          cookie: { expires: 30, path: \''.$tabsGpath.'\' }, 
          spinner: \''.$Tloading.'\', 
          load: function(event, ui) {
              $(".ui-tabs-panel:not(ui-tabs-hide) .gload", this).fadeIn(300); // for loader
              
              // Code source http://sonspring.com/journal/jquery-iframe-sizing
        			// For other good browsers.
        			$("iframe").load(function()
        				{
                  // Set inline style to equal the body height of the iframed content.        					
                  this.style.height = this.contentWindow.document.body.offsetHeight + '.$heightCorr.' + "px";
                  $(".njump").hide();
                  $(".gload").fadeOut(300); // for loader
                  var tiframe = $(this)[0].contentWindow;
                  $(tiframe).unload(function(){
                    $("#WU-Gm-Tabs .ui-tabs-panel:not(ui-tabs-hide) .gload").fadeIn(300); // for loader
                  });
        				}
        			);        		
              
              // FOR WU GRAPHS COMBINED DATASOURCE
              if ("'.$dataSource.'" == "wutowdmysql") {
                $(".WUGiframe").load(function(){
                  selectedWugDate = new Date();
                  selectedWugDate.setFullYear($.cookie("wu_graph_y"),$.cookie("wu_graph_m"),1);
                  startWDdb = new Date();
                  startWDdb.setFullYear('.$wdSinceY.','.$wdSinceM.',1);
                  if (selectedWugDate > startWDdb) {
                    $(".c-rside").hide();
                  } else {
                    $(".c-rside").show();
                  }
                });
              }
          }        
        });          
      });
        
</script>

  <div id="WU-Gm-Tabs" class="WUG-subtab">
  	 <ul style="/*height:25px;*/">	 
  		<li><a href="./wxwugraphs/iframe-inc.php?pg=graphm1"><span>'.$TempTran.'</span></a></li>
  		<li><a href="./wxwugraphs/iframe-inc.php?pg=graphm3"><span>'.$HumTran.'</span></a></li>  		
      <li><a href="./wxwugraphs/iframe-inc.php?pg=graphm2"><span>'.$DPTran.'</span></a></li>  		
  		<li><a href="./wxwugraphs/iframe-inc.php?pg=graphm4"><span>'.$BaroTran.'</span></a></li> 
      <li><a href="./wxwugraphs/iframe-inc.php?pg=graphm5"><span>'.$WSTran.'</span></a></li>
      <li><a href="./wxwugraphs/iframe-inc.php?pg=graphm6"><span>'.$PrecTran.'</span></a></li>
';
if (($showSolar && $calcSolar) || ($dataSource == 'mysql' && $showSolar)) {
  echo '      <li><a href="./wxwugraphs/iframe-inc.php?pg=graphm7"><span>'.$SunTran.'</span></a></li>'."\n";
} 
echo '	  </ul>
	</div>
';
?>
