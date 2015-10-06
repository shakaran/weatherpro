<?php 
include('WUG-inc-hour.php');
echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset='.$WUGcharset.'">
		<title>'.$PrecTran .' '.$dayDateText[$ddFormat].' - '.$gSubtitle.'</title>
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
    //block errors for flat line (no data)
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
			      zoomType: 'x'
			   },
<?php 
echo $hchExport;
echo '			   title: {
			      text: "'.$PrecTran.' '.$Tplast.' '.$ghtitle.'"
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
enabled: true,
			         text: '<?php echo $PrecTran.' ( '.$TsizeUnits.' )'; ?>'
			      },
            min: 0,
            labels: {
              formatter: function() {
                return this.value +'';
              }
            }
			   },
			   tooltip: {
			      formatter: function() {
			                return '<b>'+ this.series.name +'<\/b><br\/><span style="font-size:12pt;">'+ this.y +'<?php echo $TsizeUnits; ?><\/span>'+'<br\/>['+Highcharts.dateFormat('<?php echo $hourFormText[$hourFormat]; ?>', this.x)+']'
;
			      }
			   },
        colors: [ '#00695F' , '#00FFE6',  '#3D96AE', '#4572A7', '#AA4643', '#80699B',  '#DB843D', '#92A8CD', '#A47D7C' ],
			   plotOptions: {
			      <?php echo $aspline ?>: {
			         pointInterval: 300000, // 5 minutes
			         marker: {
			           enabled: false,
  			         states: {
  			            hover: {
                        enabled: true,
                        symbol: 'circle',
                        radius: 5
  			            }
  			         }
			         },
               lineWidth: 3
			      }
			   },
			   series: [{
            name: '<?php echo $PrecTran.' '.$Ttotal; ?>', 
            data: comArr(dRainT)
            }]
			});	
		});
		</script>
		
		<!-- 3. Add the container -->
<?php include('WUG-form.php')?>