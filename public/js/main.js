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
        	/* Creating of marker image */
        	var image = new google.maps.MarkerImage(
        			URL+'img/workout/cycling.png',
        			new google.maps.Size(60, 60),   // size
        			new google.maps.Point(0,0), // origin
        			new google.maps.Point(16,35)   // anchor
        	);
        	/* Creating of start marker */
        	beachMarker = new google.maps.Marker({
    			position: new google.maps.LatLng(result[0].lat, result[0].lan),
    			map: map,
    			icon: image
    		});
        	/* How many iterations after marker created we don't check next marker */
        	var iterations = 0;
        	/* Pushing every received point into array */
        	for(var i=0; i < result.length; i++) {
        		coords.push(new google.maps.LatLng(result[i].lat, result[i].lan));
        		if(i%3 == 0){
        			/* Calculating distance between start point and current */
        			distance = google.maps.geometry.spherical.computeLength(coords);
        			/* Marking every kilometer */
            		kilometers = distance%1000;
            		/* +/-50 meters precision */
            		if(kilometers.toFixed() < 1150 && kilometers.toFixed() > 950) {
            			if(iterations <= 0){
            				/* Creating marker on current position */
                			beachMarker = new google.maps.Marker({
                    			position: new google.maps.LatLng(result[i].lat, result[i].lan),
                    			map: map,
                    			icon: image,
                    			title: distance.toString()
                    		});
                			if(result[i].speed < 15){
            					iterations = 20;
            				}
            				else {
            					iterations = 5;
            				}
            			}
        				
            		}
        		}
        		iterations -= 1;
        	};
        	
        	beachMarker = new google.maps.Marker({
    			position: new google.maps.LatLng(result[result.length-1].lat, result[result.length-1].lan),
    			map: map,
    			icon: image
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