function changeLanguage() {
	form = document.getElementById('language_form');
	form.submit();
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
        	/* Center of the map indication (not right calculation)*/
        	center_index = Math.round(result.length/2); 
        	/* Google map options */
        	mapOptions = {
        			zoom: 13,
        			center: new google.maps.LatLng(result[center_index].lat, result[center_index].lan),
        			mapTypeId: google.maps.MapTypeId.SATELLITE,
        			mapTypeControl: true
        	}
        	/* Creating of Google Map object */
        	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        	/* Array of coordinates of LatLng */
        	var coords = [];
        	/* Creating a marker image */
        	var image = new google.maps.MarkerImage(
        			URL+'img/workout/cycling.png',
        			new google.maps.Size(60, 60),   // size
        			new google.maps.Point(0,0), // origin
        			new google.maps.Point(16,35)   // anchor
        	);
        	/* Creating a starting and finish image */
        	var start_end = new google.maps.MarkerImage(
        			URL+'img/workout/finish.png',
        			new google.maps.Size(60, 60),   // size
        			new google.maps.Point(0,0), // origin
        			new google.maps.Point(16,35)   // anchor
        	);
        	/* Creating of start marker */
        	beachMarker = new google.maps.Marker({
    			position: new google.maps.LatLng(result[0].lat, result[0].lan),
    			map: map,
    			icon: start_end
    		});
        	var marker = [];
        	var index = 1;
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
        			meters = distance%1000;
        			/* If we get closer to kilometer limit */
        			if(meters.toFixed() > 850 && iterations <= 0) {
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
        							marker[index] = new google.maps.Marker({
                            			position: new google.maps.LatLng(result[j-1].lat, result[j-1].lan),
                            			map: map,
                            			icon: image,
                            			title: 'distance: ' +local_distance.toString() + ' speed: ' + result[j].speed.toString()
                            		});
        							google.maps.event.addListener(marker[index], 'mouseover', function(event) {
        								/**
        								 * @TODO Code here
        								 */
        							});
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
        	beachMarker = new google.maps.Marker({
    			position: new google.maps.LatLng(result[result.length-1].lat, result[result.length-1].lan),
    			map: map,
    			icon: start_end
    		});
        	
        	var Path = new google.maps.Polyline({
        		path: coords,
        		strokeColor: "#339900",
        		strokeOpacity: 1.0,
        		strokeWeight: 5,
        		zIndex: 0
        	});
        	
        	Path.setMap(map);
        },
        'json'
	);
}

