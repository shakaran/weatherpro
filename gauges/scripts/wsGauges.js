/*!
A script to display n number of gauges based on the wsGauge name
 */


var g_scriptVer 		= "3.11 2015-07-22";
/*!  folowing are inserted from php script
var ws_popupGraphs        	= 3;            //Set 0=no graphs 1=Cumulus, 2 = wxGraphs (WD or MH)   3 = weatherlink  
var g_realTimeURL		= "gauges/realtimeMH.php";  // name of the file or script which supplies the realtime values
var g_imgPathURL                = "upload/";    //*** Change this to the relative path for your 'Trend' graph images
var g_showPopupDataGraphs       = true; 	//If popup data is displayed, show the graphs?
var g_count                     = 30;           //download data counter (secs, default 60)
var g_size                      = 221;          //size of gauges now 221 - Odd numbers render better than even

var g_showUvGauge               = true; 	//Display the UV Index gauge
var g_showSolarGauge            = true; 	//Display the Solar gauge
var LANG = LANG_EN;         			// supplied by php script

*/

 
var g_noJavascript              = "../index.php";  //*** Change this to the relative path for your 'old' gauges page.
var g_forecast_width            = 550;          //width of the forecast display 
var g_graphUpdateTime           = 15;           //period of popup data graph refesh, in minutes (default 15)
var g_stationOfflineTimeout     = 30;           //period of no data change before we declare the station offline, in minutes (default 3)
var g_pageUpdateLimit           = 20;           //period after which the page stops automatically updating, in minutes (default 20),
                                                // - set to 0 (zero) to disable this feature
var g_pageUpdatePswd            = "its-me";     //password to over ride the page timeout
var g_digitalFont               = true; 	//Font control for the gauges & timer
var g_digitalForecast           = false; 	//Font control for the status display, set this to false for languages that use accented characters in the forecasts
var g_showPopupData             = true; 	//Popup data displayed

var g_showWindVariation         = false; 	//Show variation in wind direction over the last 10 minutes on the direction gauge
var g_solarGaugeScaleMax        = 1400; 	//Max value to be shown on the solar gauge - theoretical max without atmosphere ~ 1374 W/m≤
                                    	        // - but Davis stations read up to 1800!


// test for canvas support before we do anything else, especially reference steelseries!
var test_canvas = document.createElement('canvas');
if (!test_canvas.getContext) {
    document.body.innerHTML = LANG.canvasnosupport;
    setTimeout(function() {window.location = g_noJavascript;}, 3000);
}
var g_grad = '°';
//Gauge look'n'feel settings
var minMaxArea 		        = 'rgba(212, 132, 134, 0.3)'; //area sector for todays max/min. (red, green, blue, transparency)
var g_frameDesign	        = steelseries.FrameDesign.TILTED_GRAY;
var g_background	        = steelseries.BackgroundColor.BEIGE;
var g_foreground	        = steelseries.ForegroundType.TYPE1;
var g_pointer		        = steelseries.PointerType.TYPE8;
var g_pointerColour             = steelseries.ColorDef.BLUE;
var g_gaugeType		        = steelseries.GaugeType.TYPE4;
var g_lcdColour		        = steelseries.LcdColor.STANDARD;
var g_knob		       = steelseries.KnobType.STANDARD_KNOB;
var g_knobStyle		        = steelseries.KnobStyle.SILVER;
var g_labelFormat	        = steelseries.LabelNumberFormat.STANDARD;
var g_tickLabelOrientation	= steelseries.TickLabelOrientation.HORIZONTAL;
var g_rainUseSectionColours     = false;    // Only one of these colour options should be true
var g_rainUseGradientColours    = false;   	// Set both to false to use the pointer colour
var g_tempTrendVisible		= true;
var g_pressureTrendVisible	= true;
// the trend images to be used for the popup data, used in conjuction with g_imgPathURL
// by default this is configured for the Cumulus 'standard' web site
// ** If you specify one image in a sub-array, then you MUST provide images for all the other sub-elements
var g_tipImgs;
if (ws_popupGraphs === 0) {
   g_tipImgs = [null, null, null, null, null, null, null, null, null, null];
}
switch (ws_popupGraphs) {
        case 1:          // g_tipImgs for Cumulus standard graphs
                g_tipImgs = 
                [['temp.png', 'intemp.png'],    // Temperature: outdoor, indoor
                 ['temp.png','temp.png','temp.png','temp.png','temp.png'],  // Temperature: dewpnt, apparent, windChill, HeatIndx, humidex
                  'raint.png',                  // Rainfall
                  'rain.png',                   // Rainfall rate
                 ['hum.png', 'hum.png'],        // Humidity: outdoor, indoor
                  'press.png',                  // Pressure
                  'wind.png',                   // Wind speed
                  'windd.png',                  // Wind direction
                  (g_showUvGauge ? 'uv.png' : null),            // UV
                  (g_showSolarGauge ? 'solar.png' : null)       // Solar rad
                ];
        break;
        case 2:         // g_tipImgs for WD CW MH  users with clientraw (4 files)  and wxgraph
                g_tipImgs = 
                [['temp+hum_24hr.php?lang='+wxLang, 'indoor_temp_24hr.php?lang='+wxLang], 
                 ['temp+dew+hum_1hr.php?lang='+wxLang,'temp+dew+hum_1hr.php?lang='+wxLang,'temp+dew+hum_1hr.php?lang='+wxLang,'temp+dew+hum_1hr.php?lang='+wxLang,'temp+dew+hum_1hr.php?lang='+wxLang],  // Temperature: dewpnt, apparent, windChill, HeatIndx, humidex
                  'rain_24hr.php?lang='+wxLang,
                  'rain_1hr.php?lang='+wxLang, 
                 ['humidity_1hr.php?lang='+wxLang, 'humidity_7days.php?lang='+wxLang],
                  'baro_24hr.php?lang='+wxLang,  
                  'windgust_1hr.php?lang='+wxLang,  
                  'winddir_24hr.php?lang='+wxLang, 
                  (g_showUvGauge ? 'uv_24hr.php?lang='+wxLang : null),
                  (g_showSolarGauge ? 'solar_24hr.php?lang='+wxLang : null)
                ];
        break;
        case 3:         // g_tipImgs for WeatherLink  users 
                g_tipImgs = 
                [['OutsideTemp.gif', 'InsideTemp.gif'],
		['DewPoint.gif',null,'WindChill.gif','HeatIndex.gif',null],
		 'Rain.gif',
		 'RainRate.gif',
		['OutsideHumidity.gif','InsideHumidity.gif'],
		 'Barometer.gif',
		 'WindSpeed.gif',
		 'WindDirection.gif',
		 (g_showUvGauge ? 'UV.gif': null),
		 (g_showSolarGauge ? 'SolarRad.gif' : null)
		];		
        break;
        case 4:         // g_tipImgs for Weathercat standard graphs
                g_tipImgs = 
                [['temperature1.jpg', 'tempin1.jpg'],
                 ['dewpoint1.jpg','temperature1.jpg','windchill1.jpg','heatindex1.jpg','temperature1.jpg'],  
                  'precipitationc1.jpg', 
                  'precipitation1.jpg',
                 ['rh1.jpg', 'rhin1.jpg'],
                  'pressure1.jpg', 
                  'windspeed1.jpg',  
                  'winddirection1.jpg',  
                  (g_showUvGauge ? 'uv1.jpg': null),  
                  (g_showSolarGauge ? 'solarrad1.jpg' : null)
                ];
        break;
        case 5:         // g_tipImgs for VWS standard graphs
                g_tipImgs = 
                [['vws742.jpg', 'vws741.jpg'],
                 ['vws757.jpg', 'vws762.jpg', 'vws754.jpg', 'vws756.jpg', null],  
                  'vws744.jpg', 
                  'vws859.jpg',
                 ['vws740.jpg', 'vws739.jpg'],
                  'vws758.jpg',
                  'vws737.jpg',  
                  'vws736.jpg', 
                  (g_showUvGauge ? 'vws752.jpg' : null), 
                  (g_showSolarGauge ?  'vws753.jpg' : null)
                ];
        break;
        case 6:         // g_tipImgs for WVIEW standard graphs
                g_tipImgs = 
                [['tempdaycomp.png', 'intemp.png'],  // intempdaycomp.png 
                 ['tempdaycomp.png', 'tempdaycomp.png', 'heatchillcomp.png', 'heatchillcomp.png', 'tempdaycomp.png'],  
                  'rainday.png', 
                  'rainrate.png',
                 ['humidday.png', 'humidday.png'],
                  'baromday.png',
                  'wspeeddaycomp.png',  
                  'wdirday.png',
                  (g_showUvGauge ? 'UVday.png' : null), 
                  (g_showSolarGauge ?  'radiationDay.png' : null)
                ];
        break;
        default:
                g_tipImgs = [null, null, null, null, null, null, null, null, null, null];
}

// nothing below this line needs to be modified
// - unless you really know what you are doing
// - but remember, if you break it, it's up to you to fix it ;)
// ------------------------------------------------------------
var g_firstRun 		= true;         //Used to setup units & scales etc
var g_refreshGraphs     = false;        //Flag to signal refesh of the pop data graphs
var data                = {};           //Stores all the values from realtime.txt in a more readable format (esp when debugging!)
var g_countDownTimer;
var count               = g_count;      //countdown tracker
var httpError           = 0;            //global to track download errors
var objXML;                             //global object to host XMLHttp object
var xmlHttpTimeout;                     //global to hold XMLHTTP timeout function
var g_statusStr 	= LANG.statusStr;
var g_cacheDefeat 	= '?' + (new Date()).getTime().toString(); //used to force reload of popup data graphs,
var g_pageLoaded 	= new Date();
var g_units = {};
var g_temp = {};
var g_dew = {};
var g_wind = {};
var g_dir = {};
var g_rain = {};
var g_rrate = {};
var g_baro = {};
var g_hum = {};
var g_uv = {};
var g_solar = {};
var g_led = {};
var g_sampleDate;
var g_windArray 	= [];   // 'initialise' the wind vector array
var g_realtimeVer 	= 6;
var gauge_temp, gauge_dew, gauge_rain, gauge_rrate,
    gauge_hum, gauge_baro, gauge_wind, gauge_wdir,
    gauge_status, gauge_timer, gauge_uv, gauge_solar, gauge_led;

function init() {
    // define temperature gauge start values
    g_temp.sections = createTempSections(0, true);
    g_temp.areas = [];
    g_temp.minValue = 0;
    g_temp.maxValue = 40;
    g_temp.title = LANG.temp_title_out;
    g_temp.value = 0.0001;
    g_temp.maxMinVisible = false;
    g_temp.selected = 'out';

    // define dew point gauge start values
    g_dew.sections = createTempSections(0, true);
    g_dew.areas = [];
    g_dew.minValue = 0;
    g_dew.maxValue = 40;
    g_dew.title = LANG.dew_title;
    g_dew.value = 0.0001;
    g_dew.selected = 'dew';
    g_dew.minMeasuredVisible = false;
    g_dew.maxMeasuredVisible = false;

    // define rain gauge start values
    g_rain.maxValue = 10;
    g_rain.value = 0.0001;
    g_rain.title = LANG.rain_title;
    g_rain.lcdDecimals = 1;
    g_rain.scaleDecimals = 1;
    g_rain.labelNumberFormat = g_labelFormat;
    g_rain.sections = (g_rainUseSectionColours ? createRainfallSections(true) : []);
    g_rain.valGrad = (g_rainUseGradientColours ? createRainfallGradient(true) : null);

    // define rain rate gauge start values
    g_rrate.maxMeasured = 0;
    g_rrate.maxValue = 10;
    g_rrate.value = 0.0001;
    g_rrate.title = LANG.rrate_title;
    g_rrate.lcdDecimals = 1;
    g_rrate.scaleDecimals = 0;
    g_rrate.labelNumberFormat = g_labelFormat;

    // define humidity gauge start values
    g_hum.areas = [];
    g_hum.value = 0.0001;
    g_hum.title = LANG.hum_title_out;
    g_hum.selected = 'out';

    // define pressure/barometer gauge start values
    g_baro.sections = [];
    g_baro.areas = [];
    g_baro.minValue = 990;
    g_baro.maxValue = 1030;
    g_baro.value = 990;
    g_baro.title = LANG.baro_title;
    g_baro.lcdDecimals = 1;
    g_baro.scaleDecimals = 0;
    g_baro.labelNumberFormat = g_labelFormat;

    // define wind gauge start values
    g_wind.maxValue = 20;
    g_wind.areas = [];
    g_wind.maxMeasured = 0;
    g_wind.value = 0.0001;
    g_wind.title = LANG.wind_title;

    // define wind direction gauge start values
    g_dir.valueLatest = 0;
    g_dir.valueAverage = 0;
    g_dir.titles = [LANG.latest_web, LANG.tenminavg_web];

    // define UV start values
    g_uv.value = 0.0001;
    g_uv.title = LANG.uv_title;
    g_uv.sections = [steelseries.Section(0, 2.9, '#289500'),
                     steelseries.Section(2.9, 5.8, '#f7e400'),
                     steelseries.Section(5.8, 7.8, '#f85900'),
                     steelseries.Section(7.8, 10.9, '#d8001d'),
                     steelseries.Section(10.9, 20, '#6b49c8')];
    g_uv.useSections = true;
    g_uv.lcdDecimals = 1;
    g_uv.minValue = 0;
    g_uv.maxValue = 16;

    // define Solar start values
    g_solar.value = 0.0001;
    g_solar.title = LANG.solar_title;
    g_solar.units = "W/m2";
    g_solar.sections = [steelseries.Section(0, 600, 'rgba(40,149,0,0.3)'),
                        steelseries.Section(600, 800, 'rgba(248,89,0,0.3)'),
                        steelseries.Section(800, 1000, 'rgba(216,0,29,0.3)'),
                        steelseries.Section(1000, 1800, 'rgba(107,73,200,0.3)')];
    g_solar.lcdDecimals = 0;
    g_solar.minValue = 0;
    g_solar.maxValue = g_solarGaugeScaleMax;

    // define led indicator
    g_led.on = false;
    g_led.blink = false;
    g_led.oldBlink = g_led.blink;
    g_led.title = LANG.led_title;
    g_led.colour = steelseries.LedColor.GREEN_LED;
    g_led.oldColour = g_led.colour;

    // set some default units
    // DO NOT CHANGE THESE - THE SCRIPT DEPENDS ON THESE DEFAULTS
    // the units actually displayed, will be read from the realtime.txt file
    data.tempunit = g_grad +'C';
    data.rainunit = 'mm';
    data.pressunit = 'hPa';
    data.windunit = 'mph';

    // enable popup data
    ddimgtooltip.showTips = g_showPopupData;

    // remove the UV gauge?
    if (!g_showUvGauge) {
        if (document.getElementById('canvas_uv')) {
            var x = document.getElementById('canvas_uv');
            x.parentNode.removeChild(x);
            x = document.getElementById('tip_8');
            x.parentNode.removeChild(x);
            x = document.getElementById('uv_cell');
            x.parentNode.removeChild(x);
        }
   }

    // remove the Solar gauge?
    if (!g_showSolarGauge) {
        if (document.getElementById('canvas_solar')) {
            var x = document.getElementById('canvas_solar');
            x.parentNode.removeChild(x);
            x = document.getElementById('tip_9');
            x.parentNode.removeChild(x);
            x = document.getElementById('solar_cell');
            x.parentNode.removeChild(x);
        }
   }

    // draw empty gauges
    drawGauges();

    // set the script version on the page
    if (document.getElementById('scriptVer')) {
        document.getElementById('scriptVer').innerHTML = g_scriptVer;
    }

    // let's set some values...
    getRealtime();
}

function drawGauges() {
    drawLed();
    drawStatus();
    drawTimer();
    drawTemp();
    drawDew();
    drawRain();
    drawRRate();
    drawHum();
    drawBaro();
    drawWind();
    drawDir();
    if (g_showUvGauge) { drawUV(); }
    if (g_showSolarGauge) { drawSolar(); }
}

function drawLed() {
    // create led indicator
    if (document.getElementById('canvas_led')) {
        gauge_led = new steelseries.Led(
                'canvas_led', {
                    size : 25,
                    ledColor : g_led.colour
                });
        if (g_led.on) {
            gauge_led.toggleLed();
        }
        document.getElementById('canvas_led').title = g_led.title;
    }
}

function drawStatus() {
    // create forecast display
    if (document.getElementById('canvas_status')) {
        gauge_status = new steelseries.DisplaySingle(
                'canvas_status', {
                    width : g_forecast_width,
                    height : 25,
                    lcdColor : g_lcdColour,
                    unitStringVisible : false,
                    value : g_statusStr,
                    digitalFont : g_digitalForecast,
                    valuesNumeric : false,
                    autoScroll : true
                });
    }
}

function drawTimer() {
    // create timer display
    if (document.getElementById('canvas_timer')) {
        gauge_timer = new steelseries.DisplaySingle(
                'canvas_timer', {
                    width : (g_count.toString().length * 13) + (LANG.timer.length * 6),
                    height : 25,
                    lcdColor : g_lcdColour,
                    lcdDecimals : 0,
                    unitString : LANG.timer,
                    unitStringVisible : true,
                    digitalFont : g_digitalFont,
                    value : count
                });
    }
}

function drawTemp() {
    // create temperature radial gauge
    if (document.getElementById('canvas_temp')) {
        gauge_temp = new steelseries.Radial(
                'canvas_temp', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    section : g_temp.sections,
                    area : g_temp.areas,
                    minValue : g_temp.minValue,
                    maxValue : g_temp.maxValue,
                    thresholdVisible : false,
                    minMeasuredValueVisible : g_temp.maxMinVisible,
                    maxMeasuredValueVisible : g_temp.maxMinVisible,
                    ledVisible : false,
                    titleString : g_temp.title,
                    unitString : data.tempunit,
                    lcdDecimals : 1,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    trendVisible : g_tempTrendVisible,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    lcdColor : g_lcdColour,
                    digitalFont : g_digitalFont,
                    tickLabelOrientation: g_tickLabelOrientation,
                    labelNumberFormat : g_labelFormat
                });
        gauge_temp.setValue(g_temp.value);
    }
}

function drawDew() {
    // create dew point radial gauge
    if (document.getElementById('canvas_dew')) {
        gauge_dew = new steelseries.Radial(
                'canvas_dew', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    section : g_dew.sections,
                    area : g_dew.areas,
                    minValue : g_dew.minValue,
                    maxValue : g_dew.maxValue,
                    minMeasuredValueVisible : false,
                    maxMeasuredValueVisible : false,
                    thresholdVisible : false,
                    ledVisible : false,
                    titleString : g_dew.title,
                    unitString : data.tempunit,
                    lcdDecimals : 1,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    lcdColor : g_lcdColour,
                    digitalFont : g_digitalFont,
                //    customLayer : g_imgLarge,               // show a large background image on dew point gauge
                    tickLabelOrientation: g_tickLabelOrientation,
                    labelNumberFormat : g_labelFormat
                });
        gauge_dew.setValue(g_dew.value);
    }
}

function drawRain() {
    // create rain radial bargraph gauge
    if (document.getElementById('canvas_rain')) {
        gauge_rain = new steelseries.RadialBargraph(
                'canvas_rain', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    minValue : 0,
                    maxValue : g_rain.maxValue,
                    titleString : g_rain.title,
                    unitString : data.rainunit,
                    thresholdVisible : false,
                    minMeasuredValueVisible : false,
                    maxMeasuredValueVisible : false,
                    ledVisible : false,
                    lcdDecimals : g_rain.lcdDecimals,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    valueColor : g_pointerColour,
                    lcdColor : g_lcdColour,
                    section : g_rain.sections,
                    digitalFont : g_digitalFont,
                    valueGradient : g_rain.valGrad,
                    useValueGradient : g_rainUseGradientColours,
                    useSectionColors : g_rainUseSectionColours,
                    labelNumberFormat : g_rain.labelNumberFormat,
                    tickLabelOrientation: g_tickLabelOrientation,
                    fractionalScaleDecimals : g_rain.scaleDecimals
                });
        gauge_rain.setValue(g_rain.value);
    }
}

function drawRRate() {
    // create rain rate radial gauge
    if (document.getElementById('canvas_rrate')) {
        gauge_rrate = new steelseries.Radial(
                'canvas_rrate', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    minValue : 0,
                    maxValue : g_rrate.maxValue,
                    thresholdVisible : false,
                    maxMeasuredValueVisible : true,
                    minMeasuredValueVisible : false,
                    ledVisible : false,
                    titleString : g_rrate.title,
                    unitString : data.rainunit + '/h',
                    lcdDecimals : g_rrate.lcdDecimals,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    lcdColor : g_lcdColour,
                    digitalFont : g_digitalFont,
                    labelNumberFormat : g_rrate.labelNumberFormat,
                    tickLabelOrientation: g_tickLabelOrientation,
                    fractionalScaleDecimals : g_rrate.scaleDecimals
                });
        gauge_rrate.setMaxMeasuredValue(g_rrate.maxMeasured);
        gauge_rrate.setValue(g_rrate.value);
    }
}

function drawHum() {
    // create humidity radial gauge
    if (document.getElementById('canvas_hum')) {
        gauge_hum = new steelseries.Radial(
                'canvas_hum', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    minValue : 0,
                    maxValue : 100,
                    section : [
                        steelseries.Section(0, 20, 'rgba(255,255,0,0.3)'),
                        steelseries.Section(20, 80, 'rgba(0,255,0,0.3)'),
                        steelseries.Section(80, 100, 'rgba(255,0,0,0.3)')
                    ],
                    area : g_hum.areas,
                    thresholdVisible : false,
                    minMeasuredValueVisible : false,
                    maxMeasuredValueVisible : false,
                    ledVisible : false,
                    titleString : g_hum.title,
                    unitString : '%',
                    lcdDecimals : 1,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    lcdColor : g_lcdColour,
                    digitalFont : g_digitalFont,
                    tickLabelOrientation: g_tickLabelOrientation,
                    labelNumberFormat : g_labelFormat
                });
        gauge_hum.setValue(g_hum.value);
    }
}

function drawBaro() {
    // create pressure/barometric radial gauge
    if (document.getElementById('canvas_baro')) {
        gauge_baro = new steelseries.Radial(
                'canvas_baro', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    minValue : g_baro.minValue,
                    maxValue : g_baro.maxValue,
                    niceScale : false,
                    thresholdVisible : false,
                    minMeasuredValueVisible : true,
                    maxMeasuredValueVisible : true,
                    section : g_baro.sections,
                    area : g_baro.areas,
                    titleString : g_baro.title,
                    unitString : data.pressunit,
                    lcdDecimals : g_baro.lcdDecimals,
                    ledVisible : false,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    lcdColor : g_lcdColour,
                    digitalFont : g_digitalFont,
                    trendVisible : g_pressureTrendVisible,
                    labelNumberFormat : g_baro.labelNumberFormat,
                    tickLabelOrientation: g_tickLabelOrientation,
                    fractionalScaleDecimals : g_baro.scaleDecimals
                });
        gauge_baro.setValue(g_baro.value);
    }
}

function drawWind() {
    // create wind speed radial gauge
    if (document.getElementById('canvas_wind')) {
        gauge_wind = new steelseries.Radial(
                'canvas_wind', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    area : g_wind.areas,
                    minValue : 0,
                    maxValue : g_wind.maxValue,
                    section : [],
                    thresholdVisible : false,
                    minMeasuredValueVisible : false,
                    maxMeasuredValueVisible : true,
                    ledVisible : false,
                    titleString : g_wind.title,
                    unitString : data.windunit,
                    lcdDecimals : 1,
                    frameDesign : g_frameDesign,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    lcdColor : g_lcdColour,
                    digitalFont : g_digitalFont,
                    tickLabelOrientation: g_tickLabelOrientation,
                    labelNumberFormat : g_labelFormat
                });
        gauge_wind.setMaxMeasuredValue(g_wind.maxMeasured);
        gauge_wind.setValue(g_wind.value);
    }
}

function drawDir() {
    // create wind direction/compass radial gauge
    if (document.getElementById('canvas_dir')) {
        gauge_wdir = new steelseries.WindDirection(
                'canvas_dir', {
                    size : g_size,
                    frameDesign : g_frameDesign,
                    pointerTypeLatest : g_pointer, // default TYPE1,
                    pointerTypeAverage : steelseries.PointerType.TYPE8, // default TYPE8
                    pointerColor : g_pointerColour,
                    pointerColorAverage : steelseries.ColorDef.RED,
                    knobType : g_knob,
                    knobStyle : g_knobStyle,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    degreeScale : true,             // Show degree scale rather than ordinal directions
                    pointSymbols : LANG.compass,
                    roseVisible : false,            // Show the central compass rose design
                    digitalFont : g_digitalFont,
                    lcdColor : g_lcdColour,
                    lcdTitleStrings : g_dir.titles,
                    useColorLabels : false
                });
        gauge_wdir.setValueAverage(g_dir.valueAverage);
        gauge_wdir.setValueLatest(g_dir.valueLatest);
    }
}

function drawUV() {
    // create UV bargraph gauge
    if (document.getElementById('canvas_uv')) {
        gauge_uv = new steelseries.RadialBargraph(
                'canvas_uv', {
                    size : g_size,
                    gaugeType : steelseries.GaugeType.TYPE3,
                    frameDesign : g_frameDesign,
                    pointerColor : g_pointerColour,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    minValue : g_uv.minValue,
                    maxValue : g_uv.maxValue,
                    titleString : g_uv.title,
                    niceScale : false,
                    ledVisible : false,
                    lcdVisible : true,
                    digitalFont : g_digitalFont,
                    lcdColor : g_lcdColour,
                    lcdDecimals : g_uv.lcdDecimals,
                    tickLabelOrientation: g_tickLabelOrientation,
                    section : g_uv.sections,
                    useSectionColors : g_uv.useSections
                });
        gauge_uv.setValue(g_uv.value);
    }
}

function drawSolar() {
    // create Solar gauge
    if (document.getElementById('canvas_solar')) {
        gauge_solar = new steelseries.Radial(
                'canvas_solar', {
                    size : g_size,
                    gaugeType : g_gaugeType,
                    frameDesign : g_frameDesign,
                    pointerType : g_pointer,
                    pointerColor : g_pointerColour,
                    backgroundColor : g_background,
                    foregroundType : g_foreground,
                    minValue : g_solar.minValue,
                    maxValue : g_solar.maxValue,
                    titleString : g_solar.title,
                    unitString : g_solar.units,
                    niceScale : false,
                    ledVisible : false,
                    lcdVisible : true,
                    thresholdVisible : false,
                    digitalFont : g_digitalFont,
                    lcdColor : g_lcdColour,
                    lcdDecimals : g_solar.lcdDecimals,
                    tickLabelOrientation: g_tickLabelOrientation,
                    section : g_solar.sections
                });
        gauge_solar.setValue(g_solar.value);
    }
}

function doUpdate() {
    // first time only, setup units etc
    if (g_firstRun) {
        doFirst();
        
        if (g_showPopupData) {
            // now initialise the pop-up script and download the trend images
            // - has to be done here as doFirst may remove elements from the page
            // - and we delay the download of the images speeding up page display
            ddimgtooltip.init("[id^='tip_']");
            //
            if (g_showPopupDataGraphs) {
                // kick off a timer for popup graphic updates
                setInterval(function(){g_refreshGraphs=true;}, g_graphUpdateTime * 60 * 1000);
            }
        }
    }

    if (g_refreshGraphs) {
        g_cacheDefeat = '?' + (new Date()).getTime().toString();
    }

    if (gauge_temp) { doTemp(); }
    if (gauge_dew)  { doDew();  }
    if (gauge_baro) { doBaro(); }
    if (gauge_rain) { doRain(); }
    if (gauge_rrate){ doRRate();}
    if (gauge_hum)  { doHum();  }
    if (gauge_wind) { doWind(); }
    if (gauge_wdir) { doDir();  }
    if (gauge_uv)   { doUV();   }
    if (gauge_solar){ doSolar();}

    if (g_refreshGraphs) {
        g_refreshGraphs = false;
    }

    countDown();
}

function getRealtime() {
    setStatus(LANG.StatusMsg);

    if (objXML === undefined) {
        if (window.XMLHttpRequest) { // object of the current windows
            objXML = new XMLHttpRequest(); // Firefox, Safari, IE7,8...
        } else if (window.ActiveXObject) { // ActiveX version - Internet Explorer 5,6
            try {
                objXML = new ActiveXObject('Msxml2.XMLHTTP');
            } catch (e) {
                try {
                    objXML = new ActiveXObject('Microsoft.XMLHTTP');
                } catch (E) {
                    objXML = false;
                    setStatus(LANG.StatusNoHttpObj);
                }
            }
        }

        if (objXML) {
            // check response
            objXML.onreadystatechange = checkRtResp;
            // check for errors
            objXML.onabort = checkRtError;
            objXML.onerror = checkRtError;
            objXML.onTimeout = checkRtError;
        }
    }

    if (objXML) {
        objXML.aborted = false;  //additional property so we can track if we abort the query
        // use date # as cache defeat
        objXML.open('GET', g_realTimeURL + '?lang=' + wxLang + '&' + (new Date()).getTime().toString(), true);
        objXML.send(null); // Go get it
        xmlHttpTimeout = setTimeout(ajaxTimeout, 10000); //set a 10 second timeout
    }
}

function ajaxTimeout() {
    objXML.aborted = true; //additional property so we can track if we abort the query
    objXML.abort();
}

function checkRtResp() {
    if (objXML.readyState === 4 && objXML.aborted === false) { //complete and not aborted?
        // stop the timeout function
        clearTimeout(xmlHttpTimeout);

        if (objXML.status === 200) {
            httpError = 0; //OK, validated so far...

            // ver is the always the last field in the file
            // so we can use it to check both that the file is the correct version, and the correct length
            data.ver = undefined;

            // strip out and cr lf characters, sometimes forecast text can contain these
            var response = objXML.responseText;
            response = response.replace(/[\r\n]/g, '');

            // get the realtime fields into a handy 'data' object
            try {
                data = JSON.parse(response);
            } catch(e) {
                // JSON parse bombs if the file is zero length,
                // so start a quickish retry...
                setStatus(LANG.realtimeCorrupt);
                count = 3; // 2 seconds
                countDown();
                return;
            }
            // and check we have the expected number
            if (data.ver !== undefined && data.ver >= g_realtimeVer) {
                // clean up temperature units - remove html encoding
                if (data.tempunit.length > 1) {
                    // clean up temperature units - remove html encoding
                    // using old format realtimegaugesT.txt
                    data.tempunit.replace('&deg;','');
                } else {
                    // using new realtimegaugesT.txt with Cumulus > 1.9.2
                    data.tempunit = g_grad + data.tempunit;
                }

                // Check for station off-line
                var now = new Date();
                var tmp = data.timeUTC.split(',');
                g_sampleDate = Date.UTC(tmp[0],tmp[1]-1,tmp[2],tmp[3],tmp[4],tmp[5]);
                if (now-g_sampleDate > g_stationOfflineTimeout * 60 * 1000) {
                    var elapsedMins = Math.floor((now-g_sampleDate) / (1000 * 60));
                    // the realtimegauges.txt file isn't being updated
                    g_led.colour = steelseries.LedColor.RED_LED;
                    g_led.title = LANG.led_title_offline;
                    if (elapsedMins < 120) {
                        // up to 2 hours ago
                        tm = elapsedMins.toString() + " " + LANG.StatusMinsAgo;
                    } else if (elapsedMins < 2 * 24 * 60) {
                        // up to 48 hours ago
                        tm = Math.floor(elapsedMins/60).toString() + " " + LANG.StatusHoursAgo;
                    } else {
                        // days ago!
                        tm = Math.floor(elapsedMins/(60*24)).toString() + " " + LANG.StatusDaysAgo;
                    }
                    g_led.blink = true;
                    data.forecast = LANG.led_title_offline + " " + LANG.StatusLastUpdate + " " + tm;
                } else if (+data.SensorContactLost === 1) {
                    // Fine Offset sensor status
                    g_led.colour = steelseries.LedColor.RED_LED;
                    g_led.title = LANG.led_title_lost;
                    g_led.blink = true;
                    data.forecast = LANG.led_title_lost;
                } else {
                    g_led.colour = steelseries.LedColor.GREEN_LED;
                    g_led.title = LANG.led_title_ok;
                    g_led.blink = false;
                }

                if (g_led.colour !== g_led.oldColour) {
                    if (gauge_led) gauge_led.setLedColor(g_led.colour);
                    g_led.oldColour = g_led.colour;
                }
                if (g_led.blink !== g_led.oldBlink) {
                    if (gauge_led) gauge_led.blink(g_led.blink);
                    g_led.oldBlink = g_led.blink;
                }


                data.forecast = $("<div/>").html(data.forecast).text();
                data.forecast.trim();

                if (data.pressunit === 'in') {  // Cumulus pressunit tag value
                    data.pressunit = 'inHg';
                }
                
                setLed(true, g_led.title);
                setStatus(data.forecast);
                doUpdate();

            } else {
                // set an error message
                if (data.ver < g_realtimeVer) {
                    gauge_timer.setValue(0);
                    setStatus("Your " + g_realTimeURL.substr(g_realTimeURL.lastIndexOf('/')+1) + " file template needs updating!");
                    return;
                } else {
                    // oh-oh! The number of data fields isn't what we expected
                    setStatus(LANG.realtimeCorrupt);
                }
                setLed(false, LANG.led_title_unknown);
                count = 4; // 3 second retry
                countDown();
            }
        } else if (objXML.status > 200) {
            setLed(false, LANG.led_title_unknown);
            if (objXML.aborted === true) {
                httpError = LANG.StatusTimeout;
            } else {
                httpError = objXML.status;
            }
            count = 11; // 10 seconds
            countDown();
        }
    }
}

function checkRtError() {
    setLed(false, LANG.led_title_unknown);
    httpError = objXML.status;
    count = 11; // 10 seconds
    countDown();
}

function setStatus(str) {
    g_statusStr = str;
    if (gauge_status) {
        gauge_status.setValue(str);
    }
}

function setLed(onOff, title) {
    g_led.title = title;
    if (gauge_led) {
        gauge_led.setLedOnOff(onOff);
        if (document.getElementById('canvas_led')) {
            document.getElementById('canvas_led').title = g_led.title;
        }
    }
}

function countDown() {
    // has the page update limit been reached - and no password supplied
    var now = new Date();
    if (g_pageUpdateLimit > 0 &&
        now > g_pageLoaded.getTime() + g_pageUpdateLimit * 60000 &&
        g_pageUpdateParam !== g_pageUpdatePswd) {
        setStatus(LANG.StatusPageLimit);
        gauge_timer.setValue(0);
        // and stop
        return;
    }

    count -= 1;
    if (gauge_timer) {
        gauge_timer.setValue(count);
    }

    if (count === 0) {
        getRealtime();
        count = g_count;
    } else {
        g_countDownTimer = setTimeout(countDown, 1000);
        if (httpError !== 0) {
            setStatus(LANG.StatusHttp + ' ' + httpError + ' ' + LANG.StatusRetryIn);
        }
    }
}

function doTemp(rad) {
    // if rad isn't specified, just use existing value
    var sel = rad === undefined ? g_temp.selected : rad.value;
    var popupImg;

    if (sel === 'out') {
        g_temp.low = extractDecimal(data.tempTL);
        g_temp.high = extractDecimal(data.tempTH);
        g_temp.value = extractDecimal(data.temp);
        g_temp.title = LANG.temp_title_out;
        g_temp.loc = LANG.temp_out_info;
        popupImg = 0;
        g_temp.trendVal =  extractDecimal(data.temptrend);
        if (g_tempTrendVisible) {
            var t1 = tempTrend(+g_temp.trendVal, data.tempunit, false);
            if (t1 > 0) {
                g_temp.trend = steelseries.TrendState.UP;
            } else if (t1 < 0) {
                g_temp.trend = steelseries.TrendState.DOWN;
            } else {
                g_temp.trend = steelseries.TrendState.STEADY;
            }
        }
    } else {
        g_temp.low = extractDecimal(data.intemp);
        g_temp.high = extractDecimal(data.intemp);
        g_temp.value = extractDecimal(data.intemp);
        g_temp.title = LANG.temp_title_in;
        g_temp.loc = LANG.temp_in_info;
        popupImg = 1;        
        g_temp.maxMinVisible = false;
        if (g_tempTrendVisible) {
            g_temp.trend = steelseries.TrendState.OFF;
        }
    }

    // has the gauge type changed?
    if (g_temp.selected !== sel) {
        g_temp.selected = sel;
        //Change gauge title
        gauge_temp.setTitleString(g_temp.title);
        gauge_temp.setMaxMeasuredValueVisible(g_temp.maxMinVisible);
        gauge_temp.setMinMeasuredValueVisible(g_temp.maxMinVisible);
        if (g_showPopupDataGraphs && g_tipImgs[0][0] !== null) {
            document.getElementById('imgtip0_img').src = g_imgPathURL + g_tipImgs[0][popupImg] + g_cacheDefeat;
        }
    }

    //auto scale the ranges
    var scaleStep;
    if (data.tempunit.indexOf('C') !== -1) {
        scaleStep = 20;
    } else {
        scaleStep = 30;
    }
    while (g_temp.low < g_temp.minValue) {
        g_temp.minValue -= scaleStep;
        g_temp.maxValue -= scaleStep;
    }
    while (g_temp.high > g_temp.maxValue) {
        g_temp.minValue += scaleStep;
        g_temp.maxValue += scaleStep;
    }

    if (g_temp.minValue !== gauge_temp.getMinValue()) {
        g_temp.sections = createTempSections(+g_temp.minValue, (data.tempunit.indexOf('C') !== -1) ? true : false);
        gauge_temp.setMinValue(+g_temp.minValue);
        gauge_temp.setMaxValue(+g_temp.maxValue);
        gauge_temp.setSection(g_temp.sections);
        gauge_temp.setValue(+g_temp.minValue);
    }
    if (g_temp.selected === 'out') {
        g_temp.areas = [steelseries.Section(+g_temp.low, +g_temp.high, minMaxArea)];
    } else {
        g_temp.areas = [];
    }

    if (g_tempTrendVisible) {
        gauge_temp.setTrend(g_temp.trend);
    }
    gauge_temp.setArea(g_temp.areas);
    gauge_temp.setValueAnimated(+g_temp.value);

    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip;
        if (g_temp.selected === 'out') {
            tip = g_temp.loc + ' - ' + LANG.lowestF_info + ': ' + g_temp.low + data.tempunit + ' ' + LANG.at + ' ' + data.TtempTL +
                 ' | ' +
                 LANG.highestF_info + ': ' + g_temp.high +  data.tempunit + ' ' + LANG.at + ' ' + data.TtempTH +
                 '<br>' +
                 LANG.temp_trend_info + ': ' + tempTrend(g_temp.trendVal,  data.tempunit, true) + ' ' + g_temp.trendVal +  data.tempunit + '/h';
        } else {
            tip = g_temp.loc + ': ' + data.intemp +  data.tempunit;
        }
        document.getElementById('imgtip0_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[0][0] !== null) {
            document.getElementById('imgtip0_img').src = g_imgPathURL + g_tipImgs[0][g_temp.selected === 'out' ? 0 : 1] + g_cacheDefeat;
        }
    }
}

function doDew(rad) {
    var tip;
    // if rad isn't specified, just use existing value
    if (rad === undefined) {sel = g_dew.selected;} else
    {sel = rad.value;}
//    var sel = rad === undefined ? g_dew.selected : rad.value;
    var popupImg;

    switch (sel) {
        case 'dew': // dew point
            g_dew.low = extractDecimal(data.dewpointTL);
            g_dew.high = extractDecimal(data.dewpointTH);
            g_dew.value = extractDecimal(data.dew);
            g_dew.areas = [steelseries.Section(+g_dew.low, +g_dew.high, minMaxArea)];
            g_dew.title = LANG.dew_title;
            g_dew.minMeasuredVisible = false;
            g_dew.maxMeasuredVisible = false;
            popupImg = 0;
            tip = LANG.dew_info + ':' +
                 '<br>' +
                 '- ' + LANG.lowest_info + ': ' + g_dew.low +  data.tempunit + ' ' + LANG.at + ' ' + data.TdewpointTL +
                 ' | ' + LANG.highest_info + ': ' + g_dew.high +  data.tempunit + ' ' + LANG.at + ' ' + data.TdewpointTH;
            break;
        case 'app': // apparent temperature
            g_dew.low = extractDecimal(data.apptempTL);
            g_dew.high = extractDecimal(data.apptempTH);
            g_dew.value = extractDecimal(data.apptemp);
            g_dew.areas = [steelseries.Section(+g_dew.low, +g_dew.high, minMaxArea)];
            g_dew.title = LANG.apptemp_title;
            g_dew.minMeasuredVisible = false;
            g_dew.maxMeasuredVisible = false;
            popupImg = 1;
            tip = tip = LANG.apptemp_info + ':' +
                 '<br>' +
                 '- ' + LANG.lowestF_info + ': ' + g_dew.low +  data.tempunit + ' ' + LANG.at + ' ' + data.TapptempTL +
                 ' | ' + LANG.highestF_info + ': ' + g_dew.high +  data.tempunit + ' ' + LANG.at + ' ' + data.TapptempTH;
            break;
        case 'wnd': // wind chill
            g_dew.low = extractDecimal(data.wchillTL);
            g_dew.high = extractDecimal(data.wchill);
            g_dew.value = extractDecimal(data.wchill);
            g_dew.areas = [];
            g_dew.title = LANG.chill_title;
            g_dew.minMeasuredVisible = true;
            g_dew.maxMeasuredVisible = false;
            popupImg = 2;
            tip = LANG.chill_info + ':' +
                '<br>' +
                '- ' + LANG.lowest_info + ': ' + g_dew.low +  data.tempunit + ' ' + LANG.at + ' ' + data.TwchillTL;
            break;
        case 'hea': // heat index
            g_dew.low = extractDecimal(data.heatindex);
            g_dew.high = extractDecimal(data.heatindexTH);
            g_dew.value = extractDecimal(data.heatindex);
            g_dew.areas = [];
            g_dew.title = LANG.heat_title;
            g_dew.minMeasuredVisible = false;
            g_dew.maxMeasuredVisible = true;
            popupImg = 3;
            tip = LANG.heat_info + ':' +
                '<br>' +
                '- ' + LANG.highest_info + ': ' + g_dew.high +  data.tempunit + ' ' + LANG.at + ' ' + data.TheatindexTH;
            break;
        case 'hum': // humidex
            g_dew.low = extractDecimal(data.humidex);
            g_dew.high = extractDecimal(data.humidex);
            g_dew.value = extractDecimal(data.humidex);
            g_dew.areas = [];
            g_dew.title = LANG.humdx_title;
            g_dew.minMeasuredVisible = false;
            g_dew.maxMeasuredVisible = false;
            popupImg = 4;
            tip = LANG.humdx_info + ': ' + g_dew.value +  data.tempunit;
            break;
    }

    if (g_dew.selected !== sel) {
        g_dew.selected = sel;
        // change gauge title
        gauge_dew.setTitleString(g_dew.title);
        // and graph image
        if (g_showPopupDataGraphs && g_tipImgs[1][0] !== null) {
            document.getElementById('imgtip1_img').src = g_imgPathURL + g_tipImgs[1][popupImg] + g_cacheDefeat;
        }
    }

    //auto scale the ranges
    var scaleStep;
    if (data.tempunit.indexOf('C') !== -1) {
        scaleStep = 20;
    } else {
        scaleStep = 30;
    }
    while (g_dew.low < g_dew.minValue) {
        g_dew.minValue -= scaleStep;
        g_dew.maxValue -= scaleStep;
    }
    while (g_dew.high > g_dew.maxValue) {
        g_dew.minValue += scaleStep;
        g_dew.maxValue += scaleStep;
    }

    if (g_dew.minValue !== gauge_dew.getMinValue()) {
        g_dew.sections = createTempSections(g_dew.minValue, (data.tempunit.indexOf('C') !== -1) ? true : false);
        gauge_dew.setMinValue(g_dew.minValue);
        gauge_dew.setMaxValue(g_dew.maxValue);
        gauge_dew.setSection(g_dew.sections);
        gauge_dew.setValue(g_dew.minValue);
    }

    gauge_dew.setMinMeasuredValueVisible(g_dew.minMeasuredVisible);
    gauge_dew.setMaxMeasuredValueVisible(g_dew.maxMeasuredVisible);
    gauge_dew.setMinMeasuredValue(+g_dew.low);
    gauge_dew.setMaxMeasuredValue(+g_dew.high);
    gauge_dew.setArea(g_dew.areas);
    gauge_dew.setValueAnimated(+g_dew.value);

    if (ddimgtooltip.showTips) {
        // update tooltip
        document.getElementById('imgtip1_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[1][0] !== null) {
            document.getElementById('imgtip1_img').src = g_imgPathURL + g_tipImgs[1][popupImg] + g_cacheDefeat;
        }
    }
}

function doRain() {
    g_rain.value = extractDecimal(data.rfall);

    if (data.rainunit === 'mm') { // 10, 20, 30...
        g_rain.maxValue = Math.max(Math.ceil(g_rain.value / 10) * 10, 10);
    } else {
        // inches 0.5, 1.0, 1.5, 2.0 ...
        //g_rain.maxValue = Math.max(Math.ceil(+data.rfall.replace(',', '.') * 2) * 0.5, 0.5);
        // inches 1.0, 2.0, 3.0 ... 10.0, 12.0, 14.0
        if (g_rain.value < 6) {
            g_rain.maxValue = Math.max(Math.ceil(g_rain.value), 1.0);
        } else {
            g_rain.maxValue = Math.ceil(g_rain.value / 2) * 2;
        }
    }

    if (g_rain.maxValue !== gauge_rain.getMaxValue()) {
        gauge_rain.setMaxValue(g_rain.maxValue);
    }

    gauge_rain.setValueAnimated(g_rain.value);

    if (ddimgtooltip.showTips) {
        // update tooltip
        document.getElementById('imgtip2_txt').innerHTML = LANG.LastRain_info + ': ' + data.LastRained;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[2] !== null) {
            document.getElementById('imgtip2_img').src = g_imgPathURL + g_tipImgs[2] + g_cacheDefeat;
        }
    }
}

function doRRate() {
    g_rrate.value = extractDecimal(data.rrate);
    g_rrate.maxMeasured = extractDecimal(data.rrateTM);

    if (data.rainunit === 'mm') { // 10, 20, 30...
        g_rrate.maxValue = Math.max(Math.ceil(g_rrate.maxMeasured / 10) * 10, 10);
    } else {
        // inches 0.5, 1.0, 1.5, 2.0 ...
        //g_rrate.maxValue = Math.max(Math.ceil(+data.rrateTM.replace(',', '.') * 2) * 0.5, 0.5);
        // inches 1.0, 2.0, 3.0 ...
        g_rrate.maxValue = Math.max(Math.ceil(g_rrate.maxMeasured), 1.0);
    }

    if (g_rrate.maxValue !== gauge_rrate.getMaxValue()) {
        gauge_rrate.setMaxValue(g_rrate.maxValue);
    }

    gauge_rrate.setSection(createRainRateSections(g_rrate.maxValue, data.rainunit === 'mm'));
    gauge_rrate.setValueAnimated(g_rrate.value);
    gauge_rrate.setMaxMeasuredValue(g_rrate.maxMeasured);

    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip = LANG.rrate_info + ':<br>' +
            '- ' + LANG.maximum_info + ': ' + data.rrateTM + ' ' + data.rainunit + '/h ' + LANG.at + ' ' + data.TrrateTM +
            ' | ' + LANG.max_hour_info + ': ' + extractDecimal(data.hourlyrainTH) + ' ' + data.rainunit + ' ' + LANG.at + ' ' + data.ThourlyrainTH;
        document.getElementById('imgtip3_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[3] !== null) {
            document.getElementById('imgtip3_img').src = g_imgPathURL + g_tipImgs[3] + g_cacheDefeat;
        }
    }
}

function doHum(rad) {
    //if rad isn't specified, just use existing value
    var sel = rad === undefined ? g_hum.selected : rad.value;
    var popupImg;

    if (sel === 'out') {
        g_hum.value = extractDecimal(data.hum);
        g_hum.areas = [steelseries.Section(+extractDecimal(data.humTL), +extractDecimal(data.humTH), minMaxArea)];
        g_hum.title = LANG.hum_title_out;
        popupImg = 0;
    } else {
        g_hum.value = extractDecimal(data.inhum);
        g_hum.areas = [];
        g_hum.title = LANG.hum_title_in;
        popupImg = 1;
    }

    if (g_hum.selected !== sel) {
        g_hum.selected = sel;
        //Change gauge title
        gauge_hum.setTitleString(g_hum.title);
        if (g_showPopupDataGraphs) {
            document.getElementById('imgtip4_img').src = g_imgPathURL + g_tipImgs[4][popupImg] + g_cacheDefeat;
        }

    }

    gauge_hum.setArea(g_hum.areas);
    gauge_hum.setValueAnimated(g_hum.value);

    if (ddimgtooltip.showTips) {
        //update tooltip
        var tip;
        if (g_hum.selected === 'out') {
            tip = LANG.hum_out_info + ':' +
                '<br>' +
                '- ' + LANG.minimum_info + ': ' + extractDecimal(data.humTL) + '% ' + LANG.at + ' ' + data.ThumTL +
                ' | ' + LANG.maximum_info + ': ' + extractDecimal(data.humTH) + '% ' + LANG.at + ' ' + data.ThumTH;
        } else {
            tip = LANG.hum_in_info + ': ' + extractDecimal(data.inhum) + '%';
        }
        document.getElementById('imgtip4_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[4][0] !== null) {
            document.getElementById('imgtip4_img').src = g_imgPathURL + g_tipImgs[4][popupImg] + g_cacheDefeat;
        }
    }
}

function doBaro() {
    g_baro.recLow  = +extractDecimal(data.pressL);
    g_baro.recHigh = +extractDecimal(data.pressH);
    g_baro.todayLow  = +extractDecimal(data.pressTL);
    g_baro.todayHigh = +extractDecimal(data.pressTH);
    g_baro.value = +extractDecimal(data.press);
    g_baro.trendVal =  +data.presstrendval; 
    				// Convert the WD change over 3 hours to an hourly rate
    				// +extractDecimal(data.presstrendval) / (ws_popupGraphs === 0 ? 1 : 3);

    if (data.pressunit === 'hPa' ||
        data.pressunit === 'mb') {
        //  min range 990-1030 - steps of 10 hPa
        g_baro.minValue = Math.min(Math.floor((g_baro.recLow - 2) / 10) * 10, 990);
        g_baro.maxValue = Math.max(Math.ceil((g_baro.recHigh + 2) / 10) * 10, 1030);
        g_baro.trendValRnd = g_baro.trendVal.toFixed(1);    // round to 0.1
    } else if (data.pressunit === 'kPa'){
        //  min range 99-105 - steps of 1 kPa
        g_baro.minValue = Math.min(Math.floor(g_baro.recLow - 0.2), 99);
        g_baro.maxValue = Math.max(Math.ceil(g_baro.recHigh + 0.2), 105);
        g_baro.trendValRnd = g_baro.trendVal.toFixed(1);    // round to 0.1
    } else {
        // inHg: min range 29.5-30.5 - steps of 0.5 inHg
        g_baro.minValue = Math.min(Math.floor((g_baro.recLow - 0.1) * 2) / 2, 29.5);
        g_baro.maxValue = Math.max(Math.ceil((g_baro.recHigh + 0.1) * 2) / 2, 30.5);
        g_baro.trendValRnd = g_baro.trendVal.toFixed(3);    // round to 0.001
    }
    if (g_baro.minValue !== gauge_baro.getMinValue() || g_baro.maxValue !== gauge_baro.getMaxValue()) {
        gauge_baro.setMinValue(g_baro.minValue);
        gauge_baro.setMaxValue(g_baro.maxValue);
    }
    g_baro.sections = [
        steelseries.Section(g_baro.minValue, g_baro.recLow, 'rgba(255,0,0,0.5)'),
        steelseries.Section(g_baro.recHigh, g_baro.maxValue, 'rgba(255,0,0,0.5)')
    ];
    g_baro.areas = [
        steelseries.Section(g_baro.minValue, g_baro.recLow, 'rgba(255,0,0,0.5)'),
        steelseries.Section(g_baro.recHigh, g_baro.maxValue, 'rgba(255,0,0,0.5)'),
        steelseries.Section(g_baro.todayLow, g_baro.todayHigh, minMaxArea)
    ];

    if (g_pressureTrendVisible) {
        // Use the baroTrend rather than simple arithmetic test - steady is more/less than zero!
        var t1 = baroTrend(g_baro.trendVal, data.pressunit, false);
        if (t1 > 0) {
            g_baro.trend = steelseries.TrendState.UP;
        } else if (t1 < 0) {
            g_baro.trend = steelseries.TrendState.DOWN;
        } else {
            g_baro.trend = steelseries.TrendState.STEADY;
        }
        gauge_baro.setTrend(g_baro.trend);
    }

    gauge_baro.setArea(g_baro.areas);
    gauge_baro.setSection(g_baro.sections);
    gauge_baro.setValueAnimated(g_baro.value);

    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip = LANG.baro_info + ':' +
            '<br>' +
            '- ' + LANG.minimum_info + ': ' + g_baro.todayLow + ' ' + data.pressunit + ' ' + LANG.at + ' ' + data.TpressTL +
            ' | ' + LANG.maximum_info + ': ' + g_baro.todayHigh + ' ' + data.pressunit + ' ' + LANG.at + ' ' + data.TpressTH +
            '<br>' +
            '- ' + LANG.baro_trend_info + ': ' + baroTrend(g_baro.trendVal, data.pressunit, true) + ' ' +
            (g_baro.trendValRnd > 0 ? '+' : '') + g_baro.trendValRnd + ' ' + data.pressunit + '/h';
        document.getElementById('imgtip5_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[5] != null) {
            document.getElementById('imgtip5_img').src = g_imgPathURL + g_tipImgs[5] + g_cacheDefeat;
        }
    }
}

function doWind() {
    g_wind.value = extractDecimal(data.wlatest);
    g_wind.average = extractDecimal(data.wspeed);
    g_wind.gust = extractDecimal(data.wgust);
    g_wind.maxGustToday = extractDecimal(data.wgustTM);
    g_wind.maxAvgToday = extractDecimal(data.windTM);

    switch (data.windunit) {
        case 'mph':
        case 'kts':
            g_wind.maxValue = Math.max(Math.ceil(g_wind.maxGustToday / 10) * 10, 20);
            break;
        case 'm/s':
            g_wind.maxValue = Math.max(Math.ceil(g_wind.maxGustToday / 5) * 5, 10);
            break;
        default:
            g_wind.maxValue = Math.max(Math.ceil(g_wind.maxGustToday / 20) * 20, 30);
    }
    g_wind.areas = [
        steelseries.Section(0, +g_wind.average, 'rgba(0,200,0,0.2)'),
        steelseries.Section(+g_wind.average, +g_wind.gust, 'rgba(220,0,0,0.2)')
    ];
    if (g_wind.maxValue !== gauge_wind.getMaxValue()) {
        gauge_wind.setMaxValue(g_wind.maxValue);
    }

    gauge_wind.setArea(g_wind.areas);
    gauge_wind.setMaxMeasuredValue(g_wind.maxGustToday);
    gauge_wind.setValueAnimated(g_wind.value);

    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip = LANG.tenminavgwind_info + ': ' + g_wind.average + ' ' + data.windunit + ' | ' +
                  LANG.maxavgwind_info + ': ' + g_wind.maxAvgToday + ' ' + data.windunit + '<br>' +
                  LANG.tenmingust_info + ': ' + g_wind.gust + ' ' + data.windunit + ' | ' +
                  LANG.maxgust_info + ': ' + g_wind.maxGustToday + ' ' + data.windunit + ' ' +
                  LANG.at + ' ' + data.TwgustTM + ' ' + LANG.bearing_info + ': ' + data.bearingTM +
                  (isNaN(parseFloat(data.bearingTM)) ? '' :  g_grad + ' (' + getord(+data.bearingTM) + ')');
        document.getElementById('imgtip6_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[6] != null) {
            document.getElementById('imgtip6_img').src = g_imgPathURL + g_tipImgs[6] + g_cacheDefeat;
        }
    }
}

function doDir() {
    g_dir.valueLatest = extractInteger(data.bearing);
    g_dir.titleLatest = LANG.latest_title;
    g_dir.valueAverage = extractInteger(data.avgbearing);
    g_dir.titleAverage = LANG.tenminavg_title;
    g_dir.bearingFrom = extractInteger(data.BearingRangeFrom10);
    g_dir.bearingTo   = extractInteger(data.BearingRangeTo10);

    gauge_wdir.setValueAnimatedAverage(g_dir.valueAverage);
    if (g_dir.valueAverage === 0) {
        g_dir.valueLatest = 0;
    }
    gauge_wdir.setValueAnimatedLatest(g_dir.valueLatest);

    var windSpd = +extractDecimal(data.wspeed);
    switch (data.windunit.toLowerCase()) {
        case 'mph':
            g_wind.avgKnots = 0.868976242 * windSpd;
            break;
        case 'kts':
            g_wind.avgKnots = windSpd;
            break;
        case 'm/s':
            g_wind.avgKnots = 1.94384449 * windSpd;
            break;
        case 'km/h':
        case 'kmh':
            g_wind.avgKnots = 0.539956803 * windSpd;
            break;
    }
    if (g_showWindVariation) {
        if (windSpd > 0) {
            if (g_wind.avgKnots < 6) {
                gauge_wdir.setSection([steelseries.Section(g_dir.bearingFrom, g_dir.bearingTo, 'rgba(220,0,0,0.2)')]);
                gauge_wdir.setArea([]);
                gauge_wdir.VRB = ' - METAR: VRB';
            } else {
                gauge_wdir.setSection([]);
                gauge_wdir.setArea([steelseries.Section(g_dir.bearingFrom, g_dir.bearingTo, 'rgba(220,0,0,0.2)')]);
                gauge_wdir.VRB = ' - METAR: ' + g_dir.bearingFrom + "V" + g_dir.bearingTo;
            }
            // If variation less than 60 degrees, then METAR = Steady
            var range = (g_dir.bearingTo < g_dir.bearingFrom ? g_dir.bearingTo + 360 : g_dir.bearingTo) - g_dir.bearingFrom;
            if (range < 60) {
                gauge_wdir.VRB = ' - METAR: STDY';
            }
        } else {
            // Zero wind speed, calm
            gauge_wdir.VRB = ' - METAR: 00000KT';
            gauge_wdir.setSection([]);
            gauge_wdir.setArea([]);
        }
    } else {
        gauge_wdir.VRB = '';
    }
    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip;
        tip = LANG.latestwind_info + ': ' + g_wind.value + ' ' + data.windunit +
            ' - ' + LANG.bearing_info + ' ' + g_dir.valueLatest +  g_grad + ' (' + getord(+g_dir.valueLatest) + ')' +
            gauge_wdir.VRB + '<br>';
        tip += LANG.tenminavgwind_info + ': ' + g_wind.average + ' ' + data.windunit +
            ' - ' + LANG.bearing_info + ' ' + g_dir.valueAverage +  g_grad + ' (' + getord(+g_dir.valueAverage) + ')';
        document.getElementById('imgtip7_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[7] != null) {
            document.getElementById('imgtip7_img').src = g_imgPathURL + g_tipImgs[7] + g_cacheDefeat;
        }
    }
}

function doUV() {
    g_uv.value = extractDecimal(data.UV);

    if (+g_uv.value === 0) {
        g_uv.risk = LANG.uv_levels[0];
        g_uv.headLine = LANG.uv_headlines[0];
        g_uv.detail = LANG.uv_details[0];
    } else if (g_uv.value < 3) {
        g_uv.risk = LANG.uv_levels[1];
        g_uv.headLine = LANG.uv_headlines[1];
        g_uv.detail = LANG.uv_details[1];
    } else if (g_uv.value < 6){
        g_uv.risk = LANG.uv_levels[2];
        g_uv.headLine = LANG.uv_headlines[2];
        g_uv.detail = LANG.uv_details[2];
    } else if (g_uv.value < 8) {
        g_uv.risk = LANG.uv_levels[3];
        g_uv.headLine = LANG.uv_headlines[3];
        g_uv.detail = LANG.uv_details[3];
    } else if (g_uv.value < 11) {
        g_uv.risk = LANG.uv_levels[4];
        g_uv.headLine = LANG.uv_headlines[4];
        g_uv.detail = LANG.uv_details[4];
    } else {
        g_uv.risk = LANG.uv_levels[5];
        g_uv.headLine = LANG.uv_headlines[5];
        g_uv.detail = LANG.uv_details[5];
    }

    gauge_uv.setValueAnimated(g_uv.value );
    gauge_uv.setUnitString(g_uv.risk);

    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip;
        tip = '<b>' + LANG.uv_title + ': ' + g_uv.value + '</b><br>';
        tip += '<i>' + g_uv.headLine + '</i><br>';
        tip += g_uv.detail;
        document.getElementById('imgtip8_txt').innerHTML = tip;
        if (g_refreshGraphs && g_showPopupDataGraphs && g_tipImgs[8] != null) {
            document.getElementById('imgtip8_img').src = g_imgPathURL + g_tipImgs[8] + g_cacheDefeat;
        }
    }
}

function doSolar() {
    g_solar.value = +extractInteger(data.SolarRad);
    g_solar.currMaxValue = +extractInteger(data.CurrentSolarMax);
    // Set a section to show current theoretical max value
    if (g_solar.value < g_solar.currMaxValue) {
        gauge_solar.setArea([steelseries.Section(g_solar.value, g_solar.currMaxValue, 'rgba(0,220,0,0.2)')]);
    } else {
        gauge_solar.setArea([steelseries.Section(g_solar.currMaxValue, g_solar.value, 'rgba(220,0,0,0.2)')]);
    }
    // Set the value
    gauge_solar.setValueAnimated(g_solar.value);
    // If we have today's max radiation value (WD only) then show it
    if (data.SolarTM !== undefined) {
        g_solar.maxToday = extractInteger(data.SolarTM);
        gauge_solar.setMaxMeasuredValue(g_solar.maxToday);
    }

    if (ddimgtooltip.showTips) {
        // update tooltip
        var tip;
        var percent = (+g_solar.currMaxValue === 0 ? '--' : Math.round(+g_solar.value / +g_solar.currMaxValue * 100));

        tip = '<b>' + LANG.solar_title + ': ' + g_solar.value + ' W/m2</b><br>' +
              '<i>' + percent + '% ' + LANG.solar_ofMax + '</i><br>' +
              LANG.solar_currentMax + ': ' + g_solar.currMaxValue + ' W/m2';
        if (data.SolarTM !== undefined) {
            tip += '<br>' + LANG.solar_maxToday + ': ' + g_solar.maxToday + ' W/m2';
        }
        document.getElementById('imgtip9_txt').innerHTML = tip;
    }
}

function doFirst() {
    if (data.tempunit.indexOf('F') !== -1) {
        g_temp.minValue = 30;
        g_temp.maxValue = 100;
        g_temp.value = 30;
        g_temp.sections = createTempSections(30, false);
        drawTemp();

        g_dew.minValue = 30;
        g_dew.maxValue = 100;
        g_dew.value = 30;
        g_dew.sections = createTempSections(30, false);
        drawDew();
    }

    if (data.pressunit.toLowerCase() !== 'hpa') {
        if (data.pressunit.toLowerCase() === 'inhg') {
            g_baro.minValue = 29.2;
            g_baro.maxValue = 30.4;
            g_baro.value = 29.2;
            g_baro.lcdDecimals = 2;
            g_baro.scaleDecimals = 1;
            g_baro.labelNumberFormat = steelseries.LabelNumberFormat.FRACTIONAL;
        }
        drawBaro();
    }

    switch (data.windunit.toLowerCase()) {
        case 'mph':
            break;
        case 'kts':
            drawWind();
            break;
        case 'm/s':
            g_wind.maxValue = 10;
            drawWind();
            break;
        default: // km/h
            g_wind.maxValue = 30;
            drawWind();
    }

    if (data.rainunit.toLowerCase() === 'in') {
        g_rain.maxValue = 1.0;
        g_rain.lcdDecimals = 2;
        g_rain.scaleDecimals = 1;
        g_rain.labelNumberFormat = steelseries.LabelNumberFormat.FRACTIONAL;
        g_rain.sections = (g_rainUseSectionColours ? createRainfallSections(false) : null);
        g_rain.gradient = (g_rainUseGradientColours ? createRainfallGradient(false) : null);
        drawRain();

        g_rrate.maxValue = 1.0;
        g_rrate.lcdDecimals = 2;
        g_rrate.scaleDecimals = 1;
        g_rrate.labelNumberFormat = steelseries.LabelNumberFormat.FRACTIONAL;
        drawRRate();
    }

    if (g_showSolarGauge && data.SolarTM !== undefined) {
        gauge_solar.setMaxMeasuredValueVisible(true);
    }
    // has a page timeout over ride password been supplied?
    g_pageUpdateParam = gup('pageUpdate');
    
    g_firstRun = false;
}

function createTempSections(min, celsius) {
    var section;
    if (celsius) {
        switch (min) {
            case  -40:
                section = [
                    steelseries.Section(-40, -15, 'rgba(0, 0, 200, 0.3)'),
                    steelseries.Section(-15, 0, 'rgba(20, 20, 200, 0.3)')
                ];
                break;
            case  -20:
                section = [
                    steelseries.Section(-20, -15, 'rgba(0, 0, 200, 0.3)'),
                    steelseries.Section(-15, 0, 'rgba(20, 20, 200, 0.3)'),
                    steelseries.Section(0, 10, 'rgba(50, 175, 125, 0.3)'),
                    steelseries.Section(10, 20, 'rgba(0, 220, 0, 0.3)')
                ];
                break;
            case 0:
                section = [
                    steelseries.Section(0, 10, 'rgba(50, 175, 125, 0.3)'),
                    steelseries.Section(10, 30, 'rgba(0, 220, 0, 0.3)'),
                    steelseries.Section(30, 40, 'rgba(220, 220, 0, 0.3)')
                ];
                break;
            case 20:
                section = [
                    steelseries.Section(20, 30, 'rgba(0, 220, 0, 0.3)'),
                    steelseries.Section(30, 45, 'rgba(220, 220, 0, 0.3)'),
                    steelseries.Section(45, 60, 'rgba(255, 0, 0, 0.3)')
                ];
                break;
        }
    } else {
        switch (min) {
            case  -30:
                section = [
                    steelseries.Section(-30, 15, 'rgba(0, 0, 200, 0.5)'),
                    steelseries.Section(15, 32, 'rgba(20, 20, 200, 0.3)'),
                    steelseries.Section(32, 40, 'rgba(100, 100, 220, 0.3)')
                ];
                break;
            case 0:
                section = [
                    steelseries.Section(0, 15, 'rgba(0, 0, 200, 0.5)'),
                    steelseries.Section(15, 32, 'rgba(20, 20, 200, 0.3)'),
                    steelseries.Section(32, 50, 'rgba(100, 100, 220, 0.3)'),
                    steelseries.Section(50, 70, 'rgba(0, 220, 0, 0.3)')
                ];
                break;
            case 30:
                section = [
                    steelseries.Section(30, 32, 'rgba(20, 20, 200, 0.3)'),
                    steelseries.Section(32, 50, 'rgba(100, 100, 220, 0.3)'),
                    steelseries.Section(50, 85, 'rgba(0, 220, 0, 0.3)'),
                    steelseries.Section(85, 100, 'rgba(220, 220, 0, 0.3)')
                ];
                break;
            case 60:
                section = [
                    steelseries.Section(60, 85, 'rgba(0, 220, 0, 0.3)'),
                    steelseries.Section(85, 110, 'rgba(220, 220, 0, 0.3)'),
                    steelseries.Section(110, 130, 'rgba(255, 0, 0, 0.3)')
                ];
                break;
        }
    }
    return section;
}

// Returns an array of section highlights for the Rain Rate gauge
// Assumes 'standard' descriptive limits from UK met office:
// < 0.25 mm/hr - Very light rain
// 0.25mm/hr to 1.0mm/hr - Light rain
// 1.0 mm/hr to 4.0 mm/hr - Moderate rain
// 4.0 mm/hr to 16.0 mm/hr - Heavy rain
// 16.0 mm/hr to 50 mm/hr - Very heavy rain
// > 50.0 mm/hour - Extreme rain
//
// Roughly translated to the corresponding Inch rates
// < 0.001
// 0.001 to 0.05
// 0.05 to 0.20
// 0.20 to 0.60
// 0.60 to 2.0
// > 2.0
function createRainRateSections(max, metric) {
    var section;

     if (metric) {
        section = [
            steelseries.Section(0, 0.25, 'rgba(0, 140, 0, 0.9)'),
            steelseries.Section(0.25, 1, 'rgba(80, 192, 80, 0.8)'),
            steelseries.Section(1, 4, 'rgba(150, 203, 150, 0.9)')
            ];
    } else {
        section = [
            steelseries.Section(0, 0.001, 'rgba(0, 140, 0, 0.9)'),
            steelseries.Section(0.001, 0.05, 'rgba(80, 192, 80, 0.8))'),
            steelseries.Section(0.05, 0.2, 'rgba(150, 203, 150, 0.9)')
            ];
    }

    if (metric) {
        if (max > 4) {
            if (max < 16)
                section.push(steelseries.Section(4, max, 'rgba(212, 203, 109, 0.8)'));
            else
                section.push(steelseries.Section(4, 16, 'rgba(212, 203, 109, 0.8)'));
        }
        if (max > 16) {
            if (max < 50)
                section.push(steelseries.Section(16, max, 'rgba(225, 155, 105, 0.8)'));
            else
                section.push(steelseries.Section(16, 50, 'rgba(225, 155, 105, 0.8)'));
        }
        if (max > 50) {
            section.push(steelseries.Section(50, max, 'rgba(245, 86, 59, 0.8)'));
        }
    } else { //imperial
        if (max > 0.2) {
            if (max < 0.6)
                section.push(steelseries.Section(0.2, max, 'rgba(212, 203, 109, 0.8)'));
            else
                section.push(steelseries.Section(0.2, 0.6, 'rgba(212, 203, 109, 0.8)'));
        }
        if (max > 0.6) {
            if (max < 2)
                section.push(steelseries.Section(0.6, max, 'rgba(225, 155, 105, 0.8)'));
            else
                section.push(steelseries.Section(0.6, 2, 'rgba(225, 155, 105, 0.8)'));
        }
        if (max > 2) {
            section.push(steelseries.Section(2, max, 'rgba(245, 86, 59, 0.8)'));
        }
    }
    return section;
}

function createRainfallSections(metric) {
    var section;

    if (metric) {
        section = [ steelseries.Section(0, 5, 'rgba(0, 250, 0, 1)'),
                    steelseries.Section(5, 10, 'rgba(0, 250, 117, 1)'),
                    steelseries.Section(10, 25, 'rgba(218, 246, 0, 1)'),
                    steelseries.Section(25, 40, 'rgba(250, 186, 0, 1)'),
                    steelseries.Section(40, 50, 'rgba(250, 95, 0, 1)'),
                    steelseries.Section(50, 65, 'rgba(250, 0, 0, 1)'),
                    steelseries.Section(65, 75, 'rgba(250, 6, 80, 1)'),
                    steelseries.Section(75, 100, 'rgba(205, 18, 158, 1)'),
                    steelseries.Section(100, 125, 'rgba(0, 0, 250, 1)'),
                    steelseries.Section(125, 500, 'rgba(0, 219, 212, 1)')];
    } else {
        section = [ steelseries.Section(0, 0.2, 'rgba(0, 250, 0, 1)'),
                    steelseries.Section(0.2, 0.5, 'rgba(0, 250, 117, 1)'),
                    steelseries.Section(0.5, 1, 'rgba(218, 246, 0, 1)'),
                    steelseries.Section(1, 1.5, 'rgba(250, 186, 0, 1)'),
                    steelseries.Section(1.5, 2, 'rgba(250, 95, 0, 1)'),
                    steelseries.Section(2, 2.5, 'rgba(250, 0, 0, 1)'),
                    steelseries.Section(2.5, 3, 'rgba(250, 6, 80, 1)'),
                    steelseries.Section(3, 4, 'rgba(205, 18, 158, 1)'),
                    steelseries.Section(4, 5, 'rgba(0, 0, 250, 1)'),
                    steelseries.Section(5, 20, 'rgba(0, 219, 212, 1)')];
    }
    return section;
}

function createRainfallGradient(metric) {
    var grad = new steelseries.gradientWrapper( 0,
                                                (metric ? 100 : 4),
                                                [ 0, 0.1, 0.62, 1],
                                                [ new steelseries.rgbaColor( 15, 148,  0, 1),
                                                  new steelseries.rgbaColor(213, 213,  0, 1),
                                                  new steelseries.rgbaColor(213,   0, 25, 1),
                                                  new steelseries.rgbaColor(250,   0,  0, 1)] );
    return grad;
}


// Kick it all off
$(document).ready(function() {
    init();
});

//----- Helper functions ------

function getord(d) {
    //convert to range 0-360
    while (d >= 348.75) {
        d -= 360;
    }
    var deg = Math.ceil((d + 11.25) / 22.5);
    return (LANG.coords[deg - 1]);
}

String.prototype.trim = String.prototype.trim || function trim() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); };

function gup(paramName) {
    paramName = paramName.replace(/(\[|\])/g,"\\$1");
    var regexS = "[\\?&]"+paramName+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results === null )
        return "";
    else
        return results[1];
}

function extractDecimal(str) {
    return str.replace(/([+-]?[0-9]+)[,.]([0-9]+).*/, '$1.$2');
}

function extractInteger(str) {
    return str.replace(/([0-9]+).*/,'$1');
}

function tempTrend(trend, units, bTxt) {
    var val = trend * (units.indexOf('C') ? 3 : 1.5);
    // Simple halving of values for farenheit, scale is over 3 hours
    var ret;
    if (val > 5) {
        ret = (bTxt ? LANG.RisingVeryRapidly : 1);
    } else if (val > 3) {
        ret = (bTxt ? LANG.RisingQuickly : 1);
    } else if (val > 1) {
        ret = (bTxt ? LANG.Rising : 1);
    } else if (val > 0.5) {
        ret = (bTxt ? LANG.RisingSlowly : 1);
    } else if (val >= -0.5) {
        ret = (bTxt ? LANG.Steady : 0);
    } else if (val >= -1) {
        ret = (bTxt ? LANG.FallingSlowly : -1);
    } else if (val >= -3) {
        ret = (bTxt ? LANG.Falling : -1);
    } else if (val >= -5) {
        ret = (bTxt ? LANG.FallingQuickly : -1);
    } else {
        ret = (bTxt ? LANG.FallingVeryRapidly : -1);
    }
    return ret;
}

function baroTrend(trend, units, bTxt) {
    var val = trend * 3, ret;
    // The terms below are the UK Met Office terms for a 3 hour change in hPa
    // trend is supplied as an hourly change, so multiply by 3
    if (units === 'in' || units === 'inHg') {
        // assume everything else is hPa or mb, could be dangerous!
        val *= 33.8639;
    } else if (units === 'kPa') {
        val *= 10;
    }
    if (val > 6.0) {
        ret = (bTxt ? LANG.RisingVeryRapidly : 1);
    } else if (val > 3.5) {
        ret = (bTxt ? LANG.RisingQuickly : 1);
    } else if (val > 1.5) {
        ret = (bTxt ? LANG.Rising : 1);
    } else if (val > 0.1) {
        ret = (bTxt ? LANG.RisingSlowly : 1);
    } else if (val >= -0.1) {
        ret = (bTxt ? LANG.Steady : 0);
    } else if (val >= -1.5) {
        ret = (bTxt ? LANG.FallingSlowly : -1);
    } else if (val >= -3.5) {
        ret = (bTxt ? LANG.Falling : -1);
    } else if (val >= -6.0) {
        ret = (bTxt ? LANG.FallingQuickly : -1);
    } else {
        ret = (bTxt ? LANG.FallingVeryRapidly : -1);
    }
    return ret;
}