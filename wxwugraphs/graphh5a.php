<?php 
include('WUG-inc-hour.php');
echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset='.$WUGcharset.'">
		<title>'.$BaroTran .' '.$Tplast.' '.$ghtitle.' - '.$gSubtitle.'</title>
		<script type="text/javascript" src="'.$jQueryFile.'"></script>
		<script type="text/javascript" src="'.$jsPath.'highcharts.js"></script>
		<script type="text/javascript" src="'.$jsPath.'exporting.js"></script>
		<!--[if IE]>
			<script type="text/javascript" src="'.$jsPath.'excanvas.compiled.js"></script>
		<![endif]-->
';
?>
		<script type="text/javascript">
<?php echo $JSdata; ?>			
    // block errors for flat line (no data)
    function stopError() {
    return true;
    }
    window.onerror = stopError;
 		
		$(document).ready(function() {
<?php echo $langChart; ?>	
      var contHeight = $('#container').height(); // for dynamic labels position 
      var chart = new Highcharts.Chart({
			   chart: {
			      renderTo: 'container',
			      defaultSeriesType: '<?php echo $spline ?>',
			      zoomType: 'x'
			   },
<?php 
echo $hchExport;
echo '			   title: {
			      text: "'.$BaroTran.' '.$Tplast.' '.$ghtitle.'"
			   },
			   credits: {
			      enabled: '.$creditsEnabled.',
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
			      startOnTick: false,
			      maxPadding: 0.006,
			      maxZoom: <?php echo $maxZoomDay; ?>*1000
			   },
			   yAxis: {
			      title: {
			         text: '<?php echo $BaroTran.' ( '.$TbaroUnits.' )'; ?>'
			      }
<?php
$bHigh = '1040';
$bLow = '980';
$roundProblem = '';
if (!$metric) {
  $bHigh = round($bHigh/33.86, 2);
  $bLow = round($bLow/33.86, 2);
  $bcon = '/33.86';
  $roundProblem = '
            ,
            startOnTick: false,
            endOnTick: false
  ';
}
echo $roundProblem;
if ($baroMinMax) {
echo '
            ,
            max: '.$bHigh.',
            min: '.$bLow.',		      
            minorGridLineWidth: 0, 
			      gridLineWidth: 0,
			      alternateGridColor: null,
			      plotBands: [{ // Very low
			         from: Math.round(980'.$bcon.'*100)/100,
			         to: Math.round(990'.$bcon.'*100)/100,
			         color: "rgba(68, 170, 213, .1)"
			      }, { // low
			         from: Math.round(990'.$bcon.'*100)/100,
			         to: Math.round(1002'.$bcon.'*100)/100,
			         color: "rgba(68, 170, 213, .3)"
			      }, { // moderate
			         from: Math.round(1002'.$bcon.'*100)/100,
			         to: Math.round(1017'.$bcon.'*100)/100,
			         color: "rgba(68, 170, 213, .5)"
			      }, { // high
			         from: Math.round(1017'.$bcon.'*100)/100,
			         to: Math.round(1030'.$bcon.'*100)/100,
			         color: "rgba(68, 170, 213, .7)"
			      },{ // very high
			         from: Math.round(1030'.$bcon.'*100)/100,
			         to: Math.round(1040'.$bcon.'*100)/100,
			         color: "rgba(68, 170, 213, .9)"
			      }]
';
}
?>
			   },
<?php
if ($baroMinMax) {
echo '
			   labels: {
			      items: [{
			         html: "'.$TveryLow.'",
			         style: {
			            left: "10px",
			            // for dynamic items text positioning
                  top: contHeight/1.72*Math.sqrt(contHeight/350)
			         }      
			      }, {
			         html: "'.$Tlow.'",
			         style: {
			            left: "10px",
			            top: contHeight/2.15*Math.sqrt(contHeight/350)
			         }      
			      }, {
			         html: "'.$Tmod.'",
			         style: {
			            left: "10px",
			            top: contHeight/3.2*Math.sqrt(contHeight/350)
			         }      
			      }, {
			         html: "'.$Thigh.'",
			         style: {
			            left: "10px",
			            top: contHeight/6.3*Math.sqrt(contHeight/350)
			         }      
			      }, {
			         html: "'.$TveryHigh.'",
			         style: {
			            left: "10px",
			            top: contHeight/30*Math.sqrt(contHeight/350)
			         }      
			      }]
			   },
';
}
?>
			   tooltip: {
			      formatter: function() {
			                return '<b>'+ this.series.name +'<\/b><br\/><span style="font-size:12pt;">'+ this.y +'<?php echo $TbaroUnits; ?><\/span>'+'<br\/>['+Highcharts.dateFormat('<?php echo $hourFormText[$hourFormat]; ?>', this.x)+']';
			      }
			   },
         colors: [ '#FF7700', '#4572A7', '#AA4643', '#89A54E', '#80699B',  '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92' ],
			   plotOptions: {
			      <?php echo $spline ?>: {
			         lineWidth: 3,
			         marker: {
			             enabled: false,
    			         states: {
    			            hover: {
			                  enabled: true,
			                  symbol: 'circle',
			                  radius: 6,
			                  lineWidth: 1
    			            }
    			         }
			         }
			         //pointInterval: 300000, // 5 minutes	         
			      }
			   },
			   series: [{
			      name: '<?php echo $BaroTran; ?>',
			      data: comArr(dBaro)
			
			   }]
			});	
		});
		</script>
		
		
		<!-- 3. Add the container -->
<?php include('WUG-form.php')?>
