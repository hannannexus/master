$(function () {
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
    
    link_marker_image = setMarkerImage(link_image_url);
    
    $('#chart_canvas').mousemove(function(e) {
    	if(typeof index_data != 'undefined'){
    		var position = $('#chart_canvas').position();
    		$('#info').remove();
    		$('body').append('<div class="white-block" id="info" style="position: absolute; background-color: white; opacity: 0.7;"></div>');
    		$('#info').html('alt: ' + floorNumber(index_data[0],2) + '<br> spd: ' + floorNumber(index_data[1]/5, 2));
    		$('#info').css('left', e.clientX + 10);
    		$('#info').css('top', position.top + 10);
    	}
    	if(typeof link_marker != 'undefined') {
    		link_marker.setMap(null);
    		link_marker = setMarker(new google.maps.LatLng(index_data[2], index_data[3]), map, link_marker_image);
    	}
    	else {
    		link_marker = setMarker(new google.maps.LatLng(index_data[2], index_data[3]), map, link_marker_image);
    	}
    	
	});
    
    $('#chart_canvas').mouseout(function() {
    	$('#info').remove();
    	if(typeof link_marker != 'undefined') {
    		link_marker.setMap(null);
    	}
    });
    
    $("#chart_canvas").bind("plothover",  function (event, pos, item) {
        latestPosition = pos;
        if (!updateLegendTimeout)
            updateLegendTimeout = setTimeout(updateLegend, 50);
    });
});