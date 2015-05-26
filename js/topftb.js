// JavaScript Document
var ACPuzzleOptions = { theme:'white',lang:'en',size:'300x150'};
(function($, self){

if(!$ || !self) {
	return;
}

for(var i=0; i<self.properties.length; i++) {
	var property = self.properties[i],
		camelCased = StyleFix.camelCase(property),
		PrefixCamelCased = self.prefixProperty(property, true);
	
	$.cssProps[camelCased] = PrefixCamelCased;
}

})(window.jQuery, window.PrefixFree);
if (typeof String.prototype.startsWith != 'function') {
  String.prototype.startsWith = function (str){
    return this.slice(0, str.length) == str;
  };
}
$(document).ready(function(){
	if(typeof CKEDITOR === 'object'){
		CKEDITOR.on('instanceReady',function(){
			$('.cke_top, .cke_bottom').css('background','#CCC').css('box-shadow','none');
			$('.cke_button, .cke_combo_button').css('background','#E4E4E4').css('background-image','none !important');
			$('.cke_toolgroup').css('background-image','none');
		});
	}
	var triggered = false;
	if($('#sponsoredservers').length > 0){
		$(document).scroll(function() {
			if(!triggered){
				//$('#sponsoredservers').trigger('click'); TODO UNCOMMENT
				triggered = true;
			}
        });
	}
	if($('#server').length > 0){
		$('#server #display').css('height', 0).stop();
		setTimeout(function(){$(document).trigger("scroll")}, 2000);
		$(document).scroll(function() {
			if(!triggered){
				$('#server #display').animate({height: "800px"}, 2500, function(){$('#server #display').removeAttr('style')});
				$('#server #style').css('border-top', 'none');
				triggered = true;
			}
        });
	}
	if($('.topform').length > 0){
		$('.topform small').css('display', 'none');
		$('.topform .formshow > *').each(function(index, element) {
            $width = $(this).width() + 40;
			$(this).css('left', "-" + $width + "px").stop().animate({left: "0px"}, 1500, function() {$('.topform small').css('display', 'inline-block');});
        });
	}
	$('#pagebar ul a:not(.arrow)').hover(function(e) {
		$left = $(this).position().left + ($(this).width()/2) - 77;
		$('#pagebar #selector').stop().animate({left: $left}, 500);
	});
	$('#pagebar').mouseout(function(e) {
		$result = $('#pagebar .active');
		$left = $result.position().left + ($result.width()/2) - 77;
		$('#pagebar #selector').stop().animate({left: $left}, 500);
    });
	if($('#pagebar').length)
		$('#pagebar').trigger('mouseout');
	$('a').click(function() {
		try{
			if(!$('<a>').prop('href', $(this).attr('href')).prop('hostname').startsWith($(location).attr('hostname'))){
				$(this).attr("target", "_blank");
			}
		}catch(err){}
	});
	$('.ip, #serverip').click(function() {
		$(this).select();
	});
	$('.ddm-nav').hover(
		function() {
			$('.arrow', this).css({
				"transform": "rotate(180deg)",
				"top": "13px"
			});
			var time = $('#header #navbar').height() > 100 ? 50 : 500;
			if($(this).get(0) != $('.ddm-nav:first').get(0)){
				$(this).addClass('open');
				$('#header #navbar').stop().animate({height: "120px"}, time);
			}
		}, function() {
			$('.arrow', this).css({
				"top": "",
				"-webkit-transform": ""
			});
			if($(this).get(0) != $('.ddm-nav:first').get(0)){
				$(this).removeClass('open');
				$('#header #navbar').stop().animate({height: "60px"}, 500);
			}
	});
	$('.dropdown-group').click(function() {
		$(this).toggleClass('open');
	});
	var open = false;
	$('#sponsoredservers').click(function() {
		if(open == 2){
			$(this).removeAttr('style');
			$('#buffer', this).removeAttr('style');
			$('#buffer > ul', this).removeAttr('style');
			open = false;
		}else if(open == 0){
			open = 1;
			$(this).css({
				"background": "url(i/Core/Sponsored/BG-Large.png) no-repeat",
				"padding-top":"53px"
			});
			$(this).stop().animate({
				"height": "564px"
			}, 1000);
			$('#buffer', this).css({
				"display": "block"
			});
			$('#buffer', this).stop().animate({
				"height": "515px"
			}, 1000, function(){
				$('> ul', this).css({
					"display": "block"
				});
				$('.server-sponsored').css({"margin-left": "-412px"}).animate({"margin-left": "0px"}, 2000);
				$('#buffer').stop().animate({
					"width": "825px"
				}, 2000, function(){open = 2;});
			});
		}
	});
	$('#edit .page').click(function(e) {
		$('#edit form').hide();
		$("#"+$(this).attr('id').replace("f","")).show();
		$('.topform .formshow > *').each(function(index, element) {
            $width = $(this).width() + 40;
			$(this).css('left', "-" + $width + "px").stop().animate({left: "0px"}, 1500);
        });
	});
	$('#display ul li').click(function(e) {
		var name = $(this).text().toLowerCase();
		if(name == "website")
			return;
		$('.on').removeClass('on');
		$('#'+name).addClass("on");
		if(name == "statistics")
        	chart.write("chartdiv");
	});
});

function formatTime(time) {
    var sec_num = parseInt(time, 10); // don't forget the second parm
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = hours+'h '+minutes+'min '+seconds+'sec';
    return time;
}
function confirmbid($min){
	if($("input[name='bid']").val().length == 0){
		alert("You must enter a bid to bid....");
		return false;
	}
	if($("input[name='bid']").val() < $min){
		alert("Your bid must be greater than the current minimum of £"+$min+"!");
		return false;
	}
	if(!validURL($("input[name='link']").val(), 'link for your ad'))
		return false;
	if(!validURL($("input[name='img']").val(), 'url for the image'))
		return false;
	if($(".terms:checked").length < 2){
		alert("You must agree to both conditions!");
		return false;
	}
}

function validURL(str, type) {
	if(!str || str === "")
		return true;
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
    '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
  if(!pattern.test(str)) {
    alert("You must enter a valid "+type+"!");
    return false;
  } else {
    return true;
  }
}

function toggleLogin(){
	if($('#login').is(':visible')){
		$('#login').css('display', 'none');
	}else{
		$('#login').css('display', 'block');
	}
}

/*
    <div id="chartdiv" style="width:90%; height: 400px;"></div>
	<script src="js/amcharts.js" type="text/javascript"></script>
    <script src="js/serial.js" type="text/javascript"></script>
        
        <script type="text/javascript">
            var chartData = [
                {
                    "date": "2012-01-01",
                    "distance": 227,
                    "duration": 408
                },
                {
                    "date": "2012-01-02",
                    "distance": 371,
                    "duration": 482
                },
                {
                    "date": "2012-01-03",
                    "distance": 433,
                    "duration": 562
                },
                {
                    "date": "2012-01-04",
                    "distance": 345,
                    "duration": 379
                },
                {
                    "date": "2012-01-05",
                    "distance": 480,
                    "duration": 501
                },
                {
                    "date": "2012-01-06",
                    "distance": 386,
                    "duration": 443
                },
                {
                    "date": "2012-01-07",
                    "distance": 348,
                    "duration": 405
                },
                {
                    "date": "2012-01-08",
                    "distance": 238,
                    "duration": 309
                },
                {
                    "date": "2012-01-09",
                    "distance": 218,
                    "duration": 287
                },
                {
                    "date": "2012-01-10",
                    "distance": 349,
                    "duration": 485
                },
                {
                    "date": "2012-01-11",
                    "distance": 603,
                    "duration": 890
                },
                {
                    "date": "2012-01-12",
                    "distance": 534,
                    "duration": 810
                }
            ];
            var chart;

            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.pathToImages = "i/Core/Servers/amchart/";
                chart.categoryField = "date";
                chart.dataDateFormat = "YYYY-MM-DD";
                chart.marginTop = 0;

                // AXES
                // category axis
                var categoryAxis = chart.categoryAxis;
                categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
                categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD                
                categoryAxis.autoGridCount = false;
                categoryAxis.gridCount = 50;
                categoryAxis.gridAlpha = 0;
                categoryAxis.gridColor = "#000000";
                categoryAxis.axisColor = "#555555";
                // we want custom date formatting, so we change it in next line
                categoryAxis.dateFormats = [{
                    period: 'DD',
                    format: 'DD'
                }, {
                    period: 'WW',
                    format: 'MMM DD'
                }, {
                    period: 'MM',
                    format: 'MMM'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }];


                // Distance value axis 
                var distanceAxis = new AmCharts.ValueAxis();
                distanceAxis.title = "count (#)";
                distanceAxis.gridAlpha = 0;
                distanceAxis.position = "left";
                distanceAxis.inside = true;
                distanceAxis.axisAlpha = 0;
                chart.addValueAxis(distanceAxis);

                // GRAPHS
                // duration graph
                var durationGraph = new AmCharts.AmGraph();
                durationGraph.title = "duration";
                durationGraph.valueField = "duration";
                durationGraph.type = "smoothedLine";
              //  durationGraph.valueAxis = durationAxis; // indicate which axis should be used
                durationGraph.lineColor = "#CC0000";
                durationGraph.balloonText = "[[value]]";
                durationGraph.lineThickness = 1;
                durationGraph.legendValueText = "[[value]]";
                durationGraph.bullet = "square";
                durationGraph.bulletBorderColor = "#CC0000";
                durationGraph.bulletBorderAlpha = 1;
                durationGraph.bulletBorderThickness = 1;
                chart.addGraph(durationGraph);

                // distance graph
                var distanceGraph = new AmCharts.AmGraph();
                distanceGraph.valueField = "distance";
                distanceGraph.title = "distance";
                distanceGraph.type = "column";
                distanceGraph.fillAlphas = 0.3;
                distanceGraph.valueAxis = distanceAxis; // indicate which axis should be used
                distanceGraph.balloonText = "[[value]] miles";
                distanceGraph.legendValueText = "[[value]] mi";
                distanceGraph.legendPeriodValueText = "total: [[value.sum]] mi";
                distanceGraph.lineColor = "#09F";
                distanceGraph.lineAlpha = 0;
                chart.addGraph(distanceGraph);

                // CURSOR                
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.zoomable = true;
                chartCursor.categoryBalloonDateFormat = "DD";
                chartCursor.cursorAlpha = 0;
                chart.addChartCursor(chartCursor);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.bulletType = "round";
                legend.equalWidths = false;
                legend.valueWidth = 120;
                legend.color = "#000000";
                legend.useGraphSettings = true;
                chart.addLegend(legend);
                // WRITE                

                var chartScrollbar = new AmCharts.ChartScrollbar();
                chart.addChartScrollbar(chartScrollbar);
				                
                chart.write("chartdiv");
            });
			
        </script>*/