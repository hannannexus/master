<?php 
use Laravel\Auth;

class Workout extends Base {
	public static $table = 'users';
	
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
	
	private function numWeeks($month, $year) {
		$num_weeks=4;
	
		$first_day = $this->firstDay($month, $year);
	
		if($first_day!=1) $num_weeks++;
	
		$widows=$first_day-1;
		$fw_days=7-$widows;
		if($fw_days==7) $fw_days=0;
	
		$numdays=date("t",mktime(2, 0, 0, (int)$month, 1, $year));
	
		if( ($numdays - $fw_days) > 28 ) $num_weeks++;
		return $num_weeks;
	}
	
	private function firstDay($month, $year) {
		
		$first_day= date("w", mktime(2, 0, 0, (int)$month, 1, $year));
		if($first_day==0) $first_day=7; # convert Sunday
	
		return $first_day;
	}
	
	private function days($month, $year, $week, $num_weeks=0) {
		$days=array();
	
		if($num_weeks==0) $num_weeks=$this->numWeeks($month, $year);
	
		$first_day = $this->firstDay($month, $year);
	
		$widows=$first_day-1;
	
		$fw_days=7-$widows;
	
		if($week==1)
		{
			for($i=0;$i<$widows;$i++) $days[]=0;
			for($i=1;$i<=$fw_days;$i++) $days[]=$i;
			return $days;
		}
	
		if($week!=$num_weeks)
		{
			$first=$fw_days+(($week-2)*7);
			for($i=$first+1;$i<=$first+7;$i++) $days[]=$i;
			return $days;
		}
	
		$numdays=date("t",mktime(2, 0, 0, (int)$month, 1, $year));
	
		$orphans=$numdays-$fw_days-(($num_weeks-2)*7);
		$empty=7-$orphans;
		for($i=($numdays-$orphans)+1;$i<=$numdays;$i++) $days[]=$i;
		for($i=0;$i<$empty;$i++) $days[]=0;
		return $days;
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
		$lap_time = $route[1]['unixtime'];
		$p_previous['lap'] = '0:00:00';
	
		array_push($markers, $p_previous);
	
		foreach($route as $key => $point) {
			$distance += $this->distance($point['lat'], $point['lan'], $p_previous['lat'], $p_previous['lan']);
			/* If point between 180m of current km and 900m of previous km {like 998 etc.},
			 * or gps bugged and current point around 865m from previous {like 2995m - 3800m} */
			if(((fmod($distance, $lap) < 180 && $d_previous > 900) || ($distance - $d_previous > 865)) && !$marker_set) {
			$route[$key-1]['distance'] = $d_previous;
			$route[$key-1]['lap'] = date("G:i:s", ($point['unixtime'] - $lap_time));
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
					$lap_time = $point['unixtime'];
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
		$route[count($route)-1]['lap'] = date("G:i:s", ($route[count($route)-1]['unixtime'] - $lap_time));
		array_push($markers, $route[count($route)-1]);
	
		return $markers;
	}
	
	public function getRoute($id_user, $workout_number) {
		$stmt = " 
			select 
				`lat`,
				`lan`,
				`alt`,
				`speed`,
				substr(from_unixtime(substr(`time`, 1, 10)), 1, 10) as `date`,
				substr(from_unixtime(substr(`time`, 1, 10)), 12, 10) as `time`,
				(substr(`time`, 1, 10)) as `unixtime`
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
		
		if($count == 0) {$count += 1;}
		
		for($i=1; $i<count($route); $i++) {
			if(fmod($i, $count) == 0) {
				array_push($result, $route[$i]);
			}
		}
		$start_time = $result[0]['unixtime'];
		foreach($result as $key => $res) {
			$result[$key]['timediff'] = date("G:i:s", ($res['unixtime'] - $start_time));
		}
		
		return $result;
	}
	
	public function getPulse($id_user, $workout_number) {
		$stmt = "
			select
				min(`time`) as `start`,
				max(`time`) as `finish`
			from
				`tp_" . $id_user . "_gps`
			where
				`workout_number` = ?
		";
		
		$timelap = $this->objectToArray(DB::query($stmt, array($workout_number)));
		
		 if(!isset($timelap[0]['start'])) {
			return NULL;
		} 
		
		$stmt = "
			select
				`time`,
				(substr(`time`, 1, 10)) as `unixtime`
			from
				`tp_" . $id_user . "_pulse`
			where
				`time` between ? and ?
			order by
				`id`
		";
		
		$pulse = $this->objectToArray(DB::query($stmt, array($timelap[0]['start'], $timelap[0]['finish'])));
		
		$graphics = array();
		
		for($i = 1; $i < count($pulse); $i++) {
			$heartrate['pulse'] = 60000/($pulse[$i]['time'] - $pulse[$i-1]['time']);
			$heartrate['time'] = ($pulse[$i]['time']-$pulse[1]['time'])/60000;
			if($heartrate['pulse'] > 30 && $heartrate['pulse'] < 200) {
				array_push($graphics, $heartrate);
			}
		}
		return $graphics;
	}
	
	public function getCalendarByDate($id_user, $month, $year) {
		$days = array();
		
		$weeks = $this->numWeeks($month, $year);
		for($i = 1; $i < $weeks + 1; $i++) {
			array_push($days, $this->days($month, $year, $i));
		}
		
		$stmt = "
			select
				`workout_number`,
				substr(from_unixtime(substr(`time`, 1, 10)), 1, 10) as `date`,
				substr(from_unixtime(substr(`time`, 1, 10)), 12, 10) as `time`,
				max(`time`)
			from
				`tp_" . Auth::user()->user_id . "_gps`
			group by
				`workout_number`
		";
		
		$trainings = array();
		$new_days = array();
		$trainings = $this->objectToArray(DB::query($stmt));
		
		foreach($trainings as $tkey => $training) {
			$trainings[$tkey]['date'] = explode("-", $trainings[$tkey]['date']);
			 foreach($days as $dkey => $day) {
				for( $i = 0; $i < 7; $i++ ) {
					$new_days[$dkey][$i]['value'] = $day[$i];
					if(intval($day[$i]) == intval($trainings[$tkey]['date'][2])  && $trainings[$tkey]['date'][0] == $year && $trainings[$tkey]['date'][1] == $month) {
						$new_days[$dkey][$i]['training'] = intval($trainings[$tkey]['workout_number']);
					} 
				}
			} 
		}
		return $new_days;
	}
	
	public function getLastWorkout() {
		$stmt = "
			select
				`workout_number`,
				min(`date`) as `date`
			from (
					select
						`workout_number`,
						substr(from_unixtime(substr(`time`, 1, 10)), 1, 10) as `date`
					from
						`tp_" . Auth::user()->user_id . "_gps`
					where
						`workout_number` = (select max(`workout_number`) from `tp_" . Auth::user()->user_id . "_gps` limit 1)
				) a
			limit
				1
		";
		$last_workout = $this->objectToSingle(DB::query($stmt));
		$last_workout['date'] = explode("-", $last_workout['date']);
		return $last_workout;
	}
	
	public function getTotalInfo($id_user = NULL, $w_number = NULL) {
		
		$stmt = "
			select
				*
			from
				`tp_" . $id_user . "_gps`
			where
				`workout_number` = ?		
		";
		$workout = $this->objectToArray(DB::query($stmt, array($w_number)));
		
		$stats = array();
		$tmp_distance = 0;
		
		foreach($workout as $key => $current) {
			if(isset($workout[$key+1])) {
				$tmp_distance += $this->distance($workout[$key]['lat'], $workout[$key]['lan'], $workout[$key+1]['lat'], $workout[$key+1]['lan']);
			}
		}
		
		$stats['distance'] = $tmp_distance;
		unset($tmp_distance);
		
		$stmt = "
			select
				`workout_number`,
				avg(`speed`) as `avg_speed`,
				max(`speed`) as `max_speed`,
				max(`alt`) as `max_altitude`,
				min(`alt`) as `min_altitude`,
				timediff(substr(from_unixtime(substr(max(`time`), 1, 10)), 12, 10), substr(from_unixtime(substr(min(`time`), 1, 10)), 12, 10)) as `time`,
				substr(from_unixtime(substr(`time`, 1, 10)), 1, 10) as `date`,
				substr(from_unixtime(substr(`time`, 1, 10)), 12, 10) as `time_start`
			from
				`tp_" . $id_user . "_gps`
			where
				`workout_number` = ?		
		";
		
		$data = $this->objectToSingle(DB::query($stmt, array($w_number)));
		
		$stats['avg_speed'] = $data['avg_speed'];
		$stats['max_speed'] = $data['max_speed'];
		$stats['max_alt'] = $data['max_altitude'];
		$stats['min_alt'] = $data['min_altitude'];
		$stats['time'] = $data['time'];
		$stats['date'] = $data['date'];
		$stats['time_start'] = $data['time_start'];
		$stats['workout_number'] = $data['workout_number'];
		
		unset($data, $stmt);
		
		return $stats;
	}
	
	public function getUserFeed($id_user) {
		$feed = array();
		$stmt = "
    		select
				*
			from
				`tp_" . $id_user . "_gps`
			group by
				`workout_number`
			order by
				`workout_number`
			asc
    	";
		$feed = $this->objectToArray(DB::query($stmt));
		
		$feed_info = array();
		
		if(!empty($feed)) {
			foreach($feed as $key => $cur) {
				array_push($feed_info, $this->getTotalInfo($id_user, $cur['workout_number']));
			}
		}
		else {
			return NULL;
		}
		
		return $feed_info;
	}
}

















