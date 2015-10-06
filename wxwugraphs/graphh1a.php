<?php 
include('WUG-inc-hour.php');
echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
    <!-- compat. mode for highcharts bug in IE8 + WinXP + Highcharts 2.0 -->
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
		<meta http-equiv="Content-Type" content="text/html; charset='.$WUGcharset.'">
		<title>'.$TempTran .' '.$Tplast.' '.$ghtitle.' - '.$gSubtitle.'</title>

		<script type="text/javascript" src="'.$jQueryFile.'"></script>
		<script type="text/javascript" src="'.$jsPath.'highcharts.js"></script>
    <script type="text/javascript" src="'.$jsPath.'exporting.js"></script>
		<!--[if IE]>
			<script type="text/javascript" src="'.$jsPath.'excanvas.compiled.js"></script>
		<![endif]-->
';
?>		
		
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<script type="text/javascript">
<?php
echo $JSdata;
?>		
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
			      defaultSeriesType: '<?php echo $spline ?>',
			     // margin: [50,10,70,60],
			      margin: [50,25,70,60],  // top, right, bottom, left
			      zoomType: 'x'
			   },
<?php 
echo $hchExport;
echo '			   title: {
			      text: "'.$TempTran.' '.$Tplast.' '.$ghtitle.'"
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
			         text: '<?php echo $TempTran.' ( '.$TtempUnits.' )'; ?>'
			      },
labels: { formatter: function() { return this.value +'°' } }
			      			   },
			   tooltip: {
			      formatter: function() {
			                return '<b>'+ this.series.name +'<\/b><br\/><span style="font-size:12pt;">'+ this.y +'<?php echo $TtempUnits; ?><\/span>'+
                      '<br\/>['+Highcharts.dateFormat('<?php echo $hourFormText[$hourFormat]; ?>', this.x)+']'
;
			      }
			   },
         colors: [ '#AA4643', '#4572A7', '#DD6A00', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92' ],
			   plotOptions: {
			      <?php echo $spline ?>: {
			         lineWidth: 3,
			         marker: {
			             enabled: false,
    			         states: {
    			            hover: {
                        enabled: true,
                        symbol: 'circle',
                        radius: 6
    			            }
    			         }
			         }
			      }
			   },
  			 series: [{
  			  name: '<?php echo $TempTran; ?>',
  			  data: comArr(dTemp) 			
  			  },{
          name: '<?php echo $DPTran; ?>',
          data: comArr(dDP)          
<?php
if ($dataSource == 'mysql' and $db_i_temp) {
echo '
  			  },{
          name: "'.$Tindoor.' '.$TempTran.'",
          data: comArr(dIndTemp) 
';
}
?>
        }]

/*
   jQuery.getJSON('<?php echo dfsdfs; ?>.json', null, function(data) {
      options.series.push({
         name: '<?php echo $TempTran; ?>',
         data: data
      });
      
      chart = new Highcharts.Chart(options);
   });
*/

			});
	
		});
		</script>

<?php include('WUG-form.php')?>
