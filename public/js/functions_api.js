/**
 * Create new marker in google map
 * @param position - object handler type of LatLng
 * @param map - map handler
 * @param image - image handler
 * @returns {google.maps.Marker}
 */
function setMarker(position, map, image) {
	var marker = new google.maps.Marker({
		position: position,
		map: map,
		icon: image
	});
	return marker;
}

/**
 * Create a new marker image
 * @param image_address - address of image file
 * @returns {google.maps.MarkerImage}
 */
function setMarkerImage(image_address) {
	var image = new google.maps.MarkerImage(
		image_address,
		new google.maps.Size(60, 60),
		new google.maps.Point(0,0), 
		new google.maps.Point(16,35) 
	);
	return image;
}

/**
 * Creation of marker tooltip
 * @param URL - site root
 * @param index - distance index in Km
 * @param speed - speed index in Km/h
 * @returns {InfoBox}
 */
function createTooltip(URL, index, speed) {
	speed = floorNumber(speed, 1);
	var box_text = '<div class=\"info-box\">Dst: ' + (index+1).toString() + ' km.<br> Spd: ' + speed.toString() + ' km/h</div>';
	var tooltip_options = {
			content: box_text,
			disableAutoPan: false,
			maxWidth: 0,
			pixelOffset: new google.maps.Size(-13, 0),
			zIndex: null,
			closeBoxMargin: "10px 2px 2px 2px",
			infoBoxClearance: new google.maps.Size(1, 1),
			isHidden: false,
			pane: "floatPane",
			enableEventPropagation: false,
			boxStyle: { 
				background : "url(" + URL + "img/workout/tooltip_middle.png) no-repeat",
			opacity: 1,
			width: "100px",
			height: "70px",
			color : 'white'
			}
		}
	var ibox = new InfoBox(tooltip_options);
	return ibox;
}

function defineImages(URL) {
	var images = [];
	images['cycling'] = setMarkerImage(URL + 'img/workout/cycling.png');
	images['downhill'] = setMarkerImage(URL + 'img/workout/bike_downhill.png');
	images['rising'] = setMarkerImage(URL + 'img/workout/bike_rising.png');
	images['flag'] = setMarkerImage(URL + 'img/workout/finish.png');
	return images;
}

/**
 * Draw map with polyline, markers and tooltips
 * @param URL - site root
 * @param result - database workout array
 */
function drawMap(URL, result) {
	
	var res_coords = result['points'];
	var res_markers = result['markers'];
	
	var marker = [];
	var coords = [];
	var current_marker;
	
	/* Center of the map indication (not right calculation)*/
	var center_index = Math.round(res_coords.length/2); 
	/* Google map options */
	var mapOptions = {
			zoom: 13,
			center: new google.maps.LatLng(parseFloat(res_coords[center_index].lat), parseFloat(res_coords[center_index].lan)),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: true
	}
	/* Creating of Google Map object */
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	/* Creating a marker images */
	var images = defineImages(URL);
	/* Creating of start marker */
	for(var i = 0; i < res_coords.length; i++) {
		coords.push(new google.maps.LatLng(parseFloat(res_coords[i].lat), parseFloat(res_coords[i].lan)));
	}
	
	for(var i = 0; i < res_markers.length; i++) {
		if(i == 0 || i == res_markers.length - 1) {
			current_marker = setMarker(new google.maps.LatLng(parseFloat(res_markers[i].lat), parseFloat(res_markers[i].lan)), map, images['flag']);
			if(i == res_markers.length - 1) {
				marker.push(current_marker);
				marker[i].tooltip = createTooltip(URL, floorNumber(parseFloat(res_markers[i].distance/1000), 1)-1, parseFloat(res_markers[i].speed));
			}
			else {
				marker.push(current_marker);
				marker[i].tooltip = createTooltip(URL, -1, parseFloat(res_markers[i].speed));
			}
		}
		else {
			if(res_markers[i].slope == 'flat') {
				current_marker = setMarker(new google.maps.LatLng(parseFloat(res_markers[i].lat), parseFloat(res_markers[i].lan)), map, images['cycling']);
			}
			if(res_markers[i].slope == 'up') {
				current_marker = setMarker(new google.maps.LatLng(parseFloat(res_markers[i].lat), parseFloat(res_markers[i].lan)), map, images['rising']);
			}
			if(res_markers[i].slope == 'down') {
				current_marker = setMarker(new google.maps.LatLng(parseFloat(res_markers[i].lat), parseFloat(res_markers[i].lan)), map, images['downhill']);
			}
			marker.push(current_marker);
			marker[i].tooltip = createTooltip(URL, i-1, parseFloat(res_markers[i].speed));
		}
		
		/* Adding mouseover event to show tooltip */
		google.maps.event.addListener(current_marker, 'mouseover', (function(marker, i) {
			return function() {
				marker[i].tooltip.open(map, this);
			}
		})(marker, i));
		
		/* Adding mouseout event to hide tooltip */
		google.maps.event.addListener(current_marker, 'mouseout', (function(marker, i) {
			return function() {
				marker[i].tooltip.close();
			}
		})(marker, i));
	}
	
	/* Drawing path on the map */
	var Path = new google.maps.Polyline({
		path: coords,
		strokeColor: "#339900",
		strokeOpacity: 1.0,
		strokeWeight: 5,
		zIndex: 0
	});
	Path.setMap(map);
}

/**
 * Show map and chart at the page 
 * @param URL - site root
 * @param id_user - user id
 * @param workout_number - workout number
 */
function showMap(URL, id_user, workout_number) {
	$.post(
		/* Post to workout controller to get array of lat/lng coordinates */
        URL+'workout/get',
        {
        	id_user : id_user,
        	workout_number : workout_number
        },
        function(result) {
        	drawMap(URL, result);
        	drawChart(result['points']);
        },
        'json'
	);
}

/**
 * Draw chart of altitude/speed
 * @param result - database workout array
 */
function drawChart(result) {
	var altitude_chart = [];
	
	var speed_chart = [];
	var distance = 0;
	var coords = []; 
	var lat_chart = [], lan_chart = [];
	
	for(var i = 0; i < result.length; i++) {
		coords.push(new google.maps.LatLng(result[i].lat, result[i].lan));
		distance = google.maps.geometry.spherical.computeLength(coords);
		altitude_chart.push([distance/1000, parseFloat(result[i].alt)]);
		speed_chart.push([distance/1000, parseFloat(result[i].speed*7)]);
		lat_chart.push([distance/1000, parseFloat(result[i].lat)]);
		lan_chart.push([distance/1000, parseFloat(result[i].lan)]);
	}
	
    plot = $.plot($("#chart_canvas"), [{ data: altitude_chart}, {data: speed_chart, lines: {fill: false}}, {data: lat_chart, lines: {show: false}}, {data: lan_chart, lines: {show: false}}], {
    	lines: { show: true, fill: true },
        crosshair: { mode: "x" },
        grid: { hoverable: true, autoHighlight: false }
    });
}