$(function () {
	
	showMap(HOME, ID_USER, W_NUMBER);
	$("#pulse_canvas").hide();
	
	
	var updateLegendTimeout = null;
    var latestPosition = null;
    
    function updateLegend() {
        updateLegendTimeout = null;
        
        var pos = latestPosition;
        
        var axes = plot.getAxes();
        if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
            pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
            return;

        var i, j, dataset = plot.getData();
        index_data = [];
        
        for (i = 0; i < dataset.length; ++i) {
            var series = dataset[i];
            
            /* find the nearest points, x-wise */
            for (j = 0; j < series.data.length; ++j)
                if (series.data[j][0] > pos.x)
                    break;
            
            /* now interpolate */
            var y, p1 = series.data[j - 1], p2 = series.data[j];
            if (p1 == null)
                y = p2[1];
            else if (p2 == null)
                y = p1[1];
            else
                y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);
            index_data[i] = y;
        }
    }
    
    var updatePulseLegendTimeout = null;
    
    function updatePulseLegend() {
        updatePulseLegendTimeout = null;
        
        var pos = latestPosition;
        
        var axes = pulse_plot.getAxes();
        if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
            pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
            return;

        var i, j, dataset = pulse_plot.getData();
        pulse_data = [];
        
        for (i = 0; i < dataset.length; ++i) {
            var series = dataset[i];
            
            /* find the nearest points, x-wise */
            for (j = 0; j < series.data.length; ++j)
                if (series.data[j][0] > pos.x)
                    break;
            
            /* now interpolate */
            var y, p1 = series.data[j - 1], p2 = series.data[j];
            if (p1 == null)
                y = p2[1];
            else if (p2 == null)
                y = p1[1];
            else
                y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);
            pulse_data[i] = y;
        }
    }
    
    link_marker_image = setMarkerImage( HOME + 'img/workout/cycling_moving_mini.png' , 'small');
    
    $('#chart_canvas').mousemove(function(e) {
    	if(typeof index_data != 'undefined'){
    		var position = $('#chart_canvas').position();
    		
    		$('#info').remove();
    		
    		$('body').append('<div class="white-block" id="info" style="font-family: Arial; font-size: 10pt; position: absolute; background-color: white; opacity: 0.7;"></div>');
    		
    		$('#info').html('<b style="color: #67BCFA">Altitude: ' + floorNumber(index_data[0],2) + ' m</b><br><b style="color: #045590"> Speed: ' + floorNumber(index_data[1]/7, 2) + ' km/h</b>');
    		$('#info').css('left', e.clientX + 10);
    		$('#info').css('top', position.top + 10);
    	}
    	if(typeof link_marker != 'undefined' && typeof index_data != 'undefined') {
    		link_marker.setMap(null);
    		link_marker = setMarker(new google.maps.LatLng(index_data[2], index_data[3]), map, link_marker_image);
    	}
    	else {
    		if(typeof index_data != 'undefined') {
    			link_marker = setMarker(new google.maps.LatLng(index_data[2], index_data[3]), map, link_marker_image);
    		}
    	}
	});
    
    $('#pulse_canvas').mousemove(function(e) {
    	if(typeof pulse_data != 'undefined'){
    		var position = $('#pulse_canvas').position();
    		
    		$('#info').remove();
    		
    		$('body').append('<div class="white-block" id="info" style="font-family: Arial; font-size: 10pt; position: absolute; background-color: white; opacity: 0.7;"></div>');
    		
    		$('#info').html('<b style="color: #67BCFA">Pulse: ' + floorNumber(pulse_data[0],2) + ' bpm</b>');
    		$('#info').css('left', e.clientX + 10);
    		$('#info').css('top', position.top + 10);
    	}
	});
    
    $('#chart_canvas').mouseout(function() {
    	$('#info').remove();
    	if(typeof link_marker != 'undefined') {
    		link_marker.setMap(null);
    	}
    });
    
    $('#pulse_canvas').mouseout(function() {
    	$('#info').remove();
    });
    
    $("#chart_canvas").bind("plothover",  function (event, pos, item) {
        latestPosition = pos;
        if (!updateLegendTimeout)
            updateLegendTimeout = setTimeout(updateLegend, 50);
    });
    
    $("#pulse_canvas").bind("plothover",  function (event, pos, item) {
        latestPosition = pos;
        if (!updatePulseLegendTimeout)
            updatePulseLegendTimeout = setTimeout(updatePulseLegend, 50);
    });
    
    $('#months-picker').click(function(event) {
    	var target = $(event.target);
    	
    	if (target.get(0).tagName.toLowerCase() != 'input')
    		return;
    	
    	var month = target.val();
    	var year = $('#year').val();
    	updateCalendar(HOME, ID_USER, month, year);
	});
    
    $('#years-picker').change(function(event) {
    	var target = $(event.target);
    	if (target.get(0).tagName.toLowerCase() != 'select')
    		return;
    	for(i = 1; i < 13; i++) {
    		console.log($("label[for='radio"+i+"']").attr("aria-pressed"));
    		if($("label[for='radio"+i+"']").attr("aria-pressed") == "true") {
    			month = i;
    		}
    	}
    	var year = target.val();
    	updateCalendar(HOME, ID_USER, month, year);
    });
    
    $('#show_pulse').click(function (event) {
    	event.preventDefault();
    	$("#chart_canvas").hide("highlight", 500, function() {
    		$("#pulse_canvas").show("highlight", "slow");
    	});
    });
    
    $('#show_chart').click(function (event) {
    	event.preventDefault();
    	$("#pulse_canvas").hide("highlight", 500, function() {
    		$("#chart_canvas").show("highlight", "slow");
    	});
    });
});