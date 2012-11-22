<?php 
class Workout extends Base {
	public static $table = 'users';
	
	public function getRoute($id_user, $workout_number) {
		$stmt = " 
			select 
				`lat`,
				`lan`,
				`alt`,
				`speed`,
				substr(from_unixtime(substr(`time`, 1, 10)), 1, 10) as `date`,
				substr(from_unixtime(substr(`time`, 1, 10)), 12, 10) as `time`
			from
				`tp_" . $id_user . "_gps`
			where
				`workout_number` = ?
			order by
				`tp_" . $id_user . "_gps`.`time`
			asc
		";
		$route = $this->objectToArray(DB::query($stmt, array($workout_number)));
		$result[0] = $route[0];
		$count = round(count($route)/1000, 0);
		for($i=1; $i<count($route); $i++) {
			if(fmod($i, $count) == 0) {
				array_push($result, $route[$i]);
			}
		}
		return $result;
	}
	
	private function distance($lat1, $lng1, $lat2, $lng2, $miles = false) {
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;
		
		$r = 6372.797;
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;
		
		return ($miles ? ($km * 0.621371192) : $km*1000);
	}
	
	public function getMarkers($route) {
		if(empty($route)) {
			return NULL;
		}
		/* Initializing vars */
		$p_previous = $markers = array();
		$distance = $d_previous = 0;
		$lap = 1000;
		$marker_set = FALSE;
		
		$p_previous = $route[0];
		
		array_push($markers, $p_previous);
		
		foreach($route as $key => $point) {
			$distance += $this->distance($point['lat'], $point['lan'], $p_previous['lat'], $p_previous['lan']);
			/* If point between 180m of current km and 900m of previous km {like 998 etc.}, 
			 * or gps bugged and current point around 865m from previous {like 2995m - 3800m} */
			 if(((fmod($distance, $lap) < 180 && $d_previous > 900) || ($distance - $d_previous > 865)) && !$marker_set) {
				$route[$key-1]['distance'] = $d_previous;
				if($point['alt'] - $route[$key-1]['alt'] < -0.25) {
					$route[$key-1]['slope'] = 'down';
				}
				elseif ($point['alt'] - $route[$key-1]['alt'] > 0.25) {
					$route[$key-1]['slope'] = 'up';
				}
				else {
					$route[$key-1]['slope'] = 'flat';
				}
				array_push($markers, $route[$key-1]);
				$marker_set = TRUE;
			}
			else {
				if((fmod($distance, $lap) < 180 && $d_previous > 900) || ($distance - $d_previous > 865)) {
					$marker_set = TRUE;
				}
				else {
					$marker_set = FALSE;
				}
			}
			$d_previous = $distance; 
			$p_previous = $point;
		}
		
		$route[count($route)-1]['distance'] = $distance;
		array_push($markers, $route[count($route)-1]);
		
		return $markers;
	}
}

















