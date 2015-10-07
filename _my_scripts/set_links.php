<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'set_links.php'; 
$pageVersion	= '3.20 2015-07-15'; 
#-----------------------------------------------------------------------
# 3.20 2015-07-15 release 2.8 version
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
$now            = time(); 
$utc_date       = gmdate ('Y_m_d_H_00',time() - 5400);
# ----------------------------------------------------------------------
# BELOW THIS LINE YOU CAN ADAPT AS YOU WISH
# LEAVE ONE LINE FOR thunder - rain and clouds. Do not comment all lines
# ----------------------------------------------------------------------
switch ($SITE['region']) {
# ----------------------------------------HERE FOR EUROPE --------------
        case 'europe':
                # ------------------------------- thunder images -------
                $thunder        = 'http://images.blitzortung.org/Images/image_b_eu.png';        // europe
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_uk.png'; 	//  UK
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_fr.png'; 	//  south-western europe
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_sk.png'; 	//  northern europe
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_gr.png'; 	//  sout-east europe
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_pl.png'; 	//  eastern europe
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_de.png'; 	//  eastern europe
                # ------------------------------- rain images ----------
                $rain           = 'http://www.meteox.com/images.aspx?jaar=-3&amp;voor=&amp;soort=exp&amp;c=&amp;n=&amp;tijdid='.$now;
                # ------------------------------ cloud images ----------
                $clouds         = 'http://www.sat24.com/image2.ashx?country=eu&amp;type=loop&amp;sat=vis';
                break;
 # ----------------------------------------HERE FOR THE USA ------------
        case 'america':
                $thunder        = 'http://images.blitzortung.org/Images/image_b_us.png';        // North America
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_tx.png';       // texas
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_ny.png';       // new york
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_ca.png';       // california
                #$thunder       = 'http://images.blitzortung.org/Images/image_b_fl.png';       // florida
                # ------------------------------- rain images ----------------------------------
                $rain           = 'http://icons.wunderground.com/data/640x480/'.$SITE['WUregion'].'_rd_anim.gif';
                # ------------------------------ cloud images ---------
                $clouds         = 'http://www.ssd.noaa.gov/goes/comp/nhem/rb.jpg?tijdid='.$now;
                break;
# ----------------------------------------HERE FOR CANADA -------------- 
        case 'canada':
                $thunder        = 'http://images.blitzortung.org/Images/image_b_us.png';        // North America
                $rain           = 'http://weather.gc.ca/data/radar/detailed/temp_image/COMPOSITE_NAT/COMPOSITE_NAT_PRECIP_RAIN_'.$utc_date.'.GIF';
                $clouds         = 'http://www.ssd.noaa.gov/goes/comp/nhem/rb.jpg?time='.$now;
                break;
# ----------------------------------------HERE FOR ALL OTHERS --------------
        default:
                $thunder        = 'http://images.blitzortung.org/Images/image_b_oc.png';        // europe
                $rain           = 'http://www.bom.gov.au/radar/IDR00004.jpg';
                $clouds         = 'http://www.sat24.com/image2.ashx?country=eu&amp;type=loop&amp;sat=vis';
 }
 #-----------------------------------------------------------------------
# LAST LINES ARE HOUSEKEEPING  - DO NOT CHANGE ANYTHING HERE
# ----------------------------------------------------------------------
$ws['img_lightning']    = $thunder;
$ws['img_rain']         = $rain;
$ws['img_clouds']       = $clouds;
# ----------------------  version history
# 3.20 2015-07-15 release 2.8 version 
