<?php 
include_once('WUG-inc-month.php');	
echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>'.$TempTran.' '.$mnthNameYear.' - '.$gSubtitle.'</title>
		<script type="text/javascript" src="'.$jQueryFile.'"></script>
		<script type="text/javascript" src="'.$jsPath.'highcharts.js"></script>
		<!--[if IE]>
			<script type="text/javascript" src="'.$jsPath.'excanvas.compiled.js"></script>
		<![endif]-->
';
?>
<!--WU DATA-->
		<script type="text/javascript">
<?
/*
// Rain units conversion 
$rainMultip = ( $metric ? 10 : 1 );
// CREATE ARRAYS 
$handle = fopen($WUcacheFile, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//skip first two rows
if($henk++ < 2) { continue; }
//day
$datu = substr($data[0], -2, 2);
if ($datu < 0) {
$datu = abs($datu);
}

//month
$datu2 = substr($data[0], 5, 2);
if (substr($datu2, -1) == "-") {
$datu2 = substr($datu2, 0, 1);
}

//year
$datu3 = substr($data[0], 0, 4);

// final date in js timestamp format (in miliseconds)
$msdate = strtotime($datu.'-'.$datu2.'-'.$datu3) * 1000;

    // skip <br> and other unnecessary rows
    if(!preg_match('/[0-9]+/', $datu)) {
      
     continue;
     
    }      
      // Add to string
      $timeArray.= $msdate   .",";
      $maxTemp  .= $data[1]  .",";
      $avgTemp  .= $data[2]  .",";
      $minTemp  .= $data[3]  .",";
      $maxDP    .= $data[4]  .",";
      $avgDP    .= $data[5]  .",";
      $minDP    .= $data[6]  .",";
      $maxHum   .= $data[7]  .",";
      $avgHum   .= $data[8]  .",";
      $minHum   .= $data[9]  .",";
      $maxBaro  .= $data[10] .",";
      $minBaro  .= $data[11] .",";
      $maxWS    .= $data[12] .",";
      $avgWS    .= $data[13] .",";
      $gustWS   .= $data[14] .",";   
      $rainT    .= ($data[15]*$rainMultip) .",";
      
      // solve Min/Max Highcharts yAxis affection bug/problem
      // Temp axis
      // !!! Does not work ... solves nothing ...  :-(
      if (!isset($tempMaxLimit) or $data[1] > $tempMaxLimit) {$tempMaxLimit = $data[1];}
      if (!isset($tempMinLimit) or $data[3] < $tempMinLimit) {$tempMinLimit = $data[3];}
      if (!isset($tempMaxLimit) or $data[4] > $tempMaxLimit) {$tempMaxLimit = $data[4];}
      if (!isset($tempMinLimit) or $data[6] < $tempMinLimit) {$tempMinLimit = $data[6];}
      // Wind axis
      if (!isset($windMaxLimit) or $data[14] > $windMaxLimit) {$windMaxLimit = $data[14];}
      if (!isset($windMinLimit) or $data[13] < $windMinLimit) {$windMinLimit = $data[13];}      
}
if (empty($maxTemp) and date('n')<=$month) {$emptyGraph = true;}

//REPAIR STRINGS
$timeArray  = substr($timeArray,  0, -1);
$maxTemp    = substr($maxTemp,    0, -1);
$avgTemp    = substr($avgTemp,    0, -1);
$minTemp    = substr($minTemp,    0, -1);
$maxDP      = substr($maxDP,      0, -1);
$avgDP      = substr($avgDP,      0, -1);
$minDP      = substr($minDP,      0, -1);
$maxHum     = substr($maxHum,     0, -1);
$avgHum     = substr($avgHum,     0, -1);
$minHum     = substr($minHum,     0, -1);
$maxBaro    = substr($maxBaro,    0, -1);
$minBaro    = substr($minBaro,    0, -1);
$maxWS      = substr($maxWS,      0, -1);
$avgWS      = substr($avgWS,      0, -1);
$gustWS     = substr($gustWS,     0, -1);
$rainT      = substr($rainT,      0, -1);

// STRINGS TO JS ARRAYS
echo
'
var maxTemp = ['.$maxTemp .'];
var avgTemp = ['.$avgTemp .'];
var minTemp = ['.$minTemp .'];
var maxDP   = ['.$maxDP   .'];
var avgDP   = ['.$avgDP   .'];
var minDP   = ['.$minDP   .'];
var maxHum  = ['.$maxHum  .'];
var avgHum  = ['.$avgHum  .'];
var minHum  = ['.$minHum  .'];
var maxBaro = ['.$maxBaro .'];
var minBaro = ['.$minBaro .'];
var maxWS   = ['.$maxWS   .'];
var avgWS   = ['.$avgWS   .'];
var gustWS  = ['.$gustWS  .'];
var rainT   = ['.$rainT   .'];

// Function for creating graph array
function comArr(unitsArray) {
    var timeArray = ['.$timeArray.'];  
    var outarr = [];
    for (var i = 0; i < timeArray.length; i++) {
     outarr[i] = [timeArray[i], unitsArray[i]];
    }
  return outarr;
} 

';

fclose($handle);
*/
echo $JSdata;
?>
		</script>
		
<!-- HIGHCHARTS -->		
		<script type="text/javascript">		
		// block errors for flat line (no data)
    function stopError() {
    return true;
    }
    window.onerror = stopError;
		
		$(document).ready(function() {
      			var chart = new Highcharts.Chart({
			   chart: {
			      renderTo: 'container',
			      zoomType: 'x',
			      margin: [50, 120, 90, 120]  // b 70
			   },
<?php 
echo '			   title: {
			      text: "'. $TempTran.$TperMonth . $mnthNameYear .'"
			   },
			   credits: {
			      text: "'.$credits.'",
			      href: "'.$creditsURL.'"
			   },
			   subtitle: {
			      text: "'.$gSubtitle.'"
			   },
';
?>
			   xAxis: {
             type: 'datetime',
             maxZoom: <?php echo $maxZoomMonth; ?> * 24 * 3600000,
         },

			   yAxis: [
          { // TEMP AXIS
			      title: {
			         text: '<?php echo $TempTran.' ( '.$TtempUnits.' )' ;?>'
			      },
            max: <? echo $tempMaxLimit; ?>+2, // dynamic value + 2
            min: <? echo $tempMinLimit; ?>-2, // dynamic value - 2
            labels: { formatter: function() { return this.value +'<?php echo $TtempUnits; ?>' } }
			    },{ // HUMIDITY AXIS
			        title: {
			           text: '<?php echo $HumTran.' '.$Tpercents; ?>'
			        },
			        //opposite: true,
              offset: 60,
			        //margin: 40,
			        tickWidth: 1,
			        lineWidth: 1,
              min: 15, 
              max: 100,
              labels: { formatter: function() { return this.value +'%' } }
			    },{ // BARO AXIS
			        title: {
			           text: '<?php echo $BaroTran.' ( '.$TbaroUnits.' )'; ?>'
			      },
			        opposite: true,
<?php
$bHigh = '1040';
$bLow = '980';
if (!$metric) {
$bHigh /= 3.386;
$bLow /= 3.386;
$bcon = '/3.386'; // test ... baro convert
}
echo '
              max: '.$bHigh.',
              min: '.$bLow.',      
              minorGridLineWidth: 0, 
			        gridLineWidth: 0,
			        alternateGridColor: null,
			/*      plotBands: [{ // Very low
			         from: 980'.$bcon.',
			         to: 990'.$bcon.',
			         color: "rgba(68, 170, 213, .1)"
			      }, { // low
			         from: 990'.$bcon.',
			         to: 1002'.$bcon.',
			         color: "rgba(68, 170, 213, .3)"
			      }, { // moderate
			         from: 1002'.$bcon.',
			         to: 1017'.$bcon.',
			         color: "rgba(68, 170, 213, .5)"
			      }, { // high
			         from: 1017'.$bcon.',
			         to: 1030'.$bcon.',
			         color: "rgba(68, 170, 213, .7)"
			      },{ // very high
			         from: 1030'.$bcon.',
			         to: 1040'.$bcon.',
			         color: "rgba(68, 170, 213, .9)"
			      }], */
';
?>
			    },{ // WIND SPEED AXIS			      
              title: {
                enabled: true,
			          text: '<?php echo $WSTran.' ( '.$TwindUnits.' )'; ?>'
			        },
              offset: 60,
              opposite: true,
              max:<? echo $windMaxLimit; ?>+3, // dynamic value
              min:<? echo $windMinLimit; ?>-3, // dynamic value
              labels: { formatter: function() { return this.value +''; } }
         }/*,{ // RAIN AXIS
			      title: {
			         text: '<?php echo $PrecTran.' ( '.$TsizeUnits.' )'; ?>'
			      },
			      min: 0,
			      max: 200,
			      offset: 120 // je treba upravit margin: [50, 120, 90, 1xx] kde xx je treba otestovat
			   }*/   
        ],

			   tooltip: {
  			     /* formatter: function() {
  			      var tdx = new Date(this.x);
              tdx = tdx.getDay()+'. '+tdx.getMonth()+'. '+tdx.getFullYear(); 
  			                return '<b>'+ this.series.name +'</b><br/><span style="font-size:12pt;">'+ this.y +'<?php echo $TtempUnits; ?></span>'+'<br />'+ Highcharts.dateFormat('%d. %m. %Y', this.x);
  			      } */
  			      formatter: function() {
  			      //var tdx = new Date(this.x);
              //tdx = tdx.getDay()+'. '+tdx.getMonth()+'. '+tdx.getFullYear(); 
              var units = "<?php echo $TtempUnits; ?>";
              if ((this.series.name.search(/<?php echo strtolower($HumTran); ?>/)) > 0) { var units = "%"; }
              if ((this.series.name.search(/<?php echo strtolower($BaroTran); ?>/)) > 0) { var units = "<?php echo $TbaroUnits; ?>"; }
              if ((this.series.name.search(/<?php echo strtolower($WSTran); ?>/)) > 0) { var units = "<?php echo $TwindUnits; ?>"; }  
              if ((this.series.name.search(/<?php echo strtolower($WSGust); ?>/)) > 0) { var units = "<?php echo $TwindUnits; ?>"; } 
                return '<b>'+ this.series.name +'</b><br/><span style="font-size:12pt;">'+ this.y + units +'</span>'+'<br />'+ Highcharts.dateFormat('%d. %m. %Y', this.x);
              }  			      
			     },
         colors: [ '#AA4643', '#89A54E', '#4572A7','#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92' ],

			   plotOptions: {
			      spline: {
			         lineWidth: 4,
			         marker: {
			            enabled: false
			         },
			         //pointInterval: 86400000, // one day
			         
			         states: {
			            hover: {
			               marker: {
			                  enabled: true,
			                  symbol: 'circle',
			                  radius: 3,
			                  lineWidth: 1
			               }
			            }
			         }
			      }
			   },
			   series: [
{
// MAX TEMP
name: '<?php echo $Tmax.strtolower($TempTran); ?>',
type: 'spline',
visible: false,
data: comArr(maxTemp)
},{
// AVERAGE TEMP
name: '<?php echo $Tavg.strtolower($TempTran); ?>',
type: 'spline',
visible: true,
data: comArr(avgTemp)
},{
// MIN. TEMP
name: '<?php echo $Tmin.strtolower($TempTran); ?>',
type: 'spline',
visible: false,
data: comArr(minTemp) 
},{         
// MAX DEW POINT
name: '<?php echo $Tmax.strtolower($DPTran); ?>',
type: 'spline',
visible: false,
data: comArr(maxDP) 
},{
// AVERAGE DP
name: '<?php echo $Tavg2.strtolower($DPTran); ?>',
type: 'spline',
visible: false,
data: comArr(avgDP)
},{
// MIN DP
name: '<?php echo $Tmin.strtolower($DPTran); ?>',
type: 'spline',
visible: false,
data: comArr(minDP)
},{
// MAX. HUMI
name: '<?php echo $Tmax.strtolower($HumTran); ?>',
type: 'spline',
visible: false,
yAxis: 1,
data: comArr(maxHum)
},{
// AVG. HUMI
name: '<?php echo $Tavg.strtolower($HumTran); ?>',
type: 'spline',
visible: false,
yAxis: 1,
data: comArr(avgHum)
},{
// MIN. HUMI
name: '<?php echo $Tmin.strtolower($HumTran); ?>',
type: 'spline',
visible: false,
yAxis: 1,
data: comArr(minHum)
},{
// MAX. BARO 
name: '<?php echo $Tmax.strtolower($BaroTran); ?>',
type: 'spline',
visible: false,
yAxis: 2,
data: comArr(maxBaro)
},{
// MIN BARO
name: '<?php echo $Tmin.strtolower($BaroTran); ?>',
type: 'spline',
visible: false,
yAxis: 2,
data: comArr(minBaro)
},{
// MAX WIND
name: '<?php echo $Tmax.strtolower($WSTran); ?>',
type: 'spline',
visible: false,
yAxis: 3,
data: comArr(maxWS)
},{
// AVG WIND
name: '<?php echo $Tavg.strtolower($WSTran); ?>',
type: 'spline',
visible: false,
yAxis: 3,
data: comArr(avgWS)
},{
// GUST WIND
name: '<?php echo $WSGust; ?>', 
type: 'spline',
visible: false,
yAxis: 3,
data: comArr(gustWS)
}/*,{
// RAIN
name: '<?php echo $PrecTran; ?>',
visible: false,
yAxis: 4,
data: precipT
}*/
]       
			});
<?php echo $langChart; ?>	
		});
		</script>

<?php include_once('WUG-form.php');?>