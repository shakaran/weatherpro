<?php 
include_once('WUG-inc-month.php');	
echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset='.$WUGcharset.'">
		<title>'.$PrecTran.' '.$mnthNameYear.' - '.$gSubtitle.'</title>	
		<script type="text/javascript" src="'.$jQueryFile.'"></script>
		<script type="text/javascript" src="'.$jsPath.'highcharts.js"></script>
    <script type="text/javascript" src="'.$jsPath.'exporting.js"></script>
		<!--[if IE]>
			<script type="text/javascript" src="'.$jsPath.'excanvas.compiled.js"></script>
		<![endif]-->
';
if (!function_exists('mb_strlen')) {
  function mb_strlen ($string) {
    return strlen($string);
  }
}

// Text for Precipitation Total
if (mb_strlen($PrecTran, 'UTF-8') > 6 && !$no_mb) {
  $sumRainText = mb_substr($PrecTran,0,6,'UTF-8').'. '.$Ttotal;
} else {
  $sumRainText = $PrecTran.' '.$Ttotal;
}
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
      var chart = new Highcharts.Chart({
			   chart: {
			      renderTo: 'container',
			      defaultSeriesType: 'column',
			      margin: [ 50, 70, 60, 80]
			   },
<?php 
echo $hchExport;
echo '			   title: {
			      text: "'.$PrecTran.' '.$TperMonth. $mnthNameYear.'"
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
            //categories: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'], 
            tickInterval: 1, // instead categories use tickinterval (problem in IExplorer)
            labels: { 
               align: 'center',
               style: {
                   font: 'normal 13px Verdana, sans-serif'
               }
            }
         },
			   yAxis: [{
			      min: 0,
			      title: {
			         text: '<?php echo $PrecTran.' ( '.$TsizeUnits.' )'; ?>'
			      }
			   },{
			      opposite: true,
            min: 0,
            tickWidth: 1,
			      title: {
			         text: '<?php echo $sumRainText.' ( '.$TsizeUnits.' )'; ?>'
			      }
         }],
			   legend: {
			      enabled: true
			   },
			   tooltip: {
			     formatter: function() {
			        var sumtext = '<b>'+ this.x + '.' + '<?php echo $mnthSmall[$month-1]; ?>' +'<\/b><br\/>';
			        if((this.series.name.search(/<?php echo $sumRainText; ?>/)) != -1) { 
                var sumtext = '<?php echo $sumRainText.'<br\/><b>'; ?>'+precipS+'-'+ this.x + '.' + '<?php echo $mnthSmall[$month-1]; ?>' +'<\/b><br\/>'; 
              }
			        return sumtext +'<span style="font-size: 11pt;">' + this.y + ' <?php echo $TsizeUnits; ?>' + '<\/span>';
			      }
			   },
			   series: [{
			      name: '<?php echo $PrecTran; ?>',
			      data: precipC,
            //pointPadding: 0.1,
            //groupPadding: 0.05,
			      dataLabels: {
			         enabled: false,
			         rotation: -90,
			         color: '#FFFFFF',
			         align: 'right',
			         x: 15,
			         y: 10,
			         formatter: function() {
			            return this.y;
			         },
			         style: {
			            font: 'normal 13px Verdana, sans-serif'
			         }
			      }         
			   },{
			      name: '<?php echo $sumRainText; ?>',
			      type: 'line',
			      lineWidth: 1,
			      color: 'gray',
			      marker: {radius: 3},
			      yAxis: 1,  
			      data: precipT
			   }]
			});
		});
		</script>
		

		<!-- 3. Add the container -->
<?php include_once('WUG-form.php')?>