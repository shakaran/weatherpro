<?php 
require 'lib/Util.php';

Util::checkShowSource();

$pageName	= 'cron_job.php';
$pageVersion	= '3.20 2015-07-26';
#-------------------------------------------------------------------------------
# 3.20 2015-07-26 release 2.8 version
#-------------------------------------------------------------------------------
#
# this scripts loads the standard index.php => startpage surpessing all output
#
# When frequently run the nws alerts (region america) can be loaded so that users have no wait time
#
# VERSION 2.8	some scripts are adapted to use slightly less cache time (- 360 seconds) so that other heavy data loads (meteoalarm) will also be retrieved
#
#-------------------------------------------------------------------------------
#
$start_cron_job = microtime(true);		// just to have an idea of the time spent (mostly waiting on URL access
#
ob_start();					// to discard all html output later
$cron_nws       = true;         		// for nws alerts 
$cron_all       = true;				// for all others
#
include 'index.php';				// load default start-page
#
$seconds	= microtime(true) - $start_cron_job;		// time spent
#
ob_clean ();					// discard all html
#
echo "succes - seconds spent: ".$seconds;	// message for calling cron manager. SHOULD start with succes
#
#  extra messages for NWS (america) suers
#
if ($cron_nws  && isset ($SITE['useCurly']) && ($SITE['useCurly'] == true) ) {
        $text           = '<br />'.PHP_EOL;
        $nws_filemtime  = filemtime($save_nws_filename);
        if ($save_nws_filemtime <> $nws_filemtime) {
                $text   .= 'changed from ';
                $text   .= date ($SITE['timeFormat'],$save_nws_filemtime);
                $text   .= ' to ';
        }
        else  { $text   .= 'no change in settings (updatetime = '.$save_nws_updateTime.') filetime : ';
        }
        $filetime       = filemtime($save_nws_filename);
        $text           .= date ($SITE['timeFormat'],$filetime);
        if (isset ($cron_string) ) {echo '<br />'.$cron_string;}
        echo $text;
}