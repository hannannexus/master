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
function setMarkerImage(image_address, size) {
	if(size == 'small') {
		var image = new google.maps.MarkerImage(
				image_address,
				new google.maps.Size(60, 60),
				new google.maps.Point(0,0), 
				new google.maps.Point(13,30) 
			);
			return image;
	}
	else {
		var image = new google.maps.MarkerImage(
				image_address,
				new google.maps.Size(60, 60),
				new google.maps.Point(0,0), 
				new google.maps.Point(16,35) 
			);
			return image;
	}
}

/**
 * Creation of marker tooltip
 * @param URL - site root
 * @param index - distance index in Km
 * @param speed - speed index in Km/h
 * @returns {InfoBox}
 */
function createTooltip(URL, index, speed, time, lap) {
	speed = floorNumber(speed, 1);
	var box_text = '<div class=\"info-box\">' +
					'<b>' + (index+1).toString() + ' km' +
					'<br />' + time + 
					'<br />' + lap + 
					'<br />' + speed.toString() + ' km/h' +
					'</b></div>';
	
	var tooltip_options = {
			content: box_text,
			disableAutoPan: false,
			maxWidth: 0,
			pixelOffset: new google.maps.Size(-18, 0),
			zIndex: null,
			closeBoxMargin: "12px 3px 3px 3px",
			infoBoxClearance: new google.maps.Size(1, 1),
			isHidden: false,
			pane: "floatPane",
			enableEventPropagation: false,
			boxStyle: { 
				background : "url(" + URL + "img/workout/tooltip_blue.png) no-repeat",
				opacity: 1,
				width: "100px",
				height: "100px",
				color : 'white',
				paddingLeft: "27px"
			}
		}
	var ibox = new InfoBox(tooltip_options);
	return ibox;
}

function defineImages(URL) {
	var images = [];
	images['cycling'] = setMarkerImage(URL + 'img/workout/cycling_mini.png', 'small');
	images['downhill'] = setMarkerImage(URL + 'img/workout/bike_downhill_mini.png', 'small');
	images['rising'] = setMarkerImage(URL + 'img/workout/bike_rising_mini.png', 'small');
	images['flag'] = setMarkerImage(URL + 'img/workout/finish.png', 'big');
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
				marker[i].tooltip = createTooltip(URL, floorNumber(parseFloat(res_markers[i].distance/1000), 1)-1, parseFloat(res_markers[i].speed), res_markers[i].timediff, res_markers[i].lap);
			}
			else {
				marker.push(current_marker);
				marker[i].tooltip = createTooltip(URL, -1, parseFloat(res_markers[i].speed), res_markers[i].timediff, res_markers[i].lap);
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
			marker[i].tooltip = createTooltip(URL, i-1, parseFloat(res_markers[i].speed), res_markers[i].timediff, res_markers[i].lap);
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
		strokeColor: "#67BCFA",
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
	$("#calendar").empty();
	$("#map_canvas").empty();
	$("#chart_canvas").empty();
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
        	drawCalendar(URL, id_user, result['calendar']);
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
	
    plot = $.plot($("#chart_canvas"), [
        {data: altitude_chart, color: '#67BCFA'}, 
        {data: speed_chart, color: '#045590', lines: {fill: false}},
        {data: lat_chart, lines: {show: false}}, 
        {data: lan_chart, lines: {show: false}}], 
        {
	    	lines: { show: true, fill: true },
	        crosshair: { mode: "x", color: '#045590', width: 3 },
	        grid: { hoverable: true, autoHighlight: false }
        }
    );
}

function drawCalendar(URL, id_user, date) {

	draw_header = true;
	
	calendar = $("#calendar");
	calendar.empty();
	
	for(i = 0; i < date.length; i++) {
		for(j = 0; j < 7; j++) {
			if(date[i][j].value == 0) {
				date[i][j].value = '';
			}
			if(date[i][j].training === undefined) {
				date[i][j].training = '';
			}
		}
		if(draw_header) {
			calendar.append(
					"<tr>" +
						"<td>Sunday</td>" +
						"<td>Monday</td>" +
						"<td>Tuesday</td>" +
						"<td>Wednesday</td>" +
						"<td>Thursday</td>" +
						"<td>Friday</td>" +
						"<td>Saturday</td>" +
					"<tr>" +
						"<td id=" + String(i) + "0" + ">" + date[i][0].value + "</td>" +
						"<td id=" + String(i) + "1" + ">" + date[i][1].value + "</td>" +
						"<td id=" + String(i) + "2" + ">" + date[i][2].value + "</td>" +
						"<td id=" + String(i) + "3" + ">" + date[i][3].value + "</td>" +
						"<td id=" + String(i) + "4" + ">" + date[i][4].value + "</td>" +
						"<td id=" + String(i) + "5" + ">" + date[i][5].value + "</td>" +
						"<td id=" + String(i) + "6" + ">" + date[i][6].value + "</td>" +
					"</tr>"
			);
			draw_header = false;
		}
		else {
			calendar.append(
					"<tr>" +
						"<td id=" + String(i) + "0" + ">" + date[i][0].value + "</td>" +
						"<td id=" + String(i) + "1" + ">" + date[i][1].value + "</td>" +
						"<td id=" + String(i) + "2" + ">" + date[i][2].value + "</td>" +
						"<td id=" + String(i) + "3" + ">" + date[i][3].value + "</td>" +
						"<td id=" + String(i) + "4" + ">" + date[i][4].value + "</td>" +
						"<td id=" + String(i) + "5" + ">" + date[i][5].value + "</td>" +
						"<td id=" + String(i) + "6" + ">" + date[i][6].value + "</td>" +
					"</tr>"
			);
		}
		
	}
	for(i = 0; i < date.length; i++) {
		for(j = 0; j < 7; j++) {
			if(date[i][j].training !== '') {
				a = date[i][j].training;
				$("#" + String(i) + String(j)).append(
					"<div><a href=\""+ URL +"workout/"+ id_user +"/"+ a +"\"><img id=\"tr" + String(i) + String(j) + "\" src=\"" + URL + "img/workout/icon_bike.png\"></a></div>"
				);
				/*$("#tr" + String(i) + String(j)).click(function () {
					showMap(URL, id_user, a);
				});*/
			}
		}
	}
}

function updateCalendar(URL, id_user, month, year) {
	
	if(month === undefined || year === undefined) {
		return;
	}
	
	$.post(
		URL + 'workout/update_calendar',
		{
			id_user: id_user,
			year: year,
			month: month
		},
		function(result) {
			drawCalendar(URL, id_user, result);
		},
		'json'
	);
	
	
}