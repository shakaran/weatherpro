<?php
#-------------- Air Quality Health Index -------------------------------
#
# The  us version of the AQHI script displays up to 4 maps with regional air quality information
#
#-----------------------------------------------------------------------
#
# The first map is a  country wide map, do not change.
# If you do not want to display them, comment them with a # on the first position
# So it becomes 
#$url_img_usa    = 'http://files.airnowtech.org/airnow/today/forecast_aqi_********_usa.jpg';
#
$url_img_usa    = 'http://files.airnowtech.org/airnow/today/forecast_aqi_********_usa.jpg';
#-----------------------------------------------------------------------
#
# the second map is a  state wide wide map, 
# check this site to find your state map:               http://airnow.gov/index.cfm?action=airnow.national_summary
# right click on the map, choose open in new tab.
# copy the url form the browser address line below
# make SURE you remove the date part by adding all *
# this is the example from the browser addres line
# http://files.airnowtech.org/airnow/today/forecast_aqi_20150717_tx_ok.jpg
# you replace the data of totoday with *
# http://files.airnowtech.org/airnow/today/forecast_aqi_********_tx_ok.jpg
# and then insert that in the line below
#
$url_img_fct    = 'http://files.airnowtech.org/airnow/today/forecast_aqi_*******_tx_ok.jpg';
#
# the last two maps are for smaller areas, the first one static, the last one a "animated" one.
# they are not  always available in every region, so set them as a comment when you do not want to display them
# they are found the same way as the previous one by 
# check this site to and go to  your state map:  http://airnow.gov/index.cfm?action=airnow.national_summary
# selecti the city closest to your area from the list at the right of the page
# select the tab with "Currewnt AQHI" 
# select the image => right click => copy the URL
#
#$url_img_obs   = 'http://files.airnowtech.org/airnow/today/cur_aqi_houston_tx.jpg';
#$url_img_obs   = 'http://files.airnowtech.org/airnow/today/cur_aqi_columbus_oh.jpg';
$url_img_obs    = 'http://files.airnowtech.org/airnow/today/cur_aqi_houston_tx.jpg';    

#$url_img_obs_mov='http://files.airnowtech.org/airnow/today/anim_aqi_tx_ok.gif';
#$url_img_obs_mov='http://files.airnowtech.org/airnow/today/anim_aqi_mi_in_oh.gif';
$url_img_obs_mov= 'http://files.airnowtech.org/airnow/today/anim_aqi_tx_ok.gif'; 
#---------------------------------------------------------------------------------------
