// <![CDATA[
// Load changing weathervalues into fields on screen, colorize changed values for some seconds
// Total rewrite from WD-tags to general tags, no translation needed (done on server)
// Version 3.01 date 2015-04-21 
// -- begin settings --------------------------------------------------------------------------
var flashcolor 	        = '#00CC00';		// color to flash for changed observations RGB
var flashtime  	        = 5000; 		// miliseconds to keep flash color on (5000 = 5 seconds);
//var reloadTime 	= 10000;     		// reload AJAX conditions every 30 seconds (= 30000 ms) set to min ftp time
var doTooltip 	        = 0;         		// set to 1 to have ajaxed variable names appear as tooltips (except for graphics)
var wsRequest 	        = false;		// to communicate with server to get the field-data
var ajaxVars            = new Array();
var errorEval;
var firsttime           = 'y';			// do not change color at initial load
var counterSecs         = 0;
var countUpdates        = 0;			// number of times a new datafiles was tried to get / uploaded
var countUpdatesMax     = 0;			// max number of files to load before pause and full reload is needed

function ws_ajax_request () {
	wsRequest = false;                      // find the handler for AJAX based on availability of the wsRequest object
	try { wsRequest = new XMLHttpRequest()}          // non IE browser (or IE8 native)  
	catch(e1) {
		try { wsRequest = ActiveXObject("Msxml2.XMLHTTP")               /* try IE6+ */ }
		catch(e2) {
			try { wsRequest = ActiveXObject("Microsoft.XMLHTTP")    /* try IE5 */}
			catch(e3) // no Ajax support
			{ wsRequest = false; alert('Sorry.. AJAX updates are not available for your browser.') }
		}
	}
	if (! wsRequest) { countUpdatesMax = 1; }
	countUpdates++; 
	return wsRequest;
}  		// eof ws_ajax_request find the handler for AJAX based on availability of the wsRequest object

function load_data(wsUrl) {                     // wsRequest the data form the server
	var d=new Date();
	var t=d.getTime();
        if (wsRequest) {  // is there a usable object
                wsRequest.open('get', wsUrl + '&U=' + countUpdates + '&t=' + t);
                // define which function handles the returned data:
                wsRequest.onreadystatechange = process_data;
                // send the wsRequest to the server:
                wsRequest.send(null);
        } else { 
    	        alert('Sorry.. AJAX updates are not available for your browser.');
        }
}   		// eof load_data: wsRequest the data form the server

function process_data() {
    if ((wsRequest.readyState == 4) && (wsRequest.status == 200) ) { 
    	setTimeout("execute_data()", 1000); 
    }
}

function execute_data() {                        // Everything ok thus eval the string with
	eval (wsRequest.responseText);
	if  (undefined == ajaxVars['ajaxdate']) {
		set_ajax_obs("gizmoindicator",'Error in data received, reload page to start');
		errorEval = true;		
	}
	if  (! errorEval == true) {
		set_ajax_obs("ajaxdate",ajaxVars['ajaxdate']);
		set_ajax_obs("ajaxtime",ajaxVars['ajaxtime']);
		set_ajax_obs("ajaxtemp", ajaxVars['ajaxtemp']);
		set_ajax_obs("ajaxtempNoU",ajaxVars['ajaxtempNoU']);
		set_ajax_obs("ajaxbigtemp", ajaxVars['ajaxbigtemp']);
		set_ajax_obs("ajaxtempDash", ajaxVars['ajaxtempDash']);
		set_ajax_obs("ajaxtemparrow",ajaxVars['ajaxtemparrow']);
		set_ajax_obs("ajaxconditionicon",ajaxVars['ajaxconditionicon']);
		set_ajax_obs("ajaxcurrentcond",ajaxVars['ajaxcurrentcond']);
		set_ajax_obs("ajaxthermometer",ajaxVars['ajaxthermometer']);
		set_ajax_obs("ajaxheatcolorword",ajaxVars['ajaxheatcolorword']);
		set_ajax_obs("ajaxfeelslike",ajaxVars['ajaxfeelslike']);
		set_ajax_obs("ajaxtempmax",ajaxVars['ajaxtempmax']);
		set_ajax_obs("ajaxtempmaxTime",ajaxVars['ajaxtempmaxTime']);
		set_ajax_obs("ajaxtempmin",ajaxVars['ajaxtempmin']);
		set_ajax_obs("ajaxtempminTime",ajaxVars['ajaxtempminTime']);
		set_ajax_obs("ajaxrainNoU",ajaxVars['ajaxrainNoU']);
		set_ajax_obs("ajaxrain",ajaxVars['ajaxrain']);
		set_ajax_obs("ajaxrainratehr",ajaxVars['ajaxrainratehr']);
		set_ajax_obs("ajaxrainmo",ajaxVars['ajaxrainmo']);
		set_ajax_obs("ajaxrainyr",ajaxVars['ajaxrainyr']);
		set_ajax_obs("ajaxwindiconwr",ajaxVars['ajaxwindiconwr']);
		set_ajax_obs("ajaxwinddir",ajaxVars['ajaxwinddir']);
		set_ajax_obs("ajaxwinddirNoU",ajaxVars['ajaxwinddirNoU']);
		set_ajax_obs("ajaxwind",ajaxVars['ajaxwind']);
		set_ajax_obs("ajaxwindNoU",ajaxVars['ajaxwindNoU']);
		set_ajax_obs("ajaxgust",ajaxVars['ajaxgust']);
		set_ajax_obs("ajaxgustNoU",ajaxVars['ajaxgustNoU']);
		set_ajax_obs("ajaxbeaufortnum",ajaxVars['ajaxbeaufortnum']);
		set_ajax_obs("ajaxbeaufort",ajaxVars['ajaxbeaufort']);
		set_ajax_obs("ajaxwindmaxgust",ajaxVars['ajaxwindmaxgust']);
		set_ajax_obs("ajaxwindmaxgusttime",ajaxVars['ajaxwindmaxgusttime']);
		set_ajax_obs("ajaxhumidity",ajaxVars['ajaxhumidity']);
		set_ajax_obs("ajaxhumidityNoU",ajaxVars['ajaxhumidityNoU']);
		set_ajax_obs("ajaxhumidityarrow",ajaxVars['ajaxhumidityarrow']);
		set_ajax_obs("ajaxdew",ajaxVars['ajaxdew']);
		set_ajax_obs("ajaxdewarrow",ajaxVars['ajaxdewarrow']);
		set_ajax_obs("ajaxbaro",ajaxVars['ajaxbaro']);
		set_ajax_obs("ajaxbaroNoU",ajaxVars['ajaxbaroNoU']);
		set_ajax_obs("ajaxbaroarrow",ajaxVars['ajaxbaroarrow']);
		set_ajax_obs("ajaxbarotrendtext",ajaxVars['ajaxbarotrendtext']);
		set_ajax_obs("ajaxsolar",ajaxVars['ajaxsolar']);
		set_ajax_obs("ajaxsolarpct",ajaxVars['ajaxsolarpct']);
		set_ajax_obs("ajaxsolarmax",ajaxVars['ajaxsolarmax']);
		set_ajax_obs("ajaxsolarmaxtime",ajaxVars['ajaxsolarmaxtime']);
		set_ajax_obs("ajaxuv",ajaxVars['ajaxuv']);
		set_ajax_obs("ajaxuvword",ajaxVars['ajaxuvword']);
		set_ajax_obs("ajaxuvmax",ajaxVars['ajaxuvmax']);
		set_ajax_obs("ajaxuvmaxtime",ajaxVars['ajaxuvmaxtime']);
		set_ajax_obs("gizmodate",ajaxVars['ajaxdate']);
		set_ajax_obs("gizmotime",ajaxVars['ajaxtime']);
		set_ajax_obs("gizmocurrentcond",ajaxVars['ajaxcurrentcondalt']);
		set_ajax_obs("gizmotemp",ajaxVars['ajaxtemp']);
		set_ajax_obs("gizmotempNoU",ajaxVars['ajaxtempNoU']);
		set_ajax_obs("gizmotemparrow",ajaxVars['ajaxtemparrow']);
		set_ajax_obs("gizmohumidity",ajaxVars['ajaxhumidity']);
		set_ajax_obs("gizmohumidityarrow",ajaxVars['ajaxhumidityarrow']);
		set_ajax_obs("gizmodew",ajaxVars['ajaxdew']);
		set_ajax_obs("gizmowindicon",ajaxVars['gizmowindicon']);
		set_ajax_obs("gizmowinddirNoU",ajaxVars['ajaxwinddirNoU']);
		set_ajax_obs("gizmowind",ajaxVars['ajaxwind']);
		set_ajax_obs("gizmowindNoU",ajaxVars['ajaxwindNoU']);
		set_ajax_obs("gizmogust",ajaxVars['ajaxgust']);	
		set_ajax_obs("gizmobaro",ajaxVars['ajaxbaro']);
		set_ajax_obs("gizmobaroarrow",ajaxVars['ajaxbaroarrow']);
		set_ajax_obs("gizmobaroNoU",ajaxVars['ajaxbaroNoU']);
		set_ajax_obs("gizmobarotrendtext",ajaxVars['ajaxbarotrendtext']);	
		set_ajax_obs("gizmorain",ajaxVars['ajaxrain']);
		set_ajax_obs("gizmorainNoU",ajaxVars['ajaxrainNoU']);
		set_ajax_obs("gizmouv",ajaxVars['ajaxuv']);
		set_ajax_obs("gizmouvword",ajaxVars['ajaxuvword']);
	} // eo proces vars
	firsttime = 'n';
        setTimeout("reset_ajax_color('')",flashtime); // change text back to default color 
        counterSecs = 0;
        if ((countUpdatesMax > 0) && (countUpdates > countUpdatesMax-1) ) { // chg indicator to pause message 
    	        if (undefined === ajaxVars['ajaxdate']) {
			set_ajax_obs("ajaxindicator",'No data received - reload page to start');
			set_ajax_obs("gizmoindicator",'No data received - reload page to start'); 
		} else {
			set_ajax_obs("ajaxindicator",ajaxVars['langPauseMsg']);
			set_ajax_obs("gizmoindicator",ajaxVars['langPauseMsg']);
		}  // eo chg indicator to pause message
	} else {
		ws_ajax_request ();							 // load again after wait of so many milliseconds
		setTimeout("load_data(wsUrl)", reloadTime); } 
}   	// eof  process_data: put every piece of data in the right place

function get_content_tags (tag) {               // search all the span tags and return the list with class=tag 
        if (!tag) var tag = 'ajax';                   // default look for ajax tag
/*  if (ie4 && browser != "Opera" && ! ie8) {
    var elem = document.getElementsByTagName('span');
	var lookfor = 'className';
  } else { */
  
        var elem = document.getElementsByTagName('span');
        var lookfor = 'class';

/*  }  */
        var arr = new Array();
        var i = 0;
        var iarr = 0;
        for(i = 0; i < elem.length; i++) {
                var att = elem[i].getAttribute(lookfor);
                if(att == tag) {
                        arr[iarr] = elem[i];
                        iarr++;
                }
        }
        return arr;
}        // eof get_content_tags: search all the span tags and return the list with class=tag 

function reset_ajax_color( usecolor ) {
// reset all the <span class="ajax"...> styles to have no color override
      var elements = get_content_tags('ajax');
	  var numelements = elements.length;
	  for (var index=0;index!=numelements;index++) {
         var element = elements[index];
	     element.style.color=usecolor;
      }
}  // eof reset_ajax_color: reset all the <span class="ajax"...> styles to have no color override

function set_ajax_obs( name, inValue ) {
// store away the current value in both the doc and the span as lastobs="value"
// change color if value != lastobs
        var value = inValue;
		var element = document.getElementById(name);
		if (! element ) { return; } // V1.04 -- don't set if missing the <span id=name> tag
		var lastobs = element.getAttribute("lastobs");		
		element.setAttribute("lastobs",value);
		if (value != unescape(lastobs)) {
          if ( firsttime == 'n' ) {element.style.color=flashcolor;}
		  if ( doTooltip ) { element.setAttribute("title",'AJAX tag '+name); }
		  element.innerHTML =  value; // moved inside to fix flashing issue (Jim at jcweather.us)
		}
} // eof  set_ajax_obs: // store current value and set color when value has changed

function ajax_countup() {
// Mike Challis' counter function (adapted by Ken True)
//
 var element = document.getElementById("ajaxcounter");
 if (element) {
  element.innerHTML = counterSecs;
  counterSecs++;
 }
}  				 // count updated so many secs ago

window.setInterval("ajax_countup()", 1000);  // run the counter for seconds since update

wsRequest = ws_ajax_request ();							 // load for the first time after wait of so many milliseconds
setTimeout("load_data(wsUrl)", reloadTime);	 //	
	
// ]]>