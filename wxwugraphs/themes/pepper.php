<?php
#### DARK THEME ####
/**
 *
 * If you design a new theme, please send it to my emai-l: luzar@post.cz
 * 
 */  


// Tabs and Datepicker style file
// info about creating this file is included in dark-darkness.css file
$tabsStyleFile = $mainDir.'themes/pepper-style.css'; 

// page (body element) background color
$pgBGC = '#F4F4F4';
// page (body element) font color
$wugfontColor = '#000000';


// only background custom styling support
$pgBGC = $CustomBgTheme === true ? $colorpickerBgVal : $pgBGC;
$pgBGC = $CustomBgTheme === 'transparent' ? 'transparent' : $pgBGC;

// highcharts graph styling
// more info about configurable values at: http://www.highcharts.com/ref/
// more highcharts theme inspiration (Default, Grid, Skies, Gray, Dark blue, Dark green) at: http://www.highcharts.com/demo/  
$higchartsTheme = '
/**
 * Grid theme for Highcharts JS
 * @author Torstein Hønsi
 */

Highcharts.theme = {
   //colors: ["#058DC7", "#50B432", "#ED561B", "#DDDF00", "#24CBE5", "#64E572", "#FF9655", "#FFF263", "#6AF9C4"],
   chart: {
      backgroundColor: "'.$pgBGC.'",
      //borderWidth: 2,
      plotBackgroundColor: "rgba(255, 255, 255, .9)",
      plotShadow: true,
      plotBorderWidth: 1
   },
   title: {
      style: { 
         color: "#000",
         font: \'bold 16px "Trebuchet MS", Verdana, sans-serif\'
      }
   },
   subtitle: {
      style: { 
         color: "#666666",
         font: \'bold 12px "Trebuchet MS", Verdana, sans-serif\'
      }
   },
   xAxis: {
      gridLineWidth: 1,
      lineColor: "#000",
      tickColor: "#000",
      labels: {
         style: {
            color: "#000",
            font: "11px Trebuchet MS, Verdana, sans-serif"
         }
      },
      title: {
         style: {
            color: "#333",
            fontWeight: "bold",
            fontSize: "12px",
            fontFamily: "Trebuchet MS, Verdana, sans-serif"

         }            
      }
   },
   yAxis: {
      minorTickInterval: "auto",
      lineColor: "#000",
      lineWidth: 1,
      tickWidth: 1,
      tickColor: "#000",
      labels: {
         style: {
            color: "#000",
            font: "11px Trebuchet MS, Verdana, sans-serif"
         }
      },
      title: {
         style: {
            color: "#333",
            fontWeight: "bold",
            fontSize: "12px",
            fontFamily: "Trebuchet MS, Verdana, sans-serif"
         }            
      }
   },
   legend: {
      itemStyle: {         
         font: "9pt Trebuchet MS, Verdana, sans-serif",
         color: "black"

      },
      itemHoverStyle: {
         color: "#039"
      },
      itemHiddenStyle: {
         color: "gray"
      }
   },
   labels: {
      style: {
         color: "#99b"
      }
   }
};

// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
';

?>
