// <![CDATA[
// ----------------------------------------------
//
//   NWS Public Alerts
//
//   Set a cookie to see if the user has 
//   javascript enabled
//
// ----------------------------------------------
// --- Version 1.00 - 28-July-2012 -- Set cookie by javascript
// --- Version 1.01 - 14-Aug-2012 -- Changed cookie expiration time
 
var expdate = new Date ();
expdate.setTime (expdate.getTime() + (24 * 60 * 60 * 30)); 
function setCookie(name, value, expires, path, domain, secure) {
 var thisCookie = name + "=" + escape(value) +
 ((expires) ? "; expires=" + expires.toGMTString() : "") +
 ((path) ? "; path=" + path : "") +
 ((domain) ? "; domain=" + domain : "") +
 ((secure) ? "; secure" : "");
 document.cookie = thisCookie;
}
setCookie ("NWSalerts", 'true', expdate)
// ]]>
