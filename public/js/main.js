function changeLanguage() {
	form = document.getElementById('language_form');
	form.submit();
}

function showMap(URL, id_user, workout_number) {
	$.post(
        URL+'workout/get',
        {
        	id_user : id_user,
        	workout_number : workout_number
        },
        function(result) {

        	center_index = Math.round(result.length/2); 
        	
        	mapOptions = {
        			zoom: 13,
        			center: new google.maps.LatLng(result[center_index].lat, result[center_index].lan),
        			mapTypeId: google.maps.MapTypeId.SATELLITE,
        			mapTypeControl: true
        	}

        	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        	
        	var coords = [];
        	
        	var image = new google.maps.MarkerImage(
        			URL+'img/workout/cycling.png',
        			new google.maps.Size(60, 60),   // size
        			new google.maps.Point(0,0), // origin
        			new google.maps.Point(0,25)   // anchor
        	);
        	
        	for(var i=0; i < result.length; i++) {
        		coords.push(new google.maps.LatLng(result[i].lat, result[i].lan));
        		if(i%3 == 0){
        			distance = google.maps.geometry.spherical.computeLength(coords);
            		kilometers = distance%1000;
            		if(kilometers.toFixed() < 1050 && kilometers.toFixed() > 950) {
            			beachMarker = new google.maps.Marker({
                			position: new google.maps.LatLng(result[i].lat, result[i].lan),
                			map: map,
                			icon: image,
                			title: distance.toString()
                		});
            		}
        		}
        	};
        	
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