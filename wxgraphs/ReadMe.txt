Installation Instructions.

1. Unzip the package and upload to a directory of your choice on your local PC.

2. Read this ReadMe.txt file :-))

3. Select the sample graphsconf-****.php file you wish to use and open it in a text editor
   like Notepad or ConTEXT.
   
4. Edit the required parameters in the customisation section. You must ensure the following are 
   correct:-
   
       $hostloc = "${WEBROOT}/";  this will work if your clientraw files are in your root (home) directory
                                  on the webserver (it must end with a / character). Note: do not change the
                                  ${WEBROOT} part.

       $jploc = "${WEBROOT}/jpgraph-1.20.5/src/";  this will need to point ot the correct JPGraph directory
                                                   on your webserver. Note: do not change the ${WEBROOT} part.

       $hourmode = "24"; must be 12 or 24 depending on which time mode you use in Weather Display.

   You do not need to alter any other variables at this time. This can come later once you have tested the 
   installation.

5. Save this edited version of the config file as graphsconf.php in the same directory.

6. Upload this directory containing the graphs and config file to your webserver. Do not at this stage remove 
   any files, upload everything.
   
7. Now go to your favourite browser and point it at your graphs eg. http://www.mysite.com/graphs/wxgraphs_text.html
   where you change the www.mysite.com to your own website, and the graphs to the directory you have chosen. You 
   should now see all the graphs displayed with the default settings from the graphsconf.php you just edited before.
   
8. Once this is all working and you can see all the graphs you can chose the ones you want and incorporate them into 
   your own pages. You can then remove any graph files you do not want to use if you need to save some space. Make 
   sure you do not remove the main graphsconf.php or error_msg.php files as these are required.

9. If you use a language other than English the you can edit the graphlang.php file and alter the data there to 
   reflect your own language, or as samples become available copy in the contents or rename the sample file. This
   file allows you to have the actual label data on your graphs in your language. I have also included a Danish 
   language file (graphlang-danish.php) courtesy of Henrik (jwwd on the forum).

10. Enjoy......


Stuart 
March 2008

List of files:-

 baro_1hr.php
 baro_24hr.php
 baro_7days.php
 baro_7days_line.php
 error_msg.php
 graphlang-danish.php
 graphlang.php
 graphsconf-metric+knots.php
 graphsconf-metric.php
 graphsconf-uk.php
 graphsconf-USA.php
 graphsconf.php
 humidity_1hr.php
 humidity_7days.php
 indoor_temp_24hr.php
 month_baro.php
 month_baro_line.php
 month_hilo_temp.php
 month_humidity.php
 month_rain.php
 month_winddir.php
 month_windspeed.php
 nextstorm_24hr.php
 rain_1hr.php
 rain_24hr.php
 rain_60min.php
 rain_7days.php
 rain_this_month.php
 rain_this_year.php
 rain_today.php
 rain_week.php
 rain_yesterday.php
 ReadMe.txt
 solar_24hr.php
 solar_7days.php
 temp+dew+hum_1hr.php
 temp+dew+hum_24hr.php
 temp+hum_24hr.php
 temp_1hr.php
 temp_60min.php
 temp_7days.php
 uv_24hr.php
 uv_7days.php
 winddir_1hr.php
 winddir_24hr.php
 winddir_60min.php
 winddir_7days.php
 windgust_1hr.php
 windspeed_1hr.php
 windspeed_24hr.php
 windspeed_60min.php
 windspeed_7days.php
 wxgraphs_test.html
 year_rain.php