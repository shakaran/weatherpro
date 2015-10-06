<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'ws_knmi_fct.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.02 2015-04-04';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-10-07 release version
# 3.01 2014-10-08 removed cache error
# 3.02 2015-04-04 release 2.7 version - validator OK 
# ----------------------------------------------------------------------
# settings:
$knmi_compact   = true;                 // if you cahnge this wait for the cache time or remove cachefile
$page_title     = 'KNMI Weersverwachting';
$cache_dir      = $SITE['cacheDir'];
$knmi_cached     = $cache_dir.'knmi-forecast';
$cache_allow    = 3600;                 // 1 uur = 3600 secondes
$url            = 'ftp.knmi.nl/pub_weerberichten/weeroverzicht.xml';
$load_file      = false;
$verwachting    = '<h3>Verwachting kon niet worden opgehaald</h3>';
$cache_string   = '';
?>
<!-- <style>
.hoofdkop       {width: 100%; font-size: 120%; font-weight: bold; text-align: center;}
.hoofdkop i     {color: grey;}
.alineakop      {background-color: grey;}
</style>  -->
<?php
if (!file_exists($knmi_cached)){ 
        $cache_string   .="<!-- $page_title ($knmi_cached) not found in cache -->".PHP_EOL;
        $load_file      = true;
} else {
        $file_time      = filemtime($knmi_cached);
        $now            = time();
        $diff           = $now  - $file_time;
        $cache_string   .=  "<!-- $page_title ($knmi_cached)
cache time      = ".date('c',$file_time)." from unix time $file_time
current time    = ".date('c',$now)." from unix time $now 
difference      = $diff (seconds)
diff allowed    = $cache_allow (seconds) -->".PHP_EOL;	
        if ($diff <= $cache_allow){
                $cache_string   .= "<!-- $page_title ($knmi_cached) loaded from cache -->".PHP_EOL;
                $verwachting    =  file_get_contents($knmi_cached);
        } else {
                $cache_string   .= "<!-- $page_title ($knmi_cached) cache to old, load fresh one -->".PHP_EOL;
                $load_file      = true;
        }
}

if ( isset ($_REQUEST['force']) && trim(strtolower($_REQUEST['force'])) == 'knmi') {$load_file = true;}
if ($load_file){
        $rawData        = knmi_curl();
        if (empty($rawData)){
                $load_file      = false;        // no new data loaded
                $cache_string   .= "<!-- ERROR $page_title retrieved data ($url) empty -->".PHP_EOL;
                $cache_string   .= "<!-- Try with double cache time -->".PHP_EOL;
                if ($diff <= 2 * $cache_allow){
                        $cache_string   .= "<!-- $page_title ($knmi_cached) loaded from cache -->".PHP_EOL;
                        $verwachting    =  file_get_contents($knmi_cached);
                        $load_file      = false; 
                } // used old cache 
        } // eo url not OK - no raw data
}
echo  $cache_string;            // echo all message until this point
if (!$load_file){               // old data still OK or no data retieved at all
?>
<!-- KNMI Weersverwachting -->
<div class="blockDiv">
<?php echo $verwachting; ?>
</div>
<!-- end of KNMI Weersverwachting -->
<?php
        return;
}

# now we have to process the raw data
$xml            = new SimpleXMLElement($rawData);
# <data> <location> 
#        <block> <field_id>Kop</field_id>               <field_content>max. 50 karakters </field_content></block>
#        <block> <field_id>Kort</field_id>              <field_content>max. 150 karakters</field_content></block>
#        <block> <field_id>Verwachting</field_id>       <field_content>Het is  . . .    </field_content></block>
#        <block> <field_id>Zonneschijn</field_id>       <field_content>         20      </field_content></block> // %
#        <block> <field_id>Neerslagkans</field_id>      <field_content>         80      </field_content></block> // %
#        <block> <field_id>Neerslaghoeveelheid</field_id><field_content>        1/5     </field_content></block> // mm
#        <block> <field_id>Minimumtemperatuur</field_id><field_content>         8       </field_content></block>
#        <block> <field_id>Middagtemperatuur</field_id> <field_content>         17      </field_content></block>
#        <block> <field_id>Windrichting</field_id>      <field_content>         Z       </field_content></block>
#        <block> <field_id>Windkracht</field_id>        <field_content>         4       </field_content></block> // bft
#  </location> </data>
$count          = count ($xml->data->location->block);

#echo '<pre>count = '.$count.PHP_EOL; 
for ($i = 0; $i < $count; $i++) {
        $field          = $xml->data->location->block[$i];
        $field_id       = (string) $field->field_id;
        $field_id       = strtolower (trim ($field_id));
        $$field_id      = (string) $field->field_content;
}

$extra  = $knmi_click   = '';
$bottom = '<p style="text-align: center;">Zonneschijn: '.$zonneschijn.'%'.
' - Neerslag: '.$neerslagkans.'% kans op '.$neerslaghoeveelheid.' mm'.
' - Temperatuur van '.$minimumtemperatuur.' tot '.$middagtemperatuur.'&deg;C'.
' - Wind uit het '.$windrichting.' '.$windkracht.'bft';
if ($knmi_compact){
        $extra          = 'display: none; '; 
        $knmi_click     = '<a href="javascript:knmiclick()">'.
'<img src="./img/i_symbolWhite.png" alt=" " style="vertical-align: bottom; padding: 2px; width: 14px;">'.
'</a>
<script type="text/javascript">
  function knmiclick() {
        hideshow(document.getElementById(\'knmiExtra\'))
        hideshow(document.getElementById(\'knmiExtra2\'))
        }
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>'.PHP_EOL;}

$from           = PHP_EOL.PHP_EOL;
$to             = '</p><br /><p>';

$verwachting    = '<div id="knmiExtra2" style="display: block; text-align: center;">'.$kort.'</div>
<div id="knmiExtra" style="'.$extra.' width: 95%; margin: 0 auto;">
<p>'.str_replace ($from, $to, $verwachting).'</p><br /></div>';
$verwachting    = '<h3 class="blockHead">'.$page_title.': '.$kop.$knmi_click.'</h3>'.
$verwachting.PHP_EOL.$bottom;

if (file_put_contents($knmi_cached, $verwachting) ){   
        $cache_string   .= "<!--  $page_title ($knmi_cached) saved to cache  -->".PHP_EOL;
        $load_file      = false;        // new data saved
} else {
        $cache_string   .= "<!-- ERROR $page_title retrieved data ($url) could not be save to cache -->".PHP_EOL;
}
function knmi_curl() {
        global  $url, $page_title, $cache_string, $knmi_cached;
        $cache_string   .= "<!-- $page_title ($knmi_cached) trying to load using CURL   -->".PHP_EOL;
        $ch = curl_init();
        curl_setopt     ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt     ($ch, CURLOPT_URL, $url);
        curl_setopt     ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt     ($ch, CURLOPT_TIMEOUT, 10);
        $rawData        = curl_exec ($ch);
        curl_close      ($ch);
        return  $rawData;       
}
?>
<!-- KNMI Weersverwachting -->
<div class="blockDiv">
<?php echo $verwachting; ?>
</div>
<!-- end of KNMI Weersverwachting -->

