<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
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
$pageName	= 'ws_spc_day4_8.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.10 2015-01-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.10 2015-01-05 first release version
# ----------------------------------------------------------------------
if (isset ($refresh)  && $refresh > 600 ) echo '<meta http-equiv="refresh" content="'.$refresh.'"/>'.PHP_EOL;     // just in case - it was in the old scripts
?>
<br />
<table class="genericTable" style="max-width: 1100px;  width:98%; margin: 0 auto;">
  <tr>
     <td style="text-align: left; width: 80%; padding-right: 4px; " >
        <b>   &nbsp;        
    </td>
     <td>
     <p style="font-size:14px; font-weight:bold;">Day 1</p>
     </td>
   </tr>
   <tr>  
      <td style="text-align: left; width: 80%; padding-right: 4px; " >
        <div style="text-align:center; max-width:860px; margin: 0 auto; width: 100%;">               
                <img alt="" src="http://www.spc.noaa.gov/products/exper/day4-8/day48prob.gif" style="width: 100%;"/>                       
                <table style="width: 100%; vertical-align: center; text-align: center; font-family: Arial, Helvetica, sans-serif Courier; font-weight: normal; font-size: small; border: 1px solid #000; border-collapse: collapse;">
                    <tbody>
                        <tr>
                        <td style="color:#ffffff; width: 4%; background-color:#F00; border: 1px solid #000;">D4</td><td style="width:44%; border: 1px solid #000;"><?php echo date($SITE['dateLongFormat'], strtotime("+3 days")); ?> - <?php echo date($SITE['dateLongFormat'], strtotime("+4 days")); ?></td>
                        <td style="color:#ffffff; width: 4%; background-color:#104e8a; border: 1px solid #000;">D7</td><td style="width:48%; border: 1px solid #000;"><?php echo date($SITE['dateLongFormat'], strtotime("+6 days")); ?> - <?php echo date($SITE['dateLongFormat'], strtotime("+7 days")); ?></td>
                        </tr>
                        <tr>
                        <td style="color:#ffffff; width: 4%; background-color:#902bee; border: 1px solid #000;">D5</td><td style="width:44%; border: 1px solid #000;"><?php echo date($SITE['dateLongFormat'], strtotime("+4 days")); ?> - <?php echo date($SITE['dateLongFormat'], strtotime("+5 days")); ?></td>
                        <td style="color:#ffffff; width: 4%; background-color:#8a4e26; border: 1px solid #000;">D8</td><td style="width:48%; border: 1px solid #000;"><?php echo date($SITE['dateLongFormat'], strtotime("+7 days")); ?> - <?php echo date($SITE['dateLongFormat'], strtotime("+8 days")); ?></td>
                        </tr>
                        <tr>
                        <td style="color:#ffffff; width: 4%; background-color:#008a00; border: 1px solid #000;">D6</td><td style="width:44%; border: 1px solid #000;"><?php echo date($SITE['dateLongFormat'], strtotime("+5 days")); ?> - <?php echo date($SITE['dateLongFormat'], strtotime("+6 days")); ?></td>
                        <td colspan="2" style="border: 1px solid #000;">(All days are valid from 12 UTC - 12 UTC)</td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <div style="text-align: left; ">
        <b>PREDICTABILITY TOO LOW</b> is used to indicate severe storms may be
  possible based on some model scenarios.
  However, the location or occurrence of severe storms are in doubt
  due to:<br /> 1) large differences in the deterministic model solutions,<br />
  2) large spread in the ensemble guidance, and/or<br />
  3) minimal run-to-run continuity.
<br />
<br />  <b>POTENTIAL TOO LOW</b> means the threat for a regional area of
  organized severe storms appears highly unlikely during the entire
  period (e.g. less than a 30% probability for a regional severe
  storm area across the CONUS through the entire Day 4-8 period).
        </div>
        <pre style="text-align: left; width: 80%; margin: 0 auto;">
        <br />
<?php           $nws = file_get_contents("http://www.srh.noaa.gov/data/WNS/SWOD48");
                print $nws;
?>
        </pre>        
     </td>
     <td style="vertical-align: top;">
        <a href="index.php?p=<?php echo $p.'&amp;day=1&amp;lang='.$lang.'#data-area"';?>>
        <img src="http://www.spc.noaa.gov/products/outlook/day1otlk.gif" alt="" style="width: 100%;"/></a>

        <p style="text-align:center; font-size:14px; font-weight:bold;">Day 2</p>
        <a href="index.php?p=<?php echo $p.'&amp;day=2&amp;lang='.$lang.'#data-area"';?>>
        <img src="http://www.spc.noaa.gov/products/outlook/day2otlk.gif" alt="" style="width: 100%;"/></a>

        <p style="text-align:center; font-size:14px; font-weight:bold;">Day 3</p>
        <a href="index.php?p=<?php echo $p.'&amp;day=3&amp;lang='.$lang.'#data-area"';?>>
        <img src="http://www.spc.noaa.gov/products/outlook/day3otlk.gif" alt="" style="width: 100%;"/></a>
     </td>
  </tr>
</table>
