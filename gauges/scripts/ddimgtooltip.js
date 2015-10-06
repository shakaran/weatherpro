/*! Image w/ description tooltip v2.0  -  For FF1+ IE6+ Opr8+
* Created: April 23rd, 2010. This notice must stay intact for usage
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
* Modified: M Crossley June 2011, January 2012
* v1.6.3
*/


/*
Each tooltip's syntax should be as follows:

  tooltip[x]=['path_to_image', 'optional desc', optional_CSS_object]

Where x should be an sequential integer starting from 0, with the following 1 to 3 components defined:
   1. Full path or URL to the tooltip image
   2. Description that is displayed beneath the image (optional)
   3. Object containing the desired CSS properties to add to the tooltip.
      The syntax should be:
      {property1:"cssvalue1", property2:"cssvalue2", etc}
      where "property" should be a valid CSS property, and "value" a valid CSS value.
      If more than one pair is defined, separate each pair with a comma.
*/

var ddimgtooltip={
  tiparray:function(){
    var tooltips=[];
    var style = {background:"#FFFFFF", color:"black", border:"2px ridge darkblue"};
    if (g_showPopupDataGraphs) {
        tooltips[0]=[(g_tipImgs[0][0] !== null ? g_imgPathURL + g_tipImgs[0][0] : null), " ", style];
        tooltips[1]=[(g_tipImgs[1][0] !== null ? g_imgPathURL + g_tipImgs[1][0] : null), " ", style];
        tooltips[2]=[(g_tipImgs[2] !== null ? g_imgPathURL + g_tipImgs[2] : null), " ", style];
        tooltips[3]=[(g_tipImgs[3] !== null ? g_imgPathURL + g_tipImgs[3] : null), " ", style];
        tooltips[4]=[(g_tipImgs[4][0] !== null ? g_imgPathURL + g_tipImgs[4][0] : null), " ", style];
        tooltips[5]=[(g_tipImgs[5] !== null ? g_imgPathURL + g_tipImgs[5] : null), " ", style];
        tooltips[6]=[(g_tipImgs[6] !== null ? g_imgPathURL + g_tipImgs[6] : null), " ", style];
        tooltips[7]=[(g_tipImgs[7] !== null ? g_imgPathURL + g_tipImgs[7] : null), " ", style];
        tooltips[8]=[(g_tipImgs[8] !== null ? g_imgPathURL + g_tipImgs[8] : null), " ", style];
        tooltips[9]=[(g_tipImgs[9] !== null ? g_imgPathURL + g_tipImgs[9] : null), " ", style];
    } else {
        tooltips[0]=[null, " ", style];
        tooltips[1]=[null, " ", style];
        tooltips[2]=[null, " ", style];
        tooltips[3]=[null, " ", style];
        tooltips[4]=[null, " ", style];
        tooltips[5]=[null, " ", style];
        tooltips[6]=[null, " ", style];
        tooltips[7]=[null, " ", style];
        tooltips[8]=[null, " ", style];
        tooltips[9]=[null, " ", style];
    }    
    return tooltips; //do not remove/change this line
  }(),

  tooltipoffsets: [20, -30], //additional x and y offset from mouse cursor for tooltips

  tipDelay: 1000,

//***** NO NEED TO EDIT BEYOND THIS POINT

  _delayTimer: 0,
  
  tipprefix: 'imgtip', //tooltip ID prefixes

  createtip:function($, tipid, tipinfo){
    if ($('#'+tipid).length==0){ //if this tooltip doesn't exist yet
      return $('<div id="' + tipid + '" class="ddimgtooltip" />').html(
        ((tipinfo[1])? '<div class="tipinfo" id="' + tipid + '_txt">' + tipinfo[1] + '</div>' : '') +
        (tipinfo[0] !== null ? '<div style="text-align:center"><img class="tipimg" id="' + tipid + '_img" src="' + tipinfo[0] + '" /></div>' : "")
        )
      .css(tipinfo[2] || {})
      .appendTo(document.body)
    }
    return null;
  },

  positiontooltip:function($, $tooltip, e){
    var x=e.pageX+this.tooltipoffsets[0], y=e.pageY+this.tooltipoffsets[1];
    var tipw=$tooltip.outerWidth(), tiph=$tooltip.outerHeight(), 
    x=(x+tipw>$(document).scrollLeft()+$(window).width())? x-tipw-(ddimgtooltip.tooltipoffsets[0]*2) : x
    y=(y+tiph>$(document).scrollTop()+$(window).height())? $(document).scrollTop()+$(window).height()-tiph-10 : y;
    $tooltip.css({left:x, top:y});
  },
  
  delaybox:function($, $tooltip, e){
    if (this.showTips){
      ddimgtooltip._delayTimer = setTimeout("ddimgtooltip.showbox('"+$tooltip.selector+"')", ddimgtooltip.tipDelay);
    }
  },
  
  //showbox:function($, $tooltip, e){
  showbox:function(tooltip){
    if (this.showTips){
      //$tooltip.show();
      //this.positiontooltip($, $tooltip, e);
      $(tooltip).show();
    }
  },

  hidebox:function($, $tooltip){
    clearTimeout(ddimgtooltip._delayTimer);
    $tooltip.hide();
  },

  showTips: false,
  
  init:function(targetselector){
    jQuery(document).ready(function($){
      var tiparray=ddimgtooltip.tiparray;
      var $targets=$(targetselector);
      if ($targets.length==0)
        return;
      var tipids=[];
      $targets.each(function(){
        var $target=$(this);
        //$target.attr('rel').match(/\[(\d+)\]/); //match d of attribute rel="imgtip[d]"
        $target.attr('id').match(/_(\d+)/); //match d of attribute id="tip_d"
        var tipsuffix=parseInt(RegExp.$1); //get d as integer
        var tipid=this._tipid=ddimgtooltip.tipprefix+tipsuffix; //construct this tip's ID value and remember it
        var $tooltip=ddimgtooltip.createtip($, tipid, tiparray[tipsuffix]);
        $target.mouseenter(function(e){
          var $tooltip=$("#"+this._tipid);
          //ddimgtooltip.showbox($, $tooltip, e);
          ddimgtooltip.delaybox($, $tooltip, e);
        })
        $target.mouseleave(function(e){
          var $tooltip=$("#"+this._tipid);
          ddimgtooltip.hidebox($, $tooltip);
        })
        $target.mousemove(function(e){
          var $tooltip=$("#"+this._tipid);
          ddimgtooltip.positiontooltip($, $tooltip, e);
        })
        if ($tooltip){ //add mouseenter to this tooltip (only if event hasn't already been added)
          $tooltip.mouseenter(function(){
            ddimgtooltip.hidebox($, $(this))
          });
        }
      });

    }); //end dom ready
  }
}

//ddimgtooltip.init("*[rel^=imgtip]");
//ddimgtooltip.init("[id^='tip_']");
