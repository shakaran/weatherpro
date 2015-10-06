EWN FORECAST v 1.4 (August 2014).
EWN's own forecast :)

v.1.4
- Fixes for mobile devices and default forecastlocation
- Added MeteoAlarm-warnings IF geolocation and MA-region names match
- Fixes for Leuven template (v 2.5) - see leuven-folder for some example-files
- Other bugfixes
- Added API-key to ewn_config.php - will be required from 2015-01-01. Get your key from EWN member-area.

v.1.3
- Bugfixes
- Switched tabs to menu
- Load forecast based on URL
- Extended forecast to 15 days
- New graphs

v.1.2
- Switch to WRFDA from WRF
- Added new maps to WRFDA and GFS
- Added daily maps for next 3 days
- Removed Arome and CMC maps

v.1.1 - Speedup/responsive-update
- Temperature-correction for first 24h towards measured temperature if a wx-station in EWN are found enough close. :)
- Changed Esri maps to Openlayers 2.13 - OL3 are still in too early beta to be used in production...
- Preloading of tiles when playing :)
- Added settings for responsiviness so it works better in tablets/smartphones
- Added demo for a page with only map
- Main forecast scales down if phone detected + setting to enable the detection
- The map height is set to browser/device-height if it is less than 675 px
- Some fixes for Android stock browser and small screens
- Layerid's used for ex. default forecastlayer are changed from numbers to text, see below
- wrf_s.css and ewn_frc.js are now local files to avoid possible hickups
- Some general cleanups & moving around stuffs :P
- REMEMBER TO COMPARE YOUR ewn_frc_conf.php WITH THE NEW ONE!

########################################################
# RESONSIVE CSS

Main widths are now set to 100% so it scales depending on available space in wrapping element.
In css-file are settings what hide ex. columns in tables based on browserwidth so content fits in smaller screens.
The default settings are set using my nordicweather.net so you probably need to tweak them a bit.

For example:
@media (max-width:650px){
  .col650{display:none !important}
}

This means: when browserwidth are less than 650 px are elements with class col650 hidden. 
IF you need to change, change the max-width-value, NOT the col650.

########################################################
# WXSIM

The forecast use also our WXSIM's data when queryd forecast are nearby a station with WXSIM. :)

To add your WXSIM to the database, simply be sure the url to lastret.txt are listed in your settings in EWN.
The script checks then the database when a forecast.

#######################################################
# FILES

- forecast.php -> A demo how to add the full forecast on your page
- short_demo.php - A demo of how to implent a short forecast on the page, like the one from 3in1 :)
- map_only.php - A demo how to add a page with only the maps
- ewn_frc/ directory: All the scripts
   * ewn_frc_conf.php - config-file
   * ewn_frc.php - main script
   * wrf_map.php - mapscript
- css/ - css-files
- js/ - js-files
- lang/ - langauge files

Language-files are the same whats used in EWN main script, with some additions. You can replace the files in EWN's main script with theese in this zip. 
EWN main script's zip will be updated with theese once i got the translations.

#########################################################
# LAYERS IN FORECASTMAPS

WRF:
wrf_dbz, wrf_temp, wrf_chill, wrf_dew, wrf_tmpsfc, wrf_wind, wrf_gust, wrf_psnow (precip + clouds), wrf_precip, wrf_rrate, wrf_prectyp, wrf_accum, wrf_snowd, wrf_baro, wrf_hail, wrf_tornado, wrf_supercell, wrf_cin, wrf_lftx, wrf_cape, wrf_srh3, wrf_shear01, wrf_shear06
    
AROME:
arome_temp, arome_chill, arome_wind, arome_precip, arome_baro
    
YR:
yr_temp, yr_chill, yr_wind, yr_precip, yr_baro

CMC:
cmc_temp, cmc_wind, cmc_precip, cmc_baro

GFS:
gfs_temp, gfs_chill, gfs_dew, gfs_wind, gfs_psnow, gfs_baro, gfs_tornado, gfs_supercell, gfs_cape, gfs_lftx


############################################################

The script is provided "as-is" without warranty. 
If you find it useful, consider to buy a beer to me by making a donation by Paypal to info@nordicweather.net. 
Donate-button can also be found at my webpage nordicweather.net. ;)