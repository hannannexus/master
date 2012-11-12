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
        	var wpt = Object();
        	wpt = result;
        	center_index = Math.round(result.length/2); 
        	
        	mapOptions = {
        			zoom: 15,
        			center: new google.maps.LatLng(result[center_index].lat, result[center_index].lan),
        			mapTypeId: google.maps.MapTypeId.SATELLITE,
        			mapTypeControl: true
        	}

        	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        	
        	var coords = [];
        	
        	
        	var Path = new google.maps.Polyline({
        		map: map,
        		strokeColor: "#339900",
        		strokeOpacity: 1.0,
        		strokeWeight: 5,
        		zIndex: 0
        	});
        	
        	//Path.setMap(map);
        	
        	for(var i=0; i < result.length; i++) {
        		path = Path.getPath();
        		path.push(new google.maps.LatLng(result[i].lat, result[i].lan));
        	};
        	
        	var image = new google.maps.MarkerImage(
        			URL+'img/workout/point.png',
        			new google.maps.Size(20, 20),   // size
        			new google.maps.Point(0,0), // origin
        			new google.maps.Point(0,10)   // anchor
        	);
        	
        	count = Math.round(result.length/30);
        	var beachMarker = [];                        
        	for(var i=0; i < result.length; i++) {
        		if(i%count==0) {
        			coords = new google.maps.LatLng(result[i].lat, result[i].lan)
        			beachMarker[i] = new google.maps.Marker({
            			position: coords,
            			map: map,
            			icon: image
            		});
        		}
        	};
        },
        'json'
	);
}