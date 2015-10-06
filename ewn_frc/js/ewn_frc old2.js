/* v.1.41
 */
  var cookieage=32000000000;//1yr
  var boset = {"showtip":false,"lastplaces":[],advanced:false};
  var basepath="maps.nordicweather.net";
  var yTitles = {color: "#666666", fontWeight: "bold",fontSize:"11px"};
  var yLabels = {fontWeight: "normal",fontSize:"11px"};
  var wiset = "m/s",target="toplist",minimap,wxlayer;
  var globalX,globalY,chart,chart2,chart3,chart4,data,tsoptionsc;
  var wsmap,dailymap;
  var maxoffset=12,dday=1,dlayer="severe",wxlayer2,dlines3;
  var map,light,satmap,normmap,dlines,llines,olines,slider,playTimer,loadTimer,is_phone;
  var mettableid,currid,layers=[],mId=1,lastLayer,mStep,mTime,layerPath,dah,playActive=false,sliderLoad=false,preloadActive=false;
  var rgr=1;
  var screenheight = 1000;
  var screenwidth = 800;
  var nua = navigator.userAgent;
  var is_anubunturegular = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Anubunturegular ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
  $(function () {
    screenheight = $(window).height();
    screenwidth = $(window).width();
    if(screenheight<675){$("#wrapper").css({height: "600 px"});$("#map").css({height: "600 px"});}
    $(window).resize(function () {
      screenheight = $(window).height();
      screenwidth = $(window).width();
      if(screenheight<675){$("#wrapper").css({height: "600px"});$("#map").css({height: "600px"});}
      else{$("#wrapper").css({height: "675px"});$("#map").css({height: "675px"});}
    });
  });
  var resolutions=[19567.87923828125,9783.939619140625,4891.9698095703125,2445.9849047851562,1222.9924523925781];
  var zoomOffset=3;
  var isTouch = Modernizr.touch;
  
function dostuff(){
$(function () {

  // Fix for select's on Anubunturegular stock browser - port from Bootstrap
  if(is_anubunturegular) {
    $('select.form-control').removeClass('form-control').css('width', '100%');
  }
  
  setTimeout(function(){$("#link_table").hide();},250);

  if(!map_only){
    getbiggest("new");
    $("#country").change(function(){
      getbiggest("old");
    });

    $("#quickoptions").change(function(){
      if($("#quickoptions :selected").val() != "None"){
        url='http://'+window.location.hostname+window.location.pathname+'?lang='+lang+'&geoid='+$("#quickoptions :selected").val()+'&city='+$("#quickoptions :selected").text()+p;
      window.location.href=url;
      }
    });
  }

  if($.jStorage.get("ewnwind")){
    wiset=$.jStorage.get("ewnwind");
    $("#wspd").val(wiset);
  }
  $("#wspd").change(function() {
    wiset = $("#wspd").val();
    $.jStorage.set("ewnwind", wiset);
    $.jStorage.setTTL("ewnwind", 32000000000);
    getpage(deflat,deflon);
  });
  
  if($.jStorage.get("ewnfav-fi")){
    qstr = $.jStorage.get("ewnfav-fi");
    qstr = qstr.split(",");
    if(isNumber(qstr[0])){deflat=qstr[0];}
    if(isNumber(qstr[1])){deflon=qstr[1];}
  }
  
  if($.jStorage.get("ewnphone")){
    is_phone = $.jStorage.get("ewnphone");
    if(is_phone=="true"){
      $(".no_phone").hide();
    }
  }
  
  $(".ewnnav").click(function () {
    target = $(this).attr("id");
    var l = $(this).attr("data-l");
    if(l==0 && $("#ewnsubnav").is(":visible")){
      $("#ewnsubnav").hide();
      $(this).removeClass("selected");
    }else if (l==0){
      $("#ewnsubnav").show();
      $(this).addClass("selected");
    }
    if(l==1){$("#ewnsubnav").hide();}
    if(l==2){
      $("#ewnsubnav .selected").removeClass("selected");
      $(this).addClass("selected");
    }
    if(l<2){
      $("#ewnnav .selected, #ewnsubnav .selected").removeClass("selected");
      $(this).addClass("selected");
    }
    if(l>0){
      target = target.replace("Panel", "_table");
      $(".tabs").hide();
      $("#"+target).show();
      if(target=="map_table"&&!wsmap){
        var w = $("#map").width()-20;
        $("#sliderext, #sliderwrap").css({width:w+"px"});
        $(window).resize(function () {
          var w = $("#map").width()-20;
          $("#sliderext, #sliderwrap").css({width:w+"px"});
        });
        loadmaps();
      }
      if(target=="dailymap_table"){
        loaddailymaps();
      }
      if(target=="hour_table"){
        rgr=1;
        chart = new Highcharts.Chart(tempoptions);
        drawwindbox();
        drawgraphiconswx(hourgraph.svg, "hourgraph", 28);
        drawgraphiconswind(hourgraph.svg);
          
        $(".graphPanel").click(function () {
          $("#graphPanels .selected").removeClass("selected");
          $(this).addClass("selected");
          var target = $(this).attr("id");
          target = target.replace("Panel", "");
          if(chart){chart.destroy();chart = null;}
          if(target=="temp"){
            chart = new Highcharts.Chart($.extend({},tempoptions));
            drawwindbox();
            drawgraphiconswx(hourgraph.svg, "hourgraph", 28);
            drawgraphiconswind(hourgraph.svg);
          }
          if(target=="baro"){
            chart = new Highcharts.Chart($.extend({},tsoptions));
            drawwindbox();
            drawgraphiconswind(hourgraph.svg);
          }
          if(target=="snow"){
            chart = new Highcharts.Chart($.extend({},snowoptions));
            drawwindbox();
            drawgraphiconswind(hourgraph.svg);
          }
        });
      }
      
      if(target=="day_table"){
        rgr=3;
        chart = new Highcharts.Chart(tempoptionsb);
        drawwindbox();
        drawgraphiconswx(hourgraphb.svg, "hourgraphb", 20);
        drawgraphiconswind(hourgraphb.svg);
          
        $(".graphPanelb").click(function () {
          $("#graphPanelsb .selected").removeClass("selected");
          $(this).addClass("selected");
          var target = $(this).attr("id");
          target = target.replace("Panelb", "");
          if(chart){chart.destroy();chart = null;}
          if(target=="temp"){
            chart = new Highcharts.Chart($.extend({},tempoptionsb));
            drawwindbox();
            drawgraphiconswx(hourgraphb.svg, "hourgraphb", 20);
            drawgraphiconswind(hourgraphb.svg);
          }
          if(target=="baro"){
            chart = new Highcharts.Chart($.extend({},tsoptionsb));
            drawwindbox();
            drawgraphiconswind(hourgraphb.svg);
          }
          if(target=="snow"){
            chart = new Highcharts.Chart($.extend({},snowoptionsb));
            drawwindbox();
            drawgraphiconswind(hourgraphb.svg);
          }
        });
      }
      
      if(target=="nerd_table"){
        rgr=0;
        chart = new Highcharts.Chart(extraoptions);
        drawwindbox();
        drawgraphiconswind(hourgraph.svg);
      }
      
      if(target=="comp_table"){
        rgr=-1;
        chart = new Highcharts.Chart(tempoptionsd);
        $(".comps").hide();
        $(".comp_temp").show();
        $(".graphPaneld").click(function () {
          $("#graphPanelsd .selected").removeClass("selected");
          $(this).addClass("selected");
          var target = $(this).attr("id");
          target = target.replace("Panelb", "");
          if(chart){chart.destroy();chart = null;}
          $(".comps").hide();
          if(target=="temp"){
            chart = new Highcharts.Chart($.extend({},tempoptionsd));
            $(".comp_temp").show();
            $(".comps1").show();
          }
          if(target=="baro"){
            chart = new Highcharts.Chart($.extend({},prmsloptionsd));
            $(".comp_prmsl").show();
            $(".comps1").show();
          }
          if(target=="wind"){
            chart = new Highcharts.Chart($.extend({},wspdoptionsd));
            $(".comp_wspd").show();
            $(".comps1").show();
          }
          if(target=="prec"){
            chart = new Highcharts.Chart($.extend({},prec3hoptionsd));
            $(".comp_prec").show();
            $(".comps1").hide();
          }
        });
      }
      
    }
    return false;          
  });
  
  if($.jStorage.get("wrffrc")){boset=$.jStorage.get("wrffrc");}
  
  if(!map_only){do_frc();}
  else{do_map();}
  
  function do_frc(){
    if(isTouch&&ajax){
      $.ajax({
        dataType: "jsonp",
        cache: true,
        jsonpCallback: "ewn",
        url		: "http://www.europeanweathernetwork.eu/frc/data_php.php",
        data	: {v:version,key:scrambled,mainwidth:mainwidth,lang:lang,user:"ajax",wsp:wiset,lat:deflat,lon:deflon,datestyle:datestyle,detect_phone:phonedetect},
        success: function(stuff) {
          if(stuff.phone=="true"){
           // $(".no_phone").hide();
            $(".nordui-table td").css({padding: "2px"});
          }
          if(screenwidth<450){
            $(".col450").hide();
          }
          $("#ewndiv").html(stuff.data);
          //$("#stats_table").html(stuff.stats);
          $("#frcname").html(stuff.pname);
          $(".frclength").html(stuff.frc);
          $("#hour_table").show();
          var f = new Date();
          $("#issuedtime").html(f.format("yyyy-mm-dd HH:MM:ss"));
          if(screenwidth>650){
            chart = new Highcharts.Chart($.extend({},tempoptions));
            drawwindbox();
            drawgraphiconswx(hourgraph.svg, "hourgraph", 28);
            drawgraphiconswind(hourgraph.svg);
            
            $(".graphPanel").click(function () {
              $("#graphPanels .selected").removeClass("selected");
              $(this).addClass("selected");
              var target = $(this).attr("id");
              target = target.replace("Panel", "");
              if(chart){chart.destroy();chart = null;}
              if(target=="temp"){
                chart = new Highcharts.Chart($.extend({},tempoptions));
                drawwindbox();
                drawgraphiconswx(hourgraph.svg, "hourgraph", 28);
                drawgraphiconswind(hourgraph.svg);
              }
              if(target=="baro"){
                chart = new Highcharts.Chart($.extend({},tsoptions));
                drawwindbox();
                drawgraphiconswind(hourgraph.svg);
              }
              if(target=="snow"){
                chart = new Highcharts.Chart($.extend({},snowoptions));
                drawwindbox();
                drawgraphiconswind(hourgraph.svg);
              }
            });
          }
        }
      });
      getpage(deflat,deflon);
    }else{
      getpage(deflat,deflon);
    }
  
    $("#city").autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "http://www.europeanweathernetwork.eu/frc/yrdb.php",
          dataType: "jsonp",
          cache: true,
          jsonpCallback: "yrplacequery",
          data: {maxRows: 20,term: request.term,country: $("#country :selected").val()},
          success: function( data ) {
            response( $.map( data.geonames, function( item ) {
              return {label: item.name,desc: "<small>("+item.area+")</small>",value: item.name,lat: item.lat,lon: item.lon,idnum: item.idnum}
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {
        url='http://'+window.location.hostname+window.location.pathname+'?lang='+lang+'&geoid='+ui.item.idnum+'&city='+ui.item.value+p;
        window.location.href=url;
        //getpage(ui.item.lat,ui.item.lon);
      },
      open: function(){$(this).removeClass("ui-corner-all").addClass("ui-corner-top");},
      close: function(){$(this).removeClass("ui-corner-top").addClass("ui-corner-all");}
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
      ul.css({zIndex:999999999})
      return $( "<li></li>" )
        .data( "item.autocomplete", item )
        .append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
        .appendTo( ul );
    }
  }
  
  function do_map(){
    var w = $("#map").width()-20;
    $("#sliderext, #sliderwrap").css({width:w+"px"});
    $(window).resize(function () {
      var w = $("#map").width()-20;
      $("#sliderext, #sliderwrap").css({width:w+"px"});
    });
    loadmaps();
  }
  
/*##############################################################################################
 * FUNCTIONS
 */
 

  function getpage(lat,lon){
    $("#topPanels .selected").removeClass("selected");
    if(screenwidth<800){
      $("#hourPanel").addClass("owntabactive");
      $("#hourPanel").addClass("selected");
      $("#hour_table").show();
      $("#quick_table").hide();
      if(screenwidth>=650){
        setTimeout(function(){
          prectip = hourgraph.prectip;
          chart = new Highcharts.Chart(tempoptions);
          drawwindbox();
          drawgraphiconswx(hourgraph.svg, "hourgraph", 28);
          drawgraphiconswind(hourgraph.svg);
        },500);
      }
    }else{
      $("#quickPanel").addClass("owntabactive");
      $("#quickPanel").addClass("selected");
      //$("#quick_table").show();
    $("#mini1").prop("checked", true);
    var f = new Date();
    $("#issuedtime").html(f.format("yyyy-mm-dd HH:MM:ss"));
        
    minimap = new OpenLayers.Map("minimap");
    minimap.numZoomLevels = null;
    if(mousewheel==0){
      controls = minimap.getControlsByClass('OpenLayers.Control.Navigation');
      for(var i = 0; i < controls.length; ++i){controls[i].disableZoomWheel();}
    }
    minimap.events.register('click', minimap, handleMapClick);
    lightmap2 = new OpenLayers.Layer.XYZ("light2","http://a."+basepath+"/tiles/nat_g/${z}/${x}/${y}.png",
      {sphericalMercator: true,zoomOffset:zoomOffset,resolutions:resolutions});
    wxlayer = new OpenLayers.Layer.XYZ("wxlayer","http://b."+basepath+"/tiles/frcst/esri_wrf_psnow_"+currlay+"/${z}/${x}/${y}.png",
      {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    dlines2 = new OpenLayers.Layer.XYZ("dline2","http://b."+basepath+"/tiles/lines_gray/${z}/${x}/${y}.png",
      {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    minimap.addLayers([lightmap2,wxlayer,dlines2]);
    minimap.setCenter(new OpenLayers.LonLat(deflon, deflat).transform(new OpenLayers.Projection("EPSG:4326"),minimap.getProjectionObject()),2);
    }
        
    $(".miniboxes").change(function(){
      laypa=$(this).attr("data-typ");
      minimap.removeLayer(wxlayer);
      wxlayer = new OpenLayers.Layer.XYZ("wxlayer","http://b."+basepath+"/tiles/frcst/esri_"+laypa+"_"+currlay+"/${z}/${x}/${y}.png",
      {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
      minimap.addLayer(wxlayer);
      minimap.setLayerIndex(wxlayer, 2);
      minimap.setLayerIndex(dlines2, 4);
    });
        
    function handleMapClick(e){
      var lonlat = minimap.getLonLatFromPixel(e.xy);
      lonlat.transform(minimap.getProjectionObject(),new OpenLayers.Projection("EPSG:4326"));
      url='http://'+window.location.hostname+window.location.pathname+'?lang='+lang+'&lat='+lonlat.lat.toFixed(4)+'&lon='+lonlat.lon.toFixed(4);
      setTimeout(function(){window.location.href=url},20);
    }
        
    if(dailymap != undefined){
     // dailymap.setCenter(new OpenLayers.LonLat(deflon, deflat).transform(new OpenLayers.Projection("EPSG:4326"),dailymap.getProjectionObject()),2);
    }
    if(wsmap){
      wsmap.setCenter(new OpenLayers.LonLat(deflon, deflat).transform(new OpenLayers.Projection("EPSG:4326"),wsmap.getProjectionObject()),2);
    }
    
    $(".malarmlinks").click(function () {
    $(".malarms").hide();
      maid = $(this).attr("data-nam");
      $("."+maid).show();
    });
  
    $(".malarmhide").click(function () {
      $(".malarms").hide();
    });
    
    /*
        $("#frcfav").click(function() {
          $.jStorage.set("ewnfav-fi", stuff.plat+","+stuff.plon+","+stuff.pname);
          $.jStorage.setTTL("ewnfav-fi", 32000000000);
        });
        $("#frcqui").click(function() {
          var qui = $.jStorage.get("ewnqui");
          qui = stuff.id+","+stuff.plat+","+stuff.plon+","+stuff.pname+"|"+qui;
          $.jStorage.set("ewnqui", qui);
          $.jStorage.setTTL("ewnqui", 32000000000);
          getbiggest();
        });
      }
    });*/
    return false;
  }

  function getbiggest(prt){
    if(prt=="new"){co="new";}else{co=$("#country :selected").val();}
    $.ajax({
      url: "http://www.europeanweathernetwork.eu/frc/yrbiggest.php",
      dataType: "jsonp",
      cache: true,
      jsonpCallback: "yrbiggestquery",
      data: {country: co},
      success: function( result ) {
        if(prt=="new"){$("#country").val(result.viscou)}
        $("select[id$=quick] > option").remove();
        $("select[id$=quick]").remove();
        var optionsValues = "<select id='quick' class='form-control'>";
        var qui = $.jStorage.get("ewnqui");
        if(qui!=null){
          qui = qui.split("|");
          if(qui.length>0){
            optionsValues += "<option value='None' style='color:#aaa;'><b>-- "+ownplaces+" --</b></option>";
            for(i=0;i<qui.length-1;i++){
              line = qui[i].split(/,/);
              optionsValues += '<option value="'+line[0]+'" data-lat="'+line[1]+'" data-lon="'+line[2]+'">'+line[3]+'</option>';
            }
          }
        }
        optionsValues += "<option value='None' style='color:#aaa;'><b>-- "+qsearch+" --</b></option>";
        res=result.data;
        $.each(res, function(item) {
          optionsValues += '<option value="'+res[item]["id"]+'" data-lat="'+res[item]["lat"]+'" data-lon="'+res[item]["lon"]+'">'+res[item]["name"]+'</option>';
        });
        optionsValues += "</select>";
        var options = $("#quickoptions");
        $("#quickoptions").html(optionsValues);
        // Fix for select's on Anubunturegular stock browser - port from Bootstrap
        if(is_anubunturegular) {
          $('select.form-control').removeClass('form-control').css('width', '100%');
        }
        $("#quick option[value="+$.jStorage.get("ewnid")+"]").attr("selected", "selected");
      }
    });
    return false;
  }
  
  function loaddailymaps(){
    var dailywidth = $("#dailymapcontainer").width();
    $("#dailymap").css({width: (dailywidth-25)+"px"});
    $("#today").prop("checked", true);
    $("#dsevere").prop("checked", true);
    dailymap = new OpenLayers.Map("dailymap");
    dailymap.numZoomLevels = null;
    if(mousewheel==0){
      controls = dailymap.getControlsByClass('OpenLayers.Control.Navigation');
      for(var i = 0; i < controls.length; ++i){controls[i].disableZoomWheel();}
    }
    lightmap3 = new OpenLayers.Layer.XYZ("light3","http://a."+basepath+"/tiles/nat_g/${z}/${x}/${y}.png",
      {sphericalMercator: true,zoomOffset:zoomOffset,resolutions:resolutions});
    wxlayer2 = new OpenLayers.Layer.XYZ("wxlayer2","http://b."+basepath+"/tiles/frcst/esri_wrf_"+dlayer+"_d_"+dday+"/${z}/${x}/${y}.png",
      {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    dlines3 = new OpenLayers.Layer.XYZ("dline3","http://b."+basepath+"/tiles/lines_gray/${z}/${x}/${y}.png",
      {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    dailymap.addLayers([lightmap3,wxlayer2,dlines3]);
    dailymap.setCenter(new OpenLayers.LonLat(deflon, deflat).transform(new OpenLayers.Projection("EPSG:4326"),dailymap.getProjectionObject()),2);
    
    $("#dlalegend").show();
    if(screenwidth>450){
      $("#dlalegend").animate({height: "hide", width: "hide"}, 100, "linear").html("");
      $(".top_wrf_dsevere").clone().appendTo("#dlalegend").show();
      $("#dlalegend").animate({height: "show", width: "show"}, 500, "linear").css({display:"inline-block"});
    }
    
    $(".dailydays").change(function(){
      dday=$(this).attr("data-typ");
      dailymap.removeLayer(wxlayer2);
      wxlayer2 = new OpenLayers.Layer.XYZ("wxlayer2","http://b."+basepath+"/tiles/frcst/esri_wrf_"+dlayer+"_d_"+dday+"/${z}/${x}/${y}.png",
        {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
      dailymap.addLayer(wxlayer2);
      dailymap.setLayerIndex(wxlayer2, 2);
      dailymap.setLayerIndex(dlines3, 4);
    });
    
    $(".dailymap").change(function(){
      dlayer=$(this).attr("data-typ");
      dailymap.removeLayer(wxlayer2);
      wxlayer2 = new OpenLayers.Layer.XYZ("wxlayer2","http://b."+basepath+"/tiles/frcst/esri_wrf_"+dlayer+"_d_"+dday+"/${z}/${x}/${y}.png",
        {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
      dailymap.addLayer(wxlayer2);
      dailymap.setLayerIndex(wxlayer2, 2);
      dailymap.setLayerIndex(dlines3, 4);
      
      if (dlayer=="severe"){
        $("#dlelegend").hide();
        $("#dlalegend").show();
        if(screenwidth>450){
          $("#dlalegend").animate({height: "hide", width: "hide"}, 100, "linear").html("");
          $(".top_wrf_dsevere").clone().appendTo("#dlalegend").show();
          $("#dlalegend").animate({height: "show", width: "show"}, 500, "linear").css({display:"inline-block"});
        }
      }else{
        $("#dlelegend").show();
        $("#dlalegend").hide();
        if(dlayer=="maxtemp"){var dl="wrf_dmaxtemp";}
        if(dlayer=="mintemp"){var dl="wrf_dmintemp";}
        if(dlayer=="gust"){var dl="wrf_dwind";}
        if(dlayer=="precip"){var dl="wrf_dprecip";}
        if(screenwidth>450){
          $("#dlelegend").animate({height: "hide", width: "hide"}, 100, "linear").html("");
          $("."+dl).clone().appendTo("#dlelegend").show();
          $("#dlelegend").animate({height: "show", width: "show"}, 500, "linear").css({display:"inline-block"});
        }
      }
    });
        
  }
  
  function loadmaps(){ 
    wsmap = new OpenLayers.Map("map");
    wsmap.numZoomLevels = null;
    if(mousewheel==0){
      controls = wsmap.getControlsByClass('OpenLayers.Control.Navigation');
      for(var i = 0; i < controls.length; ++i){controls[i].disableZoomWheel();}
    }
    $("#"+defmap).prop("checked", true);
    $("#"+defbase).prop("checked", true);
  
    checkBaseLayer();
    wsmap.setCenter(new OpenLayers.LonLat(deflon, deflat).transform(new OpenLayers.Projection("EPSG:4326"),wsmap.getProjectionObject()),defzoom);
  
    getmStep(defmap.split("_")[0]);
    curoffset = getcuroffset();
    startLayer = curoffset+1;
    mId = startLayer;
    lastLayer = startLayer;
    da=new Date(((startLayer-1)*mStep)+mTime);
    $(".mapinfo_date").html(da.format("dd/mm/yyyy")+'<br/>'+da.format("HH:00 o"));
    layerPath=$("#"+defmap).attr("id");
    currid=layerPath.split("_")[0];
    if(screenwidth>450){
      $("."+defmap).clone().appendTo("#lelegend").show();
      $("#lelegend").animate({height: "show", width: "show"}, 500, "linear").css({display:"inline-block"});
    }
    $(".mapinfo_name").html($("#"+defmap).attr("data-txt1"));
    
    layers[layerPath] = [];
    for(i=1;i<mMax+1;i++){
      layers[layerPath][i] = new OpenLayers.Layer.XYZ(layerPath+i,"http://c."+basepath+"/tiles/frcst/esri_"+layerPath+"_"+i+"/${z}/${x}/${y}.png", 
        {sphericalMercator:true,isBaseLayer:false,visibility:false,zoomOffset:zoomOffset,resolutions:resolutions});
      wsmap.addLayer(layers[layerPath][i]);
      layers[layerPath][i].events.register("tileloaded", layers[layerPath][i], layerLoadcheck);
      layers[layerPath][i].preLoaded = false;
      wsmap.setLayerIndex(layers[layerPath][i],i);
    }
    layers[layerPath][startLayer].setVisibility(true);
    layers[layerPath][startLayer].preLoaded = true;
    wsmap.setLayerIndex(dlines,200);
    
    doslider();
    doticks();
    eventsLog = OpenLayers.Util.getElement("eventsLog");
    sliderLoad=true;
    
    $(window).resize(function () {
      screenheight = $(window).height();
      screenwidth = $(window).width();
      if(screenheight<675){$("#wrapper").css({height: screenheight+"px"});$("#map").css({height: screenheight+"px"});}
      else{$("#wrapper").css({height: "675px"});$("#map").css({height: "675px"});}
      doslider();
      doticks();
    });
      
    $("#mapPropsContainer").on("mouseleave", function(){
      $("#mapPropsContainer").animate({height: "hide", width: "hide"}, 100, "linear");
      $("#"+mettableid+"table").hide();
      if(mettableid!=currid){$("#"+mettableid).removeClass("moremapsactive");}
      return false;
    });
    $(".moremaps").on("mouseover", function(){
      $("#"+mettableid+"table").hide();
      if(mettableid!=currid){$("#"+mettableid).removeClass("moremapsactive");}
      mettableid=$(this).attr("id");
      $("#"+mettableid).addClass("moremapsactive");
      $("#mapPropsContainer").animate({height: "show", width: "show"}, 100, "linear").css({display:"inline-block"});
      var x = $("#"+mettableid).position();
      if($('#fmi').length){
        var xx = $("#fmi").position();
      }else{
        var xx = $("#yr").position();
      }
      $("#mapPropsContainer").css({"position":"absolute","margin-left": (x.left-xx.left)+"px"});
      $("#"+mettableid+"table").show();
      return false;
    });
    var count=0
    $('.moremaps, #mapPropsContainer').mouseenter(function(){
      count++;
    }).mouseleave(function(){
      if (count > 0) count--;
      setTimeout(function(){
        if (count==0){
          $("#mapPropsContainer").animate({height: "hide", width: "hide"}, 100, "linear");
          $("#"+mettableid+"table").hide();
          if(mettableid!=currid){$("#"+mettableid).removeClass("moremapsactive");}
        }
      },50);
    });
  
    $(".checkboxes").change(function(){
      if(playTimer){clearTimeout(playTimer);}
      layers[layerPath][lastLayer].setVisibility(false);
      layerPath=$(this).attr("id");
      if(layerPath=="wrf_baro"||layerPath=="gfs_baro"||layerPath=="yr_baro"){
        $("#lelegend").hide();
        $("#lalegend").hide();
      } else if (layerPath=="wrf_severe"){
        $("#lelegend").hide();
        $("#lalegend").show();
        if(screenwidth>450){
          $("#lalegend").animate({height: "hide", width: "hide"}, 100, "linear").html("");
          $(".top_"+layerPath).clone().appendTo("#lalegend").show();
          $("#lalegend").animate({height: "show", width: "show"}, 500, "linear").css({display:"inline-block"});
        }
      }else{
        $("#lelegend").show();
        $("#lalegend").hide();
        if(screenwidth>450){
          $("#lelegend").animate({height: "hide", width: "hide"}, 100, "linear").html("");
          $("."+layerPath).clone().appendTo("#lelegend").show();
          $("#lelegend").animate({height: "show", width: "show"}, 500, "linear").css({display:"inline-block"});
        }
      }
      $(".mapinfo_name").html($(this).attr("data-txt1"));
      if(is_phone){
        $("#mapPropsContainer").animate({height: "hide", width: "hide"}, 100, "linear");
        $("#"+mettableid+"table").hide();
      }
      getmStep(layerPath.split("_")[0]);
      if(currid!=layerPath.split("_")[0]){
        curoffset = getcuroffset();
        startLayer = curoffset+1;
        mId = startLayer;
        sliderLoad=false;
        doslider();
        doticks();
      }
      currid=layerPath.split("_")[0];
      reloadLayer();
    });
	
    $(".checkboxesb").change(function(){
      checkBaseLayer();
    });
    
  } // EOF LOADMAPS

  function doslider(){
    slider=$("#slider").slider({
      value:startLayer,
      min: 1,
      max: mMax-1,
      step: 1,
      change: function( event, ui ) {
        mId=ui.value;
        if(sliderLoad){showLayer(mId,false);}
        sliderLoad=true;
        da=new Date(((ui.value-1)*mStep)+mTime);
        $(".mapinfo_date").html(da.format("dd/mm/yyyy")+'<br/>'+da.format("HH:00 o"));
      }
    });
  }

  function doticks(){
    if(screenwidth<650){var g = 3;}else{var g = 2;}
    $(".tick").remove();
    for(i=0;i<mMax-1;i++){
      tst=new Date((i*mStep)+mTime);
      tst= sliderticks(i,tst);
      var tick = $('<div class="tick"><span class="tickwrap tw'+i+'">'+tst+'</span></div>').appendTo(slider);
        tick.css({left: (100 / (mMax-2) * i) + "%",
        width: (100 / (mMax-2)) + "%"});
      $(".tw"+i).css({left: "-"+$(".tw"+i).width()/2+"px"});
    }
  }
  
  $("#frcnext").click(function() {
    if (mId<=mMax){
      mId = (mId + 1);
      if(playTimer){clearTimeout(playTimer);}
      sliderLoad=false;
      playActive=false;
      preloadActive=false;
      showLayer(mId,true);
	  }
  });
  $("#frcprev").click(function() {
    if (mId>1){
      mId = (mId - 1);
      if(playTimer){clearTimeout(playTimer);}
      sliderLoad=false;
      playActive=false;
      preloadActive=false;
      showLayer(mId,true);
    }
  });
  $("#frcrepeat").click(function() {
    if(playTimer){clearTimeout(playTimer);}
    mId=startLayer;
    sliderLoad=false;
    playActive=true;
    preloadActive=true;
    showLayer(mId,true);
    setTimeout(function(){playM()},1000);
  });
  $("#frcplay").click(function() { 
    playM();
  });
  $("#frcstop").click(function() { 
    clearTimeout(playTimer);
    preloadActive=false;
    playActive=false;
  });
    
  function playM(){
    if(mId<=mMax){
      mId=mId+1;
      sliderLoad=false;
      playActive=true;
      preloadActive=true;
      showLayer(mId,true);
    }
    if(mId==mMax){clearTimeout(playTimer);playActive=false;}
  }
  
  function showLayer(mId,moveSlider){
    if(layers[layerPath][mId].preLoaded == false){
      if(layers[layerPath][mId].loading == false){
        layerLoader(mId);
        //eventsLog.innerHTML += "<br>Start load";
      }
      //eventsLog.innerHTML += "<br>Check..."+layerPath+"_"+mId;
      clearTimeout(loadTimer);
      loadTimer = setTimeout(function(){showLayer(mId,moveSlider)},100);
    }else{
      currId=layers[layerPath][mId].id;
      lastId=layers[layerPath][lastLayer].id;
         
      layers[layerPath][mId].setVisibility(true);
      $(currId).removeClass("layerOff").addClass("layerOn");
      $(lastId).removeClass("layerOn").addClass("layerOff");
      layers[layerPath][mId].setOpacity(1);
      // Wait until css has faded out the layer
      setTimeout(function(){
        layers[layerPath][lastLayer].setVisibility(false);
        lastLayer=mId;
      },300);
      
      if(moveSlider){$("#slider").slider("value",mId);}
      if(playActive&&mId<mMax){playTimer = setTimeout(function(){playM()},1000);}
    }
  }
  
  function layerLoadcheck(){
    if(this.numLoadingTiles == 0){
      //eventsLog.innerHTML += "<br>Loaded: "+this.name;
      this.preLoaded = true;
    }
  }
  
  function layerLoader(dah){
    layers[layerPath][dah].loading = true;
    layers[layerPath][dah].setVisibility(true);
    layers[layerPath][dah].setOpacity(0);
    if(playActive&&dah<mMax&&preloadActive){
      setTimeout(function(){layerLoader(dah+1)},800);
    }else{
      preloadActive=false;
    }
  }
      
  function reloadLayer(){
    playActive=false;
    sliderLoad=false;
    preloadActive=false;
    if(playTimer){clearTimeout(playTimer);}
    if(loadTimer){clearTimeout(loadTimer);}
    layers[layerPath] = [];
    for(i=1;i<mMax+1;i++){
      layers[layerPath][i] = new OpenLayers.Layer.XYZ(layerPath+i,"http://c."+basepath+"/tiles/frcst/esri_"+layerPath+"_"+i+"/${z}/${x}/${y}.png", 
        {sphericalMercator:true,isBaseLayer:false,visibility:false,zoomOffset:zoomOffset,resolutions:resolutions});
      wsmap.addLayer(layers[layerPath][i]);
      layers[layerPath][i].events.register("tileloaded", layers[layerPath][i], layerLoadcheck);
      layers[layerPath][i].preLoaded = false;
      wsmap.setLayerIndex(layers[layerPath][i],i);
      wsmap.setLayerIndex(dlines,2000);
    }
    layers[layerPath][mId].setVisibility(true);
    layers[layerPath][mId].preLoaded = true;
    lastLayer=mId;
    sliderLoad=true;
  }
  
	function checkBaseLayer(){
    if(typeof lightmap != 'undefined') {wsmap.removeLayer(lightmap);}
    if(typeof dlines != 'undefined'){wsmap.removeLayer(dlines);}
    if($("#gray").is(":checked")){
      lightmap = new OpenLayers.Layer.XYZ("light2","http://a."+basepath+"/tiles/nat_g/${z}/${x}/${y}.png",
        {sphericalMercator: true,zoomOffset:zoomOffset,resolutions:resolutions});
      dlines = new OpenLayers.Layer.XYZ("dline2","http://b."+basepath+"/tiles/lines_gray/${z}/${x}/${y}.png",
        {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    }
    if($("#normal").is(":checked")){
      lightmap = new OpenLayers.Layer.XYZ("borm2","http://a."+basepath+"/tiles/osm_dmi/${z}/${x}/${y}.png",
        {sphericalMercator: true,zoomOffset:zoomOffset,resolutions:resolutions});
      dlines = new OpenLayers.Layer.XYZ("dline2","http://b."+basepath+"/tiles/osm_lines/${z}/${x}/${y}.png",
        {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    }
    if($("#sat").is(":checked")){
      lightmap = new OpenLayers.Layer.XYZ("light2","http://a."+basepath+"/ArcGIS/rest/services/World_Imagery/MapServer/tile/${z}/${y}/${x}.png", 
        {sphericalMercator: true,zoomOffset:zoomOffset,resolutions:resolutions});
      dlines = new OpenLayers.Layer.XYZ("dline2","http://b."+basepath+"/tiles/lines_gray/${z}/${x}/${y}.png",
        {sphericalMercator:true,isBaseLayer:false,zoomOffset:zoomOffset,resolutions:resolutions});
    }
    if(wsmap){
      wsmap.addLayers([lightmap,dlines]);
      wsmap.setLayerIndex(lightmap,0);
      wsmap.setLayerIndex(dlines,75);
    }
    return false;
  }

  function getcuroffset() {
    var curtime = new Date().getTime();
    for (i=0; i<=maxoffset; i++) {
      if (mTime+(i*mStep) > curtime-mStep/2) {
        return i;
      }
    }
    return 0;
  }

  function savestorage(stpl){
    if($.jStorage.get("wrffrc")){boset=$.jStorage.get("wrffrc");}
    lastplaces = boset.lastplaces;
    lastplaces.unshift(stpl);
    if(lastplaces.length>12){lastplaces.pop();}
    boset.lastplaces=lastplaces;
    $.jStorage.set("wrffrc", boset);
    $.jStorage.setTTL("wrffrc", cookieage);
    return false;
  }

  function sliderticks(i,da){
      h=da.format("HH");
      if(mMax<20){
        if(i==0||l>h){o=da.format("HH")+"<br/>"+da.format("dddd");}else{o=h+"<br/>&nbsp;";}
        l=h;
      }if(mMax>50){
        if(i%4==0){
          if(i==0||l>h){o=da.format("HH")+"<br/>"+da.format("dddd");}else{o=h+"<br/>&nbsp;";}
          l=h;
        }else if(i%2==1){
          o="";
        }else{
          if(l>h){o="&#8226;<br/>"+da.format("dddd");l=h;}else{o="&#8226;<br/>&nbsp;";}
        }
      }else{
        if(i%2==0){
          if(i==0||l>h){o=da.format("HH")+"<br/>"+da.format("dddd");}else{o=h+"<br/>&nbsp;";}
          l=h;
        }else{
          if(l>h){o="&#8226;<br/>"+da.format("dddd");l=h;}else{o="&#8226;<br/>&nbsp;";}
        }
      }
    return o;
  }

  function getmStep(val){
    $("#"+currid).removeClass("moremapsactive");
    if(val=="yr"){
      mStep=mapconf["yr"]["yrsteps"]*3600000;
      mTime = mapconf["yr"]["yrtime"];
      mMax = (mapconf["yr"]["yrlength"]/mapconf["yr"]["yrsteps"]);
      $("#attrTxt").html('Data: Norwegian Meteorological Institute, <a target="_blank" href="http://www.met.no">met.no</a>');
      $("#yr").addClass("moremapsactive");
    }
    if(val=="fmi"){
      mStep=mapconf["fmi"]["fmisteps"]*3600000;
      mTime = mapconf["fmi"]["fmitime"];
      mMax = (mapconf["fmi"]["fmilength"]/mapconf["fmi"]["fmisteps"]);
      $("#attrTxt").html('Data: Finnish Meteorological Institute, <a target="_blank" href="http://www.fmi.fi">fmi.fi</a>');
      $("#fmi").addClass("moremapsactive");
    }
    if(val=="wrf"){
      mStep=mapconf["wrf"]["wrfsteps"]*3600000;
      mTime = mapconf["wrf"]["wrftime"];  
      mMax = (mapconf["wrf"]["wrflength"]/mapconf["wrf"]["wrfsteps"]);
      $("#attrTxt").html('Data: EWN WRFDA, <a target="_blank" href="http://www.europeanweathernetwork.eu">European Weathernetwork</a>');
      $("#wrf").addClass("moremapsactive");
    }
    if(val=="gfs"){
      mStep=mapconf["gfs"]["gfssteps"]*3600000;
      mTime = mapconf["gfs"]["gfstime"];  
      mMax = (mapconf["gfs"]["gfslength"]/mapconf["gfs"]["gfssteps"]);
      $("#attrTxt").html('Data: Global Forecast System (GFS), <a target="_blank" href="http://www.noaa.gov/">NOAA</a>');
      $("#gfs").addClass("moremapsactive");
    }
    if(val=="cmc"){
      mStep=mapconf["cmc"]["cmcsteps"]*3600000;
      mTime = mapconf["cmc"]["cmctime"];  
      mMax = (mapconf["cmc"]["cmclength"]/mapconf["cmc"]["cmcsteps"]);
      $("#attrTxt").html('Data: GEM-GDPS, <a target="_blank" href="http://weather.gc.ca/">Canadian Meteorological Centre</a>');
      $("#cmc").addClass("moremapsactive");
    }
    return false;
  }

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

if(!map_only){

 Highcharts.setOptions({
    lang: {weekdays: days,thousandsSep: ""},
    chart: {
      defaultSeriesType: "spline",
      backgroundColor: "#f7f7f7",
      plotBackgroundColor: {linearGradient: [0, 0, 0, 150],stops: [[0, "#eee"],[1, "#f2f2f2"]]},
      plotBorderColor: "#88BCCE",
      marginBottom: 77,
      marginRight: 40,
      marginTop: 30,
      plotBorderWidth: 1
    },
    title: {text: ""},
    credits: {enabled: false},
    plotOptions: {
      spline: {lineWidth: 1.8, shadow: false, marker: {enabled: false,radius:3,states: {hover: {enabled: true}}}},
      column: {lineWidth:0,pointWidth:24,pointPlacement:-0.5,groupPadding: 0,pointPadding: 0,borderWidth: 0,shadow: false},
      areaspline: {lineWidth: 1.5, shadow: false,marker: {enabled: false,radius:3,states: {hover: {enabled: true}}}}
    },
    tooltip: {
      shared: true,
      useHTML: true,
      crosshairs: { width: 0.5,color: "#666"},
      formatter: function () { 
        var index = this.points[0].point.index;
        var ret = "<b>" + Highcharts.dateFormat('%A %H:%M', this.x) +"</b>";
        ret += '<table>';
        Highcharts.each(this.points, function (point) {
          var series = point.series;
          if(series.name!=windtxt&&series.name!="6h "+prectxt&&series.name!="3h "+prectxt&&series.name!=rangetxt){
            ret += '<tr><td><span style="color:' + series.color + '">\u25CF</span> ' + series.name +
            ': </td><td style="white-space:nowrap">' + Highcharts.pick(point.point.value, point.y) +
            series.options.tooltip.valueSuffix + '</td></tr>';
          }
          if(series.name==rangetxt){
            ret += '<tr><td><span style="color:' + series.color + '">\u25CF</span> ' + series.name +
            ': </td><td style="white-space:nowrap">' + Highcharts.pick(point.point.low, point.y) +
            series.options.tooltip.valueSuffix + ' - ' + Highcharts.pick(point.point.high, point.y) +
            series.options.tooltip.valueSuffix + '</td></tr>';
          }
        });
        
        if(rgr==1){var precval=hourgraph.prectip[this.x];}
        if(rgr==3){var precval=hourgraphb.prectip[this.x];}
        if(rgr==1||rgr==3){
          ret += '<tr><td><span style="color:#4572A7">\u25CF</span> ' + prectxt +
          ': </td><td style="white-space:nowrap">' + precval +' mm</td></tr>';
        }
       
        if(rgr>=0){
          ret += '<tr><td style="vertical-align: top">\u25CF '+windtxt+'</td><td style="white-space:nowrap">' + 
          wspdtip[this.x][0]+' - '+wspdtip[this.x][1]+' '+wiset+'</td></tr>';
        }

        ret += '</table>';
        return ret;
      }
    },
    legend: {enabled: true, y:6},
    exporting: {enabled:false}
  });
  
  var yTitles = {fontSize:"10px"};
  var yLabels = {fontSize:"10px"};
  
  dailyX = { // Top X axis
      linkedTo: 0,
      type: 'datetime',
      tickInterval: 24 * 3600 * 1000,
      labels: {
        format: '{value:<span style="font-size: 12px; font-weight: bold">%A</span>}',
        align: 'left',
        x: 3,
        y: -5
      },
      opposite: true,
      gridLineWidth: 1.2,
      gridLineColor: "#88BCCE",
      tickLength: 20,
      tickColor: "#88BCCE",
      tickWidth: 1.2,
  };
  
  xAxis1 = { // Bottom X axis
      type: 'datetime',
      tickInterval: 3 * 36e5, // two hours
      minorTickInterval: 36e5, // one hour
      tickLength: 0,
      gridLineWidth: 0,
      gridLineColor: (Highcharts.theme && Highcharts.theme.background2) || '#F0F0F0',
      startOnTick: false,
      endOnTick: false,
      minPadding: 0,
      maxPadding: 0,
      offset: 30,
      showLastLabel: true,
      labels: {
        format: '{value:%H}'
      }
  };
  
  xAxis2 = { // Bottom X axis
      type: 'datetime',
      tickInterval: 6 * 36e5, // two hours
      minorTickInterval: 3 * 36e5, // one hour
      tickLength: 0,
      gridLineWidth: 0,
      gridLineColor: (Highcharts.theme && Highcharts.theme.background2) || '#F0F0F0',
      startOnTick: false,
      endOnTick: false,
      minPadding: 0,
      maxPadding: 0,
      offset: 30,
      showLastLabel: true,
      labels: {
        format: '{value:%H}'
      }
  };
  
  xAxis3 = { // Bottom X axis
      type: 'datetime',
      tickInterval: 6 * 36e5, // two hours
      minorTickInterval: 3 * 36e5, // one hour
      tickLength: 0,
      gridLineWidth: 0,
      gridLineColor: (Highcharts.theme && Highcharts.theme.background2) || '#F0F0F0',
      startOnTick: false,
      endOnTick: false,
      minPadding: 0,
      maxPadding: 0,
      offset: 0,
      showLastLabel: true,
      labels: {
        format: '{value:%H}'
      }
  };
  
  tempYAxis = [
    { // Temperature
      title: {text: null},labels: {format: '{value}°',style: yLabels,x: -3},
      plotLines: [{ value: 0,color: '#BBBBBB',width: 1,zIndex: 2}],maxPadding: 0.3,tickInterval: 2,gridLineWidth: 0   
    },{ // precipitation axis
      title: {text: null},labels: {enabled: false},gridLineWidth: 0,min:0,minRange:10,tickLength: 0
    },{ // Air pressure 
      title: { text: "hPa",offset: 0,align: 'high',rotation: 0,style: yTitles,textAlign: 'left',x: 3},
      labels: {style: yLabels,y: 2,x: 3},
      allowDecimals: false,gridLineWidth: 0.4,opposite: true,showLastLabel: false
    }];
  
  tsYAxis = [
    {
      gridLineWidth: 0,min: 0,
      title: {text: "J/kg",y:-7,margin:-18,style:yTitles,rotation:0,align:"high"},
      labels: {style: yLabels,x: -3} 
    },{
      lineWidth: 1,gridLineWidth: 0,offset: 31,title: {text: null},tickInterval: 2,
      labels: {format: '{value}°',x: -4,style: yLabels}
    },{
      gridLineWidth: 0.4,opposite: true,minRange:40,allowDecimals:false,
      title: {text: "hPa",y:-7,margin:-2,style:yTitles,rotation:0,align:"high"},
      labels: {align: "left",x: 4,style:yLabels}
    }];
    
  snowYAxis = [
    {
      gridLineWidth: 0.4,min:0,
      title: {text: "cm",y:-7,margin: -5,style:yTitles,rotation:0,align:"high"},
      labels: {x: -4,style:yLabels}       
    },{
      linkedTo:0,gridLineWidth:0,opposite:true,
      title: {text: "cm",y:-7,margin: -5,style:yTitles,rotation:0,align:"high"},
      labels: {x:4,style:yLabels}
    }];
    
  extraYAxis = [
    {
      gridLineWidth: 0,min: 0,
      title: {text: "J/kg",y:-7,margin:-18,style:yTitles,rotation:0,align:"high"},
      labels: {style: yLabels,x: -3} 
    },{
      gridLineWidth: 0.4,title: {text: null},tickInterval: 2,
      labels: {format: '{value}K',x: 4,style: yLabels},opposite:true
    }];
    
  compYAxis = [
    {
      gridLineWidth: 0.4,
      title: {text: "",y:-7,margin:-18,style:yTitles,rotation:0,align:"high"},
      labels: {style: yLabels,x: -3} 
    }];
    
  compYAxis2 = [
    {
      gridLineWidth: 0.4,min:0,
      title: {text: "",y:-7,margin:-18,style:yTitles,rotation:0,align:"high"},
      labels: {style: yLabels,x: -3} 
    }];
  
  tempoptions = {
    chart: {marginBottom: 77,renderTo: "hourgraph"},
    xAxis: [xAxis1,dailyX],
    yAxis: tempYAxis,
    series: [{
      name: temptxt,data: smoothline(hourgraph.tempdata),tooltip: {valueSuffix: "°C"},
      zIndex: 1,color: '#FF3333',negativeColor: '#48AFE8',pointInterval: 3600000,pointStart: hourgraph.jstart
    },{
      name: "3h "+prectxt,data: hourgraph.precdata,type: 'column',color:"#4572A7",yAxis: 1,
      dataLabels: {enabled: true,formatter: function () {if (this.y > 0) {return this.y;}},style: {fontSize: '9px',FontFamily:'Arial'}},
      tooltip: {valueSuffix: " mm"},pointInterval: 10800000,pointStart: hourgraph.jstart2
    },{
      name: barotxt,color: "#9ACD32",data: smoothline(hourgraph.barodata),
      tooltip: {valueSuffix: " hPa"},dashStyle: 'shortdot',yAxis: 2,pointInterval: 3600000,pointStart: hourgraph.jstart
    }]
  };

  tsoptions = {
    chart: {marginBottom: 77,renderTo: "hourgraph"},
    xAxis: [xAxis1,dailyX],
    yAxis: tsYAxis,
    series: [{
      name: "CAPE",color:"#EE7621",type:"areaspline",data:hourgraph.capedata,
      tooltip: {valueSuffix: " J/kg"},pointInterval: 3600000,pointStart: hourgraph.jstart
    },{
      name: dewtxt,color:"#6CA6CD",yAxis:1,data:hourgraph.dewdata,
      tooltip: {valueSuffix: "°C"},pointInterval: 3600000,pointStart: hourgraph.jstart
    },{
      name: barotxt,color:"#9ACD32",dashStyle: 'shortdot',yAxis:2,data:hourgraph.barodata,
      tooltip: {valueSuffix: " hPa"},pointInterval: 3600000,pointStart: hourgraph.jstart
    }]
  };
  
  snowoptions = {
    chart: {marginBottom: 77,renderTo: "hourgraph"},
    xAxis: [xAxis1,dailyX],
    yAxis: snowYAxis,
      series: [{
        name:snowtxt,color:"#87CEFA",type:"areaspline",data:hourgraph.snowdata,
        tooltip: {valueSuffix: " cm"},pointInterval: 3600000,pointStart: hourgraph.jstart
      }]
  };
  
  tempoptionsb = {
    chart: {marginBottom: 77,renderTo: "hourgraphb"},
    xAxis: [xAxis2,dailyX],
    yAxis: tempYAxis,
    series: [{
      name: temptxt,data: smoothline(hourgraphb.tempdata),tooltip: {valueSuffix: "°C"},
      zIndex: 1,color: '#FF3333',negativeColor: '#48AFE8',pointInterval: 10800000,pointStart: hourgraphb.jstart
    },{
      name: "6h "+prectxt,data: hourgraphb.precdata,type: 'column',color:"#4572A7",yAxis: 1,
      dataLabels: {enabled: true,formatter: function () {if (this.y > 0) {return this.y;}},style: {fontSize: '8px',FontFamily:'Arial'}},
      tooltip: {valueSuffix: " mm"},pointInterval: 21600000,pointStart: hourgraphb.jstart2
    },{
      name: barotxt,color: "#9ACD32",data: smoothline(hourgraphb.barodata),
      tooltip: {valueSuffix: " hPa"},dashStyle: 'shortdot',yAxis: 2,pointInterval: 10800000,pointStart: hourgraphb.jstart
    }]
  };

  tsoptionsb = {
    chart: {marginBottom: 77,renderTo: "hourgraphb"},
    xAxis: [xAxis2,dailyX],
    yAxis: tsYAxis,
    series: [{
      name: "CAPE",color:"#EE7621",type:"areaspline",data:hourgraphb.capedata,
      tooltip: {valueSuffix: " J/kg"},pointInterval: 10800000,pointStart: hourgraphb.jstart
    },{
      name: dewtxt,color:"#6CA6CD",yAxis:1,data:hourgraphb.dewdata,
      tooltip: {valueSuffix: "°C"},pointInterval: 10800000,pointStart: hourgraphb.jstart
    },{
      name: barotxt,color:"#9ACD32",dashStyle: 'shortdot',yAxis:2,data:hourgraphb.barodata,
      tooltip: {valueSuffix: " hPa"},pointInterval: 10800000,pointStart: hourgraphb.jstart
    }]
  };
  
  snowoptionsb = {
    chart: {marginBottom: 77,renderTo: "hourgraphb"},
    xAxis: [xAxis2,dailyX],
    yAxis: snowYAxis,
      series: [{
        name:snowtxt,color:"#87CEFA",type:"areaspline",data:hourgraphb.snowdata,
        tooltip: {valueSuffix: " cm"},pointInterval: 10800000,pointStart: hourgraphb.jstart
      }]
  };
  
  extraoptions = {
    chart: {marginBottom: 77,renderTo: "hourgraphc"},
    xAxis: [xAxis1,dailyX],
    yAxis: extraYAxis,
    series: [{
      name: "CAPE",color:"#EE7621",type:"areaspline",data:hourgraphc.capedata,
      tooltip: {valueSuffix: " J/kg"},pointInterval: 3600000,pointStart: hourgraphc.jstart
    },{
      name: "Lifted Index",data: smoothline(hourgraphc.lftxdata),tooltip: {valueSuffix: " K"},yAxis:1,
      zIndex: 1,color: '#FF3333',negativeColor: '#48AFE8',pointInterval: 3600000,pointStart: hourgraphc.jstart
    }]
  };
  
  tempoptionsd = {
    chart: {marginBottom: 47,renderTo: "hourgraphd"},
    xAxis: [xAxis3,dailyX],
    yAxis: compYAxis,
    series: [{
      name: temptxt,color:"#FF3333",data:hourgraphd.ewntemp,tooltip: {valueSuffix: "°C"},lineWidth: 2,
      pointInterval: 10800000,pointStart: hourgraphd.jstart,
		  marker: {enabled: true,fillColor: 'white',lineWidth: 2,lineColor: "#FF3333"}
    },{
      name: rangetxt,color:"#FF3333",data:hourgraphd.ewnmmtemp,type: "arearange",tooltip: {valueSuffix: "°C"},lineWidth: 0,
      linkedTo: ':previous',fillOpacity: 0.3,zIndex: 0, pointInterval: 10800000,pointStart: hourgraphd.jstart
    }]
  };
  prmsloptionsd = {
    chart: {marginBottom: 47,renderTo: "hourgraphd"},
    xAxis: [xAxis3,dailyX],
    yAxis: compYAxis,
    series: [{
      name: barotxt,color:"#66CD00",data:hourgraphd.ewnprmsl,tooltip: {valueSuffix: " hPa"},yAxis:0,lineWidth: 2,
      pointInterval: 10800000,pointStart: hourgraphd.jstart,
		  marker: {enabled: true,fillColor: 'white',lineWidth: 2,lineColor: "#66CD00"}
    },{
      name: rangetxt,color:"#66CD00",data:hourgraphd.ewnmmprmsl,type: "arearange",tooltip: {valueSuffix: " hPa"},lineWidth: 0,
      linkedTo: ':previous',fillOpacity: 0.3,zIndex: 0, pointInterval: 10800000,pointStart: hourgraphd.jstart
    }]
  };
  wspdoptionsd = {
    chart: {marginBottom: 47,renderTo: "hourgraphd"},
    xAxis: [xAxis3,dailyX],
    yAxis: compYAxis2,
    series: [{
      name: windtxt+" ",color:"#36648B",data:hourgraphd.ewnwspd,yAxis:0,lineWidth: 2,pointInterval: 10800000,
      tooltip: {valueSuffix: " "+wiset},pointStart: hourgraphd.jstart,
      marker: {enabled: true,fillColor: 'white',lineWidth: 2,lineColor: "#36648B"}
    },{
      name: rangetxt,color:"#36648B",data:hourgraphd.ewnmmwspd,type: "arearange",tooltip: {valueSuffix: " "+wiset},lineWidth: 0,
      linkedTo: ':previous',fillOpacity: 0.3,zIndex: 0, pointInterval: 10800000,pointStart: hourgraphd.jstart
    }]
  };
  prec3hoptionsd = {
    chart: {marginBottom: 47,renderTo: "hourgraphd"},
    xAxis: [xAxis3,dailyX],
    yAxis: compYAxis2,
    series: [{
      name: prectxt,color:"#4572A7",data:hourgraphd.ewnprec3h,tooltip: {valueSuffix: " mm"},yAxis:0,lineWidth: 2,
      pointInterval: 10800000,pointStart: hourgraphd.jstart,
      marker: {enabled: true,fillColor: 'white',lineWidth: 2,lineColor: "#4572A7"}
    },{
      name: rangetxt,color:"#4572A7",data:hourgraphd.ewnmmprec3h,type: "arearange",tooltip: {valueSuffix: " mm"},lineWidth: 0,
      linkedTo: ':previous',fillOpacity: 0.3,zIndex: 0, pointInterval: 10800000,pointStart: hourgraphd.jstart
    }]
  };
 
  
} // If map only

});  //EOF DOC-READY

} // dostuff

function smoothline(data) {
    var i = data.length,sum,value;
    while (i--) {
      data[i].value = value = data[i].y; // preserve value for tooltip
      sum = (data[i - 1] || data[i]).y + value + (data[i + 1] || data[i]).y;
      data[i].y = Math.max(value - 0.5, Math.min(sum / 3, value + 0.5));
    }
    return data;
}

  function drawgraphiconswx(svgdata, mainrec, plotleft){
    var mainrect = $("#"+mainrec+" .highcharts-series-group");
    $.each(svgdata, function(index2,value2){
      $.each(chart.series[0].points, function(index,value){
        if(value.category == value2.ictime ) {
          var xx = value.plotX + chart.plotLeft - plotleft;
          var yy = value.plotY + chart.plotTop - 40;
          $(mainrect).append('<svg class="svg-ic" height="40" width="40" viewBox="0 0 512 512" x ='+xx+' y="'+yy+'"><g>'+geticon(value2.cond)+'</g></svg>');
        }
      });
    });
  }
  
  function drawgraphiconswind(svgdata){
    $.each(svgdata, function(index2,value2){
      $.each(chart.series[0].points, function(index,value){
        var arrow, x, y,level,path;
        if(value.category == value2.time ) {
          level = value2.bft;
          x = value.plotX + chart.plotLeft - 0;
          y = 280;
          path = ['M',0,7,'L',-1.5,7,0,10,1.5,7,0,7,0,-10];
          if (level === 0) {path = [];}
          if (level === 2) {path.push('M', 0, -8, 'L', 4, -8);} 
          else if (level >= 3) {path.push(0, -10, 7, -10);}
          if (level === 4) {path.push('M', 0, -7, 'L', 4, -7);} 
          else if (level >= 5) {path.push('M', 0, -7, 'L', 7, -7);}
          if (level === 5) {path.push('M', 0, -4, 'L', 4, -4);} 
          else if (level >= 6) {path.push('M', 0, -4, 'L', 7, -4);}
          if (level === 7) {path.push('M', 0, -1, 'L', 4, -1);} 
          else if (level >= 8) {path.push('M', 0, -1, 'L', 7, -1);}
          
          if (level === 0) {arrow = chart.renderer.circle(x, y, 10).attr({fill: 'none'});}
          else {
            arrow = chart.renderer.path(path).attr({
                      rotation: parseInt(value2.dir, 10),
                      translateX: x, // rotation center
                      translateY: y // rotation center
            });
          }
          arrow.attr({stroke: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black','stroke-width': 1.5,zIndex: 5})
            .add();
        }
      });
    });
  }
  
  function drawwindbox() {
    var xAxis = chart.xAxis[0],x,pos,max,isLong,isLast,i;
    for (pos = xAxis.min, max = xAxis.max, i = 0; pos <= max + 36e5; pos += 36e5, i ++) {
      isLast = pos === max + 36e5;
      x = Math.round(xAxis.toPixels(pos)) + (isLast ? 0.5 : -0.5);
      if (this.resolution > 36e5) {
        isLong = pos % this.resolution === 0;
      } else {
        isLong = i % 3 === 0;
      }
      isLong = 0;
      chart.renderer.path(['M', x, chart.plotTop + chart.plotHeight + (isLong ? 0 : 28),
        'L', x, chart.plotTop + chart.plotHeight + 32, 'Z'])
        .attr({'stroke': chart.options.chart.plotBorderColor,'stroke-width': 1})
        .add();
    }
  }

var shapes = new Array();
shapes["cloud"]="d=\"M441.953,142.352c-4.447-68.872-61.709-123.36-131.705-123.36c-59.481,0-109.766,39.346-126.264,93.429c-9.244-3.5-19.259-5.431-29.729-5.431c-42.84,0-78.164,32.08-83.322,73.523c-0.309-0.004-0.614-0.023-0.924-0.023c-36.863,0-66.747,29.883-66.747,66.747s29.883,66.746,66.747,66.746c4.386,0,8.669-0.436,12.819-1.243c20.151,27.069,52.394,44.604,88.734,44.604c31.229,0,59.429-12.952,79.533-33.772c15.071,15.091,35.901,24.428,58.913,24.428c31.43,0,58.783-17.42,72.955-43.127c11.676,5.824,24.844,9.106,38.777,9.106c48.047,0,86.998-38.949,86.998-86.996C508.738,185.895,480.252,151.465,441.953,142.352z\"/>";
shapes["lightning"]="class=\"lightning\" fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"m 3.4723994,8.5185577 3.0304576,-7.0710678 6.944799,0 -5.0191957,5.08233 4.1353117,0 -8.1127873,9.0913731 2.0834396,-7.1342026 z\"/>";
shapes["tornado"]="class=\"tornado\" fill-rule=\"evenodd\" d=\"M3.51795549,3.09677419 C4.55956613,1.38647295 9.11409512,0 16.017284,0 C22.9204728,0 27.3855446,1.64705965 28.5166124,3.09677419 C30.0790285,5.09935736 27.6364411,9.83203892 20.1837273,14.4516129 C13.8294775,18.3903027 9.14478347,22.2210455 12.8924519,23.7419355 C21.8177932,27.3640441 17.0588943,32 17.0588943,32 C17.0588943,32 17.0588947,28.9032253 13.934062,27.8709677 C8.65191269,26.1260613 6.64278758,24.7741935 4.55956617,22.7096774 C2.98366184,21.147923 1.2876824,16.2185972 6.64278762,11.3548387 C9.30758236,8.93454683 1.51950057,6.37819267 3.51795549,3.09677419 Z M27,4.03225806 C27,2.37540373 22.0751325,1.03225806 16,1.03225806 C9.92486745,1.03225806 5,2.37540373 5,4.03225806 C5,5.6891124 9.92486745,7.03225806 16,7.03225806 C22.0751325,7.03225806 27,5.6891124 27,4.03225806 Z M27,4.03225806\"/>";
shapes["hurricane"]="class=\"hurricane\" d=\"M 21.05263,282.71812 C 21.05263,263.14772 5.13832,247.26423 -14.46943,247.26423 C -20.99403,247.26423 -27.10709,249.01667 -32.36199,252.08515 C -25.97086,231.2175 -15.39876,214.28402 -1.84069,207.37258 C -28.92355,216.4934 -47.76995,248.20401 -50,282.68374 C -50,302.25413 -34.07718,318.17761 -14.46943,318.1776 C -8.02952,318.1776 -1.98598,316.46325 3.22446,313.46822 C -3.19061,334.0152 -13.85506,350.53308 -27.272,357.37258 C -0.62131,348.3973 18.34408,316.45182 21.05263,282.71812 z\"/>";
shapes["waterdrop"]="class=\"waterdrop\" fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M150.2 464.6h-.1c-15.5 0-28.1-12.7-28.1-28.2v-4c0-.3 0-.5.1-.8v-.3c1.4-11.6 9.6-22.2 16.9-31.7 5.9-7.6 10.9-14.1 10.9-19.5 0-.1.1-.1.2-.1h.1c.1 0 .1 0 .1.1 0 5.4 5.1 12 10.9 19.5 8 10.3 17 22 17 35v1.8c0 15.5-12.5 28.2-28 28.2zm25-42.9c0 .1 0 .1.1.2-.1-.1-.1-.2-.1-.2zm.4 1l.1.3-.1-.3zm.4 1l.1.4c0-.1-.1-.2-.1-.4zm.3 1.1l.2.5c-.1-.2-.1-.4-.2-.5zm.4 1l.3 1.1c-.1-.3-.2-.7-.3-1.1zm.4 1.6c.1.2.1.4.1.6l-.1-.6zm.2 1.1l.1.6c0-.2 0-.4-.1-.6zm.3 1.1l.1.6c-.1-.2-.1-.4-.1-.6zm.1 1.1c0 .2 0 .4.1.5 0-.1 0-.3-.1-.5zm.2 1.2v.5-.5zm.1 1.2v.4-.4z\"/>";
shapes["ice"]="class=\"ice\" points=\"153.317,416 173.313,457.373 194,416 \"/>";
shapes["snowflake"]="class=\"snowflake\" points=\"266.125,427.569 246.082,427.598 244.383,427.773 245.355,426.434 255.378,409.032 240.345,400.389 230.34,417.775 229.65,419.32 228.951,417.768 218.906,400.423 203.895,409.101 213.865,426.307 214.939,427.812 213.062,427.642 193.209,427.667 193.232,445.077 213.062,445.05 214.963,444.874 213.821,446.511 203.969,463.614 219.002,472.26 228.933,455.003 229.695,453.328 230.408,454.903 240.441,472.227 255.452,463.548 245.362,446.141 244.406,444.835 245.928,445.008 266.147,444.981 \"/>";
shapes["hail"]="class=\"hail\" cx=\"277.678\" cy=\"436.324\" r=\"26.895\"/>";
shapes["sun_1"]="class=\"sun_center\" d=\"M255.725,324.881c-37.958,0-68.84-30.882-68.84-68.84s30.881-68.839,68.84-68.839c37.958,0,68.839,30.881,68.839,68.839S293.683,324.881,255.725,324.881z\"/>";
shapes["sun_2"]="class=\"sun\" d=\"M128.363,195.126l11.058-19.152l34.855,20.125c-4.398,5.948-8.128,12.354-11.126,19.111L128.363,195.126z M267,115h-22v40.574c3-0.383,7.158-0.577,10.739-0.577c3.759,0,8.261,0.212,11.261,0.633V115z M196.093,174.419c5.982-4.368,12.412-8.059,19.18-11.011l-20.202-34.99l-19.152,11.058L196.093,174.419z M383.61,195.126l-11.058-19.152L337.33,196.31c4.384,5.96,8.097,12.377,11.076,19.142L383.61,195.126z M155.064,267c-0.387-4-0.583-7.198-0.583-10.8c0-3.736,0.21-7.2,0.626-11.2H115v22H155.064z M336.055,139.475l-19.153-11.058l-20.307,35.176c6.763,2.987,13.176,6.705,19.13,11.092L336.055,139.475z M316.9,383.665l19.153-11.059l-20.169-34.932c-5.949,4.399-12.354,8.132-19.109,11.131L316.9,383.665z M139.421,336.108l34.649-20.005c-4.38-5.965-8.087-12.385-11.062-19.148l-34.646,20.001L139.421,336.108z M397,267v-22h-40.659c0.415,4,0.626,7.464,0.626,11.2c0,3.602-0.196,6.8-0.583,10.8H397zM372.552,336.108l11.058-19.152l-35.062-20.243c-2.955,6.768-6.646,13.196-11.016,19.177L372.552,336.108z M195.072,383.665l20.019-34.674c-6.766-2.968-13.188-6.671-19.159-11.046l-20.012,34.661L195.072,383.665z M267,397v-40.149c-3,0.42-7.504,0.632-11.261,0.632c-3.581,0-7.739-0.193-10.739-0.577V397H267z M335.66,256.041c0-44.077-35.859-79.936-79.936-79.936c-44.077,0-79.937,35.859-79.937,79.936c0,44.077,35.859,79.937,79.937,79.937C299.801,335.978,335.66,300.118,335.66,256.041z M312.563,256.041c0,31.342-25.498,56.84-56.839,56.84c-31.342,0-56.84-25.498-56.84-56.84c0-31.341,25.498-56.839,56.84-56.839C287.065,199.202,312.563,224.7,312.563,256.041z\"/>";
shapes["moon"]="class=\"moon\" d=\"M248.082,263.932c-31.52-31.542-39.979-77.104-26.02-116.542c-15.25,5.395-29.668,13.833-41.854,26.02  c-43.751,43.75-43.751,114.667,0,158.395c43.729,43.73,114.625,43.752,158.374,0c12.229-12.186,20.646-26.604,26.021-41.854  C325.188,303.91,279.604,295.451,248.082,263.932z\"/>";
shapes["fog"]="class=\"fog\" d=\"M392,432H120v23h272V432z\"/>";
shapes["nan"]="class=\"nan\" d=\"M 273.79665,278.39483 L 176.01526,278.41797 C 176.01516,262.63035 180.66722,249.81298 184.24805,239.96582 C 187.82867,230.11898 193.15907,221.12648 200.23926,212.98828 C 207.31921,204.85046 223.22902,190.52755 247.96875,170.01953 C 261.15216,159.27758 267.74395,149.43059 267.74414,140.47852 C 267.74395,131.52696 265.0991,124.56896 259.80957,119.60449 C 254.51968,114.64058 246.50374,112.15849 235.76172,112.1582 C 224.20558,112.15849 214.64342,115.98336 207.0752,123.63281 C 199.50672,131.28282 194.6646,144.62916 192.54883,163.67188 L 97.822266,151.95313 C 101.07745,117.12268 113.73206,89.087225 135.78613,67.84668 C 157.84009,46.606799 191.65353,35.986692 237.22656,35.986328 C 272.70814,35.986692 301.35395,43.392284 323.16406,58.203125 C 352.78619,78.222978 367.59737,104.91566 367.59766,138.28125 C 367.59737,152.11613 363.7725,165.46247 356.12305,178.32031 C 348.47304,191.17859 332.84805,206.88496 309.24805,225.43945 C 291.37819,235.59876 274.96586,253.5497 274.54302,262.53489 L 273.79665,278.39483 z M 175.70313,312.35352 L 275.06836,312.35352 L 275.06836,400 L 175.70313,400 L 175.70313,312.35352 z\"/>";

  function geticon(ic){
    var svg;
    switch (ic) {
    case "hurricane":
      svg='<path transform="scale(2) translate(140,-155)" '+shapes["hurricane"];
      break;
    case "tornado":
      svg='<path transform="scale(8) translate(16,18)" '+shapes["tornado"];
      break;
    case "tornadothunder":
      svg='<path transform="scale(5) translate(25,55)" '+shapes["tornado"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(18,21)" '+shapes["lightning"];
      break;
    case "heavy_thunder":
      svg='<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<path transform="scale(12) translate(18,21)" '+shapes["lightning"];
      break;
    case "rainthunder":
      svg='<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<path transform="translate(120,-50)" '+shapes["waterdrop"]+'<path transform="translate(190,-50)" '+shapes["waterdrop"];
      break;
    case "hailthunder":
      svg='<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<circle  transform="translate(0,-75)" '+shapes["hail"]+'<circle  transform="translate(70,-75)" '+shapes["hail"];
      break;
    case "snowthunder":
      svg='<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<polygon transform="translate(35,-70)" '+shapes["snowflake"]+'<polygon transform="translate(125,-70)" '+shapes["snowflake"];
      break;
    case "rainthundershower":
      svg='<path transform="scale(0.80) translate(0,-20)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(0,-20)" '+shapes["sun_2"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<path transform="translate(120,-50)" '+shapes["waterdrop"]+'<path transform="translate(190,-50)" '+shapes["waterdrop"];
      break;
    case "hailthundershower":
      svg='<path transform="scale(0.80) translate(0,-20)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(0,-20)" '+shapes["sun_2"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<circle  transform="translate(0,-75)" '+shapes["hail"]+'<circle  transform="translate(70,-75)" '+shapes["hail"];
      break;
    case "snowthundershower":
      svg='<path transform="scale(0.80) translate(0,-20)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(0,-20)" '+shapes["sun_2"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<polygon transform="translate(35,-70)" '+shapes["snowflake"]+'<polygon transform="translate(125,-70)" '+shapes["snowflake"];
      break;
    case "nt_rainthundershower":
      svg='<path transform="scale(0.85) translate(20,-50)" '+shapes["moon"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<path transform="translate(120,-50)" '+shapes["waterdrop"]+'<path transform="translate(190,-50)" '+shapes["waterdrop"];
      break;
    case "nt_hailthundershower":
      svg='<path transform="scale(0.85) translate(20,-50)" '+shapes["moon"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<circle  transform="translate(0,-75)" '+shapes["hail"]+'<circle  transform="translate(70,-75)" '+shapes["hail"];
      break;
    case "nt_snowthundershower":
      svg='<path transform="scale(0.85) translate(20,-50)" '+shapes["moon"]+'<path transform="scale(0.60) translate(170,170)" class="thundercloud" '+shapes["cloud"]+'<path transform="scale(12) translate(8,21)" '+shapes["lightning"]+'<polygon transform="translate(35,-70)" '+shapes["snowflake"]+'<polygon transform="translate(125,-70)" '+shapes["snowflake"];
      break;
    case "clear":
      svg='<path '+shapes["sun_1"]+'<path '+shapes["sun_2"];
      break;
    case "mostlyclear":
      svg='<path '+shapes["sun_1"]+'<path '+shapes["sun_2"]+'<path transform="scale(0.30) translate(760,760)" class="light_cloud" '+shapes["cloud"];
      break;
    case "partlycloudy":
      svg='<path transform="scale(0.80) translate(40,70)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(40,70)" '+shapes["sun_2"]+'<path transform="scale(0.42) translate(420,500)" class="middle_cloud" '+shapes["cloud"];
      break;
    case "mostlycloudy":
      svg='<path transform="scale(0.80) translate(20,50)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,50)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,360)" class="dark_cloud" '+shapes["cloud"];
      break;
    case "nt_clear":
      svg='<path '+shapes["moon"];
      break;
    case "nt_mostlyclear":
      svg='<path '+shapes["moon"]+'<path transform="scale(0.30) translate(760,760)" class="light_cloud" '+shapes["cloud"];
      break;
    case "nt_partlycloudy":
      svg='<path transform="scale(0.92) translate(30,10)" '+shapes["moon"]+'<path transform="scale(0.42) translate(420,500)" class="middle_cloud" '+shapes["cloud"];
      break;
    case "nt_mostlycloudy":
      svg='<path transform="scale(0.85) translate(40,40)" '+shapes["moon"]+'<path transform="scale(0.50) translate(260,360)" class="dark_cloud" '+shapes["cloud"];
      break;
    case "cloudy":
      svg='<path transform="scale(0.60) translate(160,260)" class="dark_cloud" '+shapes["cloud"];
      break;
    case "rain4":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(5,-50)" '+shapes["waterdrop"]+'<path transform="translate(75,-50)" '+shapes["waterdrop"]+'<path transform="translate(145,-50)" '+shapes["waterdrop"]+'<path transform="translate(215,-50)" '+shapes["waterdrop"];
      break;
    case "rain3":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(15,-50)" '+shapes["waterdrop"]+'<path transform="translate(110,-50)" '+shapes["waterdrop"]+'<path transform="translate(205,-50)" '+shapes["waterdrop"];
      break;
    case "rain2":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(65,-50)" '+shapes["waterdrop"]+'<path transform="translate(165,-50)" '+shapes["waterdrop"];
      break;
    case "rain1":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(115,-50)" '+shapes["waterdrop"];
      break;
    case "rainshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(115,-40)" '+shapes["waterdrop"];
      break;
    case "moderaterainshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(65,-40)" '+shapes["waterdrop"]+'<path transform="translate(165,-40)" '+shapes["waterdrop"];
      break;
    case "heavyrainshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(40,-40)" '+shapes["waterdrop"]+'<path transform="translate(115,-40)" '+shapes["waterdrop"]+'<path transform="translate(190,-40)" '+shapes["waterdrop"];
      break;
    case "nt_rainshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(115,-40)" '+shapes["waterdrop"];
      break;
    case "nt_moderaterainshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(65,-40)" '+shapes["waterdrop"]+'<path transform="translate(165,-40)" '+shapes["waterdrop"];
      break;
    case "nt_heavyrainshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(40,-40)" '+shapes["waterdrop"]+'<path transform="translate(115,-40)" '+shapes["waterdrop"]+'<path transform="translate(190,-40)" '+shapes["waterdrop"];
      break;
    case "freezingrain":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="scale(1.5) translate(-50,-185)" '+shapes["ice"]+'<path transform="translate(115,-50)" '+shapes["waterdrop"]+'<path transform="translate(205,-50)" '+shapes["waterdrop"];
      break;
    case "drizzle":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<path transform="scale(0.60) translate(85,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(165,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(245,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(325,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(405,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(485,170)" '+shapes["waterdrop"];
      break;
    case "freezingdrizzle":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="scale(1.2) translate(-35,-135)" '+shapes["ice"]+'<path transform="scale(0.60) translate(215,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(295,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(375,170)" '+shapes["waterdrop"]+'<path transform="scale(0.60) translate(455,170)" '+shapes["waterdrop"];
      break;
    case "snow3":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-60,-60)" '+shapes["snowflake"]+'<polygon transform="translate(30,-60)" '+shapes["snowflake"]+'<polygon transform="translate(120,-60)" '+shapes["snowflake"];
      break;
    case "snow2":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-25,-60)" '+shapes["snowflake"]+'<polygon transform="translate(90,-60)" '+shapes["snowflake"];break;
    case "snow1":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(35,-60)" '+shapes["snowflake"];
      break;
    case "heavysnowshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-55,-55)" '+shapes["snowflake"]+'<polygon transform="translate(35,-55)" '+shapes["snowflake"]+'<polygon transform="translate(125,-55)" '+shapes["snowflake"];
      break;
    case "moderatesnowshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-25,-55)" '+shapes["snowflake"]+'<polygon transform="translate(90,-55)" '+shapes["snowflake"];
      break;
    case "snowshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(35,-55)" '+shapes["snowflake"];
      break;
    case "nt_heavysnowshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-55,-55)" '+shapes["snowflake"]+'<polygon transform="translate(35,-55)" '+shapes["snowflake"]+'<polygon transform="translate(125,-55)" '+shapes["snowflake"];
      break;
    case "nt_moderatesnowshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-25,-55)" '+shapes["snowflake"]+'<polygon transform="translate(90,-55)" '+shapes["snowflake"];
      break;
    case "nt_snowshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(35,-55)" '+shapes["snowflake"];
      break;
    case "heavysleet":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-55,-60)" '+shapes["snowflake"]+'<path transform="translate(115,-50)" '+shapes["waterdrop"]+'<polygon transform="translate(125,-60)" '+shapes["snowflake"];
      break;
    case "sleet":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(60,-50)" '+shapes["waterdrop"]+'<polygon transform="translate(90,-60)" '+shapes["snowflake"];
      break;
    case "heavysleetshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-55,-55)" '+shapes["snowflake"]+'<path transform="translate(115,-40)" '+shapes["waterdrop"]+'<polygon transform="translate(125,-55)" '+shapes["snowflake"];
      break;
    case "sleetshowers":
      svg='<path transform="scale(0.80) translate(20,10)" '+shapes["sun_1"]+'<path transform="scale(0.80) translate(20,10)" '+shapes["sun_2"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(60,-40)" '+shapes["waterdrop"]+'<polygon transform="translate(90,-55)" '+shapes["snowflake"];
      break;
    case "nt_heavysleetshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<polygon transform="translate(-55,-55)" '+shapes["snowflake"]+'<path transform="translate(115,-40)" '+shapes["waterdrop"]+'<polygon transform="translate(125,-55)" '+shapes["snowflake"];
      break;
    case "nt_sleetshowers":
      svg='<path transform="scale(0.85) translate(40,10)" '+shapes["moon"]+'<path transform="scale(0.50) translate(280,310)" class="dark_cloud" '+shapes["cloud"]+'<path transform="translate(60,-40)" '+shapes["waterdrop"]+'<polygon transform="translate(90,-55)" '+shapes["snowflake"];
      break;
    case "hail":
      svg='<path transform="scale(0.60) translate(170,170)" class="dark_cloud" '+shapes["cloud"]+'<circle  transform="translate(-120,-75)" '+shapes["hail"]+'<circle  transform="translate(-50,-75)" '+shapes["hail"]+'<circle  transform="translate(20,-75)" '+shapes["hail"]+'<circle  transform="translate(90,-75)" '+shapes["hail"];
      break;
    case "fog":
      svg='<path '+shapes["sun_1"]+'<path '+shapes["sun_2"]+'<path transform="translate(0,-75)" '+shapes["fog"]+'<path transform="translate(0,-120)" '+shapes["fog"]+'<path transform="translate(0,-165)" '+shapes["fog"]+'<path transform="translate(0,-210)" '+shapes["fog"]+'<path transform="translate(0,-255)" '+shapes["fog"]+'<path transform="translate(0,-300)" '+shapes["fog"];
      break;
    case "nt_fog":
      svg='<path '+shapes["moon"]+'<path transform="translate(0,-75)" '+shapes["fog"]+'<path transform="translate(0,-120)" '+shapes["fog"]+'<path transform="translate(0,-165)" '+shapes["fog"]+'<path transform="translate(0,-210)" '+shapes["fog"]+'<path transform="translate(0,-255)" '+shapes["fog"]+'<path transform="translate(0,-300)" '+shapes["fog"];
      break;
    default:
      svg='<path transform="scale(0.50) translate(250,300)" '+shapes["nan"];
      break;
  }
  return svg;
}

if(!dateFormat){
var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	return function (date, mask, utc) {
		var dF = dateFormat;
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");
		mask = String(dF.masks[mask] || mask || dF.masks["default"]);
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: fdaynames,
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};
}else{
dateFormat.i18n = {
	dayNames: fdaynames,
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};
}