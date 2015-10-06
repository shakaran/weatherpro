<?php #	ini_set('display_errors', 'On');  error_reporting(E_ALL);	
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
$pageName	= 'incCwopstats.php';
$pageVersion	= '3.20 2015-08-02';
#-------------------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
include         'cwopstats.php';
$folder         = "./cwop/";
$cwopPage       = $p;
$OK             = '<img src="'.$folder.'images/check.png" style="border; none; width:17px; height: 17px;" alt= "OK"  />';
$notOK          = '<img src="'.$folder.'images/redx.png"  style="border; none; width:17px; height: 17px;" alt= "not OK"  />';
?>
<script type="text/javascript" src="javaScripts/tabber.js"></script>
<div class="blockDiv">
<h3 class="blockHead">Data Quality for <?php echo "$cityname"; ?> (CWOP ID - <?php echo "$cwop"; ?> )</h3>	 
<div style="width: 642px; margin: 0 auto;">
<table style="margin-top 10px; width: 100%; cellspacing: 0; cellpadding: 1px;">
  <tr>
    <td style="width: 62px;"><img src="<?php echo $folder; ?>images/cwoplogo4848.png" alt="cwop" style="width: 48px; text-align: left;"/></td>
    <td style="width: 380px;">
        <form method="get">
                <select name="span" onchange="this.form.submit()">
                        <option value=''> - Choose Time Span - </option>
                        <option value='3d'>3 Days</option>
                        <option value='7d'>7 Days</option>
                        <option value='14d'>14 Days</option>
                        <option value='4w'>4 Weeks</option>
                        <option value='8w'>8 Weeks</option>
                        <option value='13w'>13 Weeks</option>
                        <option value='26w'>26 Weeks</option>
                        <option value='39w'>39 Weeks</option>
                        <option value='52w'>52 Weeks</option>
                </select>        
                <input type="hidden" id="p" name="p" value="<?php echo $cwopPage; ?>" />
        </form>
        <span style="font-size: 80%; color: black; ">Current Time Span Selected: <?php echo "$span"; ?></span>
   </td>
  </tr>
</table>
<br />

<div class="tabber" style="width:650px; margin: 10px auto;">
<div class="tabbertab " style="padding: 0;">
<h3><?php langtrans('Barometer') ?></h3>
<table style="border: 1px solid;">
  <tr class="row-dark">
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;">Madis Value:</td>
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;"><?php echo "$qcbaro"; ?>%&nbsp;
      <?php if ($qcbaro >= "90") { echo $OK; } else { echo $notOK; } ?></td>
  </tr>
  <tr class="row-dark">
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;">Data Span:</td>
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;"><?php echo "$span"; ?></td>
  </tr>
  <tr class="row-dark">
    <td style="width: 175px; text-align: left; border: 1px solid; color: black; font-weight:bold;">Average Barometer Error:</td>
    <td style="width: 450px; text-align: left; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avbaroerr"; ?></td>
  </tr>
  <tr class="row-dark">
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;">Error Standard Deviation:</td>
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sdbaroerr"; ?></td>
  </tr>
  <tr class="row-white">
    <td colspan="2" style="border: 1px solid; color: black;"><img style="width: 636px; border: none;" src="<?php echo $chartbaro; ?>" alt="Baro Chart" title="Baro Chart" /></td>
  </tr>
</table>
</div>
<div class="tabbertab " style="padding: 0;">
<h3><?php langtrans('Temperature') ?></h3>
<table style="border: 1px solid;">
  <tr class="row-dark">
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;">Madis Value:</td>
    <td colspan="3" style="text-align: left; border: 1px solid; color: black; font-weight:bold;"><?php echo "$qctemp"; ?>%&nbsp;
    <?php if ($qctemp >= "90") { echo $OK; } else { echo $notOK; } ?></td>
  </tr>
  <tr class="row-dark">
    <td style="text-align:   left; border: 1px solid; color: black; font-weight:bold;">Data Span:  <?php echo "$span"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;">24 Hours</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;">Daytime</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;">Nighttime</td>
  </tr>
  <tr class="row-dark">
    <td style="text-align:   left; border: 1px solid; color: black; font-weight:bold; width: 175px;">Average Temp. Error:</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avtemperr24"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avtemperrday"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avtemperrnite"; ?></td>
  </tr>
  <tr class="row-dark">
    <td style="text-align:   left; border: 1px solid; color: black; font-weight:bold;">Error Standard Deviation:</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sdtemperr24"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sdtemperrday"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sdtemperrnite"; ?></td>
  </tr>
  <tr class="row-white">
    <td colspan="4" style="border: 1px solid; color: black;"><img style="width: 636px; border: none;" src="<?php echo $charttemp; ?>" alt="Baro Chart" title="Baro Chart" /></td>
  </tr>
</table>
</div>
<div class="tabbertab " style="padding: 0;">
<h3><?php langtrans('Dewpoint') ?></h3>
<table style="border: 1px solid;">
  <tr class="row-dark">
    <td style="text-align: left; border: 1px solid; color: black; font-weight:bold;">Madis Value:</td>
    <td colspan="3" style="text-align: left; border: 1px solid; color: black; font-weight:bold;"><?php echo "$qcdewp"; ?>%&nbsp;
    <?php if ($qcdewp >= "90") { echo $OK; } else { echo $notOK; } ?></td>
  </tr>
  <tr class="row-dark">
    <td style="text-align:   left; border: 1px solid; color: black; font-weight:bold;">Data Span:  <?php echo "$span"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;">24 Hours</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;">Daytime</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;">Nighttime</td>
  </tr>
  <tr class="row-dark">
    <td style="text-align:   left; border: 1px solid; color: black; font-weight:bold; width: 175px;">Average Dewpoint Error:</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avdewerr24"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avdewerrday"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$avdewerrnite"; ?></td>
  </tr>
  <tr class="row-dark">
    <td style="text-align:   left; border: 1px solid; color: black; font-weight:bold;">Error Standard Deviation:</td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sddewerr24"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sddewerrday"; ?></td>
    <td style="text-align: center; border: 1px solid; color: black; font-weight:bold;"><?php echo "$sddewerrnite"; ?></td>
  </tr>
  <tr class="row-white">
    <td colspan="4" style="border: 1px solid; color: black;"><img style="width: 636px; border: none;" src="<?php echo $chartdew; ?>" alt="Dew Chart" title="Dew Chart" /></td>
  </tr>
</table>
</div>
<div class="tabbertab " style="padding: 0;">
<h3><?php langtrans('Wind') ?></h3>
<table style="border: 1px solid;">
  <tr class="row-dark">
    <td style="width: 100px; text-align: left; border: 1px solid; color: black; font-weight:bold;">Madis Value:</td>
    <td style="width: 50px;  text-align: left; border: 1px solid; color: black; font-weight:bold;">&nbsp;<?php echo "$qcwind"; ?>% &nbsp;</td>
    <td style="              text-align: left; border: 1px solid;"><?php if ($qcwind >= "90") { echo $OK; } else { echo $notOK; } ?></td>
  </tr>
  <tr class="row-white">
    <td colspan="3" style="border: 1px solid; color: black;"><img style="width: 636px; border: none;" src="http://weather.gladstonefamily.net/cgi-bin/wxqchartwind.pl?site=<?php echo "$cwop"; ?>&amp;start=-7&amp;days=7" alt="Wind Vector" title="Wind Vector" /></td>
  </tr>
</table>
</div>

<div class="tabbertab " style="padding: 0; ">
<h3><?php langtrans('More information') ?></h3>
<?php
echo '
<div style="width: 636px; border: 1px solid;">
<p style="margin: 10px;">
<span style="color: black; font-weight:bold;">'.$sitename.'</span> is a proud member of the <a href="http://www.wxqa.com">Citizen Weather Observer Program</a> (CWOP).
<br /> The above charts represent data reported to CWOP for 
'.$cityname.' ('.$cwop.' actuals in <b style="color: #0000FF;">blue</b>) with the predicted data, based on surrounding stations ('.$cwop.' Analysis in <b style="color: #FF0000;">red</b>).
</p>
<p style="margin: 10px;">
<span style="color: black; font-weight:bold;">Data Quality:</span>
<br />
The <a href="http://madis.noaa.gov/">MADIS</a> value represents the percentage of observations that have successfully passed the MADIS QC checks.  
If the Madis rating is within the acceptable limits, a green check <img src="cwop/images/check.png" style="border: none; width: 13px; height: 13px;" alt="OK" /> will appear.  
Otherwise, a red x-mark <img src="cwop/images/redx.png" style="border: none; width: 13px; height: 13px;" alt="not OK" /> will appear indicating that the data has not passed quality control.
<br /><br />
<span style="color: black; font-weight:bold;">Errors:</span>
<br />  
If the above errors are POSITIVE, this means that the analysis variable is HIGHER than the reported variable. 
This means that the sensor is reading a variable lower than expected.  
If the above errors are NEGATIVE, this means that the analysis variable is LOWER than the reported variable. 
This means that the sensor is reading a variable higher than expected.
</p>
<p style="margin: 10px;">
<span style="color: black; font-weight:bold;">CWOP:</span>
<br />
The Citizen Weather Observer Program (CWOP) is a private-public partnership with the National Oceanic &amp; Atmospheric Administration. 
Its three main goals: 
</p>
<ol>
<li>to collect weather data contributed by citizens;</li>
<li>to make these data available for weather services; and</li> 
<li>to provide feedback to the data contributors so that they have the tools to check and improve their data quality.</li>
</ol>
<p style="margin: 10px;">Many thanks go to Phillip Gladstone at CWOP for his dedication to providing the needed accuracy and quality checks for the amateur weather observer.
</p>
</div>';
?>
</div>
</div>
</div> <!-- end tabber-div -->

<h3 class="blockHead">
	<span style=""><?php langtrans('Calculations/Data courtesy of') ?>
	 <a href='http://weather.gladstonefamily.net/'> Phillip Gladstone</a>. 
	 <?php langtrans('Script courtesy of') ?> &nbsp;Michael Holden 
	 <?php langtrans('of') ?> 
	 <a href='http://www.relayweather.com/'>Relay Weather</a>.</span>
<br />
   	<?php langtrans('Adapted for the template by'); ?>&nbsp;
  	<a href="http://leuven-template.eu/" target="_blank">Wim van der kuil</a>
</h3>
</div>
<!-- end block-div -->
<?php
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
