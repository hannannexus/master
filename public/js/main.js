function changeLanguage() {
	var form = $('#language_form');
	form.submit();
}

function setMarker(position, map, image) {
	var marker = new google.maps.Marker({
		position: position,
		map: map,
		icon: image
	});
	return marker;
}

function floorNumber(x, n)
{
	var mult = Math.pow(10, n);
	return Math.floor(x*mult)/mult;
}

function setMarkerImage(image_address) {
	var image = new google.maps.MarkerImage(
		image_address,
		new google.maps.Size(60, 60),
		new google.maps.Point(0,0), 
		new google.maps.Point(16,35) 
	);
	return image;
}

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

function drawMap(URL, result) {
	/* Center of the map indication (not right calculation)*/
	var center_index = Math.round(result.length/2); 
	/* Google map options */
	var mapOptions = {
			zoom: 13,
			center: new google.maps.LatLng(result[center_index].lat, result[center_index].lan),
			mapTypeId: google.maps.MapTypeId.SATELLITE,
			mapTypeControl: true
	}
	/* Creating of Google Map object */
	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	/* Creating a marker image */
	var image = setMarkerImage(URL + 'img/workout/cycling.png');
	var downhill = setMarkerImage(URL + 'img/workout/bike_downhill.png');
	var rising = setMarkerImage(URL + 'img/workout/bike_rising.png');
	var image = setMarkerImage(URL + 'img/workout/cycling.png');
	/* Creating a starting and finish image */
	var start_end = setMarkerImage(URL + 'img/workout/finish.png');
	/* Creating of start marker */
	var start_marker = setMarker(new google.maps.LatLng(result[0].lat, result[0].lan), map, start_end);
	/* Array of coordinates of LatLng */
	var coords = [];
	/* Array of markers */
	var marker = [];
	/* Index of markers */
	var index = 0;
	/* How many points should skip after marker set */
	var iterations = 0;
	/* Pushing every received point into array */
	for(var i=0; i < result.length; i++) {
		coords.push(new google.maps.LatLng(result[i].lat, result[i].lan));
		/* Every third point checked to speed up map drawing */
		if(i%3 == 0){
			/* General distance from start */
			distance = google.maps.geometry.spherical.computeLength(coords);
			/* Meters passed from every kilometer */
			meters = floorNumber(distance%1000, 0);
			/* If we get closer to kilometer limit */
			if(meters > 850 && iterations <= 0) {
				/* We starting to check not every third point but every point at all */
				/* Local coordinates array to make local distance */
				var local_coords = [];
				/* Local distance by default is current distance */
				var local_distance = distance;
				/* variable needed for iterating through local points */
				var j = i;
				/* Indicates that we get the kilometer length */
				var end = false;
				/* Set next iterations to 0 */
				iterations = 0;
				/* While we not get kilometer length */
				while(!end) {
					/* And we not get end of our trace */
					if(j < result.length - 1) {
						/* Push local coordinates */
						local_coords.push(new google.maps.LatLng(result[j].lat, result[j].lan));
						/* Calculating local distance */
						local_distance += google.maps.geometry.spherical.computeLength(local_coords);
						/* If we are more than 1 kilometer */
						if(local_distance%1000 < 200) {
							/* We make new marker at previous position */
							/* Check if rising up */
							if(result[j-2].alt - result[j-1].alt < -0.25) {
								c_marker = setMarker(new google.maps.LatLng(result[j-1].lat, result[j-1].lan), map, rising);
							}
							/* Check if downhill */
							if(result[j-2].alt - result[j-1].alt > 0.25) {
								c_marker = setMarker(new google.maps.LatLng(result[j-1].lat, result[j-1].lan), map, downhill);
							}
							/* Check if plain */
							if(result[j-2].alt - result[j-1].alt <= 0.25 && result[j-2].alt - result[j-1].alt >= -0.25) {
								c_marker = setMarker(new google.maps.LatLng(result[j-1].lat, result[j-1].lan), map, image);
							}
							/* Pushing current marker into global markers array */
							marker.push(c_marker);
							/* Creating tooltip for current marker in global array */
							marker[index].tooltip = createTooltip(URL, index, result[j].speed);
							/* Adding mouseover event to show tooltip */
							google.maps.event.addListener(c_marker, 'mouseover', (function(marker, index) {
								return function() {
				                    marker[index].tooltip.open(map, this);
				                }
							})(marker, index));
							/* Adding mouseover event to hide tooltip */
							google.maps.event.addListener(c_marker, 'mouseout', (function(marker, index) {
								return function() {
				                    marker[index].tooltip.close();
				                }
							})(marker, index));
							index++;
							/* This is the end of our local iterations */
							end = true;
						}
						else {
							/* Else we setting next point index */
							j++;
							/* And setting up iterations*/
							iterations += 3;
						}
					}
				}
				/* Здесь костыль, его поменять*/
				if(result[j].speed < 3) {
					iterations = 60;
				}
			}
		}
		/* Substract iterations */
		iterations--;
	}
	/* Making finish marker */
	end_marker = setMarker(new google.maps.LatLng(result[result.length-1].lat, result[result.length-1].lan), map, start_end); 
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

function showMap(URL, id_user, workout_number) {
	$.post(
		/* Post to workout controller to get array of lat/lng coordinates */
        URL+'workout/get',
        {
        	id_user : id_user,
        	workout_number : workout_number
        },
        function(result) {
        	drawChart(result);
        	drawMap(URL, result);
        },
        'json'
	);
}

function drawChart(result) {
	var altitude_chart = [];
	var speed_chart = [];
	var distance = 0;
	var coords = []; 
	
	for(var i = 0; i < result.length; i++) {
		coords.push(new google.maps.LatLng(result[i].lat, result[i].lan));
		distance = google.maps.geometry.spherical.computeLength(coords);
		altitude_chart.push([distance/1000, result[i].alt]);
		speed_chart.push([distance/1000, result[i].speed*10]);
	}
	
    $.plot($("#chart_canvas"), [{ data: altitude_chart}, {data: speed_chart}], {
    	lines: { show: true, fill: true },
        crosshair: { mode: "x" },
        grid: { hoverable: true, autoHighlight: false },
        tooltip:        true,       //false
        tooltipOpts: {
          content:      "X: %x.2 | Y: %y.2",
          shifts: { 
            x:          10,
            y:          50
          },
          defaultTheme: true
        }
    });
}























