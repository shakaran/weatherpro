<?php 
include('WUG-inc-hour.php');
echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset='.$WUGcharset.'">
		<title>'.$SunTran .' '.$Tplast.' '.$ghtitle.' - '.$gSubtitle.'</title>
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
// Function for creating icons array
function comArrIco(unitsArray) { 
    var outarr = [];
    for (var i = 0; i < timeArray.length; i++) {
     outarr[timeArray[i]] = unitsArray[i];
    }
  return outarr;
} 
var vico = comArrIco(dCond);
    // block errors for flat line (no data)
    function stopError() {
    return true;
    }
    window.onerror = stopError;
		
		$(document).ready(function() {
<?php echo $langChart; ?>
      var chart = new Highcharts.Chart({
			   chart: {
			      renderTo: 'container',
			      defaultSeriesType: '<?php echo $aspline ?>',
			      zoomType: 'x',
			      showAxes: true // show axes if series is not set
			   },
<?php 
echo $hchExport;
echo '			   title: {
			      text: "'.$SunTran2.' '.$Tplast.' '.$ghtitle.'"
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
			   yAxis: [{
			      title: {
enabled: true,
			         text: '<?php echo $SunTran2.' ( '.$TsunUnits.' )'; ?>'
			      },
            min: 0,
            labels: {
              formatter: function() { return this.value +''; }
            }
			  }
<?php       
if ($dataSource == 'mysql' && $db_suv) {
  echo '
  ,{ // UV
    opposite: true,
    min: 0,
    title: {
       text: "'.$TuvInd.'"
    }        
  }';
}
?>
        ],
			   tooltip: {
			      formatter: function() {
                if(this.series.name.search(/<?php echo $TuvInd; ?>/) != -1) {
                    sununits = ' ';
                    ttname = '<?php echo $TuvInd; ?>';
                } else {
                    sununits = '<?php echo $TsunUnits; ?>';
                    ttname = '<?php echo $SunTran2; ?>';
                }
			          return '<b>'+ ttname +'<\/b><br\/><span style="font-size:12pt;">'+ this.y + sununits +'<\/span>'+'<br\/>['+Highcharts.dateFormat('<?php echo $hourFormText[$hourFormat]; ?>', this.x)+']'+
                      // UNCOMPLETED - I must find some small transparent 
                      //'<br\/><img src="./images/weather_icons/'+vico[this.x]+'.png" alt="condition" \/>'
                      '<br\/>'+vico[this.x] // text alternative without icon
;
			      }
			   },
         colors: [ '#FFF700' , '#8155FF',  '#3D96AE', '#4572A7', '#AA4643', '#80699B',  '#DB843D', '#92A8CD', '#A47D7C' ],
			   plotOptions: {
			      <?php echo $aspline ?>: {
			         pointInterval: 300000, // 5 minutes
               marker: {
			           enabled: false,
  			         states: {
  			            hover: {
            			      fillColor: '#F0DD00',
                        enabled: true,
                        symbol: 'circle',
                        radius: 5
  			            }
  			         }
			         }
			      },
            <?php echo $spline ?>: {
              marker: {
                enabled: false,
                states: {
                  hover: {
                    enabled: true,
                    radius: 5
                  }
                },
                symbol: 'circle',
                radius: 3
              },
              lineWidth: 2
            }
			   },
			   series: [{
			      name: '<?php echo $SunTran; ?>',
			      yAxis: 0,
			      data: 
<?php 
if ($parseCond) {
  echo "[".substr($dSolarC,0,-1)."]";
}
else {
  echo 'comArr(dSolar)'; 
}
if ($dataSource == 'mysql' && $db_suv) {
  echo '},{
  name: "'.$TuvInd.'",
  yAxis: 1,
  type: "'.$aspline.'",
  fillColor: {
      linearGradient: [0, 0, 0, 350],
      stops: [
          [0, "rgb(129, 85, 255)"],
          [1, "rgba(2,0,0,0)"]
      ]
  },
  data: comArr(dUV)
  ';
}
?> 			
			   }]
			});	
		});
		</script>
		
		<!-- 3. Add the container -->

<?php include('WUG-form.php')?>