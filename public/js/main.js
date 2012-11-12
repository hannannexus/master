function changeLanguage() {
	form = document.getElementById('language_form');
	form.submit();
}

function showMap(URL, id_user, workout_number) {
	$.post(
        URL,
        {
        	id_user : id_user,
        	workout_number : workout_number
        },
        function(result) {
        	var wpt = Object();
        	wpt = result;

        	mapOptions = {
        			zoom: 18,
        			center: new google.maps.LatLng(result[0].lat, result[0].lng),
        			mapTypeId: google.maps.MapTypeId.SATELLITE,
        			mapTypeControl: true
        	}

        	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        	
        	var coords = [];
        	
        	for(var i=0; i < 30; i++) {
        		coords.push(new google.maps.LatLng(result[i].lat, result[i].lng));
        	};
        	var Path = new google.maps.Polyline({
        		path: coords,
        		strokeColor: "#339900",
        		strokeOpacity: 1.0,
        		strokeWeight: 2
        	});
        	
        	Path.setMap(map);
  
        	var image = new google.maps.MarkerImage(
        			'img/workout/point.png',
        			new google.maps.Size(20, 20),   // size
        			new google.maps.Point(0,0), // origin
        			new google.maps.Point(0,10)   // anchor
        	);
        
        	var beachMarker = [];                        
        	for(var i=0; i < 30; i++) {
        		beachMarker[i] = new google.maps.Marker({
        			position: coords[i],
        			map: map,
        			icon: image
        		});
        	};
        },
        'json'
	);
}