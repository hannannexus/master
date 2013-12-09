<?php 

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
		} elseif (count($route) == 2) {
			return NULL;
		}
		/* Initializing vars */
		$p_previous = $markers = array();
		$distance = $d_previous = 0;
		$lap = 1000;
		$marker_set = FALSE;
		
		$p_previous = $route[0];
		if(isset($route[1]['unixtime'])) {
			$lap_time = $route[1]['unixtime'];
		}
		$p_previous['lap'] = '0:00:00';
	
		array_push($markers, $p_previous);
		foreach($route as $key => $point) {
			if($point['lat'] == 0 && $point['lan'] == 0) {
				continue;
			}
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
		if(empty($route) || is_null($route)) {
			return array();
		}
		$result[0] = $route[0];
		$count = round(count($route)/1000, 0);
		
		if($count == 0) {$count++;}
		
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
				distinct `time`,
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
		
		$sm_line = $this->smoothig($graphics);
		
		return $sm_line;
		/* return $graphics; */
	}
	
	private function smoothig($arr) {
		$result = array();
		foreach($arr as $key => $cur) {
			if($key < count($arr)-1) {
				if(abs($arr[$key]['pulse'] - $arr[$key+1]['pulse']) < 30) {
					array_push($result, $arr[$key]);
				}
			}
		}
		return $result;
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
				substr(`date`, 1, 10) as `date`,
				substr(`date`, 12, 10) as `time`
			from
				`user_news`
		    where
		        `user_id` = ?
		";
		
		$trainings = array();
		$new_days = array();
		$trainings = $this->objectToArray(DB::query($stmt, array($id_user)));
		foreach($trainings as $tkey => $training) {
			$trainings[$tkey]['date'] = explode("-", $trainings[$tkey]['date']);
			 foreach($days as $dkey => $day) {
				for( $i = 0; $i < 7; $i++ ) {
					$new_days[$dkey][$i]['value'] = $day[$i];
					if(!isset($new_days[$dkey][$i]['training'])) {
						$new_days[$dkey][$i]['training'] = array();
					}
					if(intval($day[$i]) == intval($trainings[$tkey]['date'][2])  && $trainings[$tkey]['date'][0] == $year && $trainings[$tkey]['date'][1] == $month) {
						array_push($new_days[$dkey][$i]['training'], strval($trainings[$tkey]['workout_number']));
					} 
				}
			} 
		}
		return $new_days;
	}
	
	public function getLastWorkout($id_user) {
		$stmt = "
			select
				*
		    from
		        `user_news`
		    where
		        `user_id` = ?
		    having
		        max(`workout_number`)
		    limit
		        1
		";
		$last_workout = $this->objectToSingle(DB::query($stmt, array($id_user)));
		$last_workout['date'] = explode("-", $last_workout['date']);
		return $last_workout;
	}
	
	public function getWorkoutDate($id_user, $workout_number) {
	    $stmt = "
			select
				*
		    from
		        `user_news`
		    where
		        `user_id` = ?
		    and
		        `workout_number` = ?
		    limit
		        1
		";
	    $last_workout = $this->objectToSingle(DB::query($stmt, array($id_user, $workout_number)));
	    $last_workout['date'] = explode("-", $last_workout['date']);
	    return $last_workout;
	}
	
	public function getTotalInfo($id_user = NULL, $w_number = NULL) {
		
		$stmt = "
			select
				*,
				date_format(un.`date`, '%d-%c-%Y') as `formatted_date`,
				sec_to_time(un.`time`/(un.`distance`/1000)) as `time_for_km`
			from
				`user_news` as un
			where
				un.`user_id` = ?
			and
				un.`workout_number` = ?
			order by
				un.`id`
			desc	
		";
		$workout = $this->objectToSingle(DB::query($stmt, array($id_user, $w_number)));
		
		/* $stats = array();
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
		$stats['cdate'] = date("d M Y", mktime(0, 0, 0, substr($stats['date'], 5, 2), substr($stats['date'], 8, 2), substr($stats['date'], 0, 4)));
		$stats['ctime_start'] = substr($stats['time_start'], 0, 5);
		
		unset($data, $stmt); */
		return $workout;
	}
	
	/**
	 * @deprecated
	 * @param unknown_type $id_user
	 * @param unknown_type $pack
	 * @return NULL|multitype:
	 */
	public function getUserFeed_deprecated($id_user, $pack = 0) {
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
			desc
    	";
		$feed = $this->objectToArray(DB::query($stmt));
		
		$feed_info = array();
		
		if(!empty($feed)) {
			for($i = $pack*10; $i < ($pack+1)*10; $i++) {
				if(isset($feed[$i])) {
					array_push($feed_info, $this->getTotalInfo($id_user, $feed[$i]['workout_number']));
				}
				else {
					break;
				}
			}
		}
		else {
			return NULL;
		}
		
		return $feed_info;
	}
	
	public function getUserFeed($id_user, $pack = 0) {
		$feed = array();
		if($id_user == Auth::user()->user_id) {
			$stmt = "
				select
					*,
					date_format(un.`date`, '%d-%c-%Y') as `formatted_date`,
					sec_to_time(un.`time`/(un.`distance`/1000)) as `time_for_km`
				from
					`user_news` as un
				where
					un.`user_id` = ?
				order by
					un.`id`
				desc
			";
		} else {
			$stmt = "
				select
					*,
					date_format(un.`date`, '%d-%c-%Y') as `formatted_date`,
					sec_to_time(un.`time`/(un.`distance`/1000)) as `time_for_km`
				from
					`user_news` as un
				where
					un.`user_id` = ?
				and
					visible = 1
				order by
					un.`id`
				desc
			";
		}
		
		
		$feed = $this->objectToArray(DB::query($stmt, array($id_user)));
		
		$feed_info = array();
		if(!empty($feed)) {
			for($i = $pack*10; $i < ($pack+1)*10; $i++) {
				if(isset($feed[$i])) {
					array_push($feed_info, $feed[$i]);
				} else {
					break;
				}
			}
		} else {
			return NULL;
		}
		
		return $feed_info;
	}
	
	public function getArythmy($pulse, $user_id) {
		$arythmy = array();
		
		$stmt = "
			select
				`arythmy_step`
			from
				`user_config`
			where
				`id_user` = ?
		";
		
		$result = $this->objectToSingle(DB::query($stmt, array($user_id)));
		
		for($i = $result['arythmy_step']-1; $i < count($pulse); $i++) {
			$sum = 0;
			for($j = $i; $j >= $i-$result['arythmy_step']+1; $j--) {
				$sum += $pulse[$j]['pulse'];
			}
			$avg = $sum/$result['arythmy_step'];
			if(($pulse[$i]['pulse'] < ($avg-10)) || ($pulse[$i]['pulse'] > ($avg+10))) {
				array_push($arythmy, $pulse[$i]);
			}
		}
		return $arythmy;
	}
	
	public function getMainFeed($refresh = false) {
		$feed = array();
		$stmt = "
			SELECT
				u.`name`,
				u.`surname`,
				c.`photo`,
				n.`user_id`,
				n.`sport_type`,
				n.`workout_number`,
				n.`time`,
				n.`distance`,
				date_format(n.`date`, '%d-%c-%Y') as `date`
			FROM
				`user_news` as n
			JOIN
				`users` as u
			ON
				n.`user_id` = u.`user_id`
			JOIN
				`user_config` as c
			ON
				n.`user_id` = c.`id_user`
			WHERE
				n.`visible` = 1
			ORDER BY
				n.`id`
			DESC
			LIMIT 15	
		";
		
		$feed = $this->objectToArray(DB::query($stmt));
		$home = URL::home();
		if (!empty($feed)) {
		    foreach ($feed as $key => $val) {
		        if (empty($val['photo'])) {
		            $feed[$key]['photo'] = $home . 'img/system/no_image.jpg';
		        } elseif (!preg_match('/^http/', $val['photo'])) {
		            $feed[$key]['photo'] = $home . 'img/photos/' . $val['user_id'] . '/320/' . $val['photo'];
		        }
		        $feed[$key]['link'] = $home . 'workout' . SLASH . $val['user_id'] . SLASH . $val['workout_number'] . SLASH;
		        $feed[$key]['sport'] = $home . 'img/workout/sports/' . $val['sport_type'] . '.png';
		        $feed[$key]['distance'] = round(floatval($val['distance']), 2);
		        $feed[$key]['user'] = $home . 'user' . SLASH . $val['user_id'] . SLASH;
		    }
		    if ($refresh) {
		        return View::make('home.feed')->with('feed', $feed)->render();
		    }
			return $feed;
		} else {
			return NULL;
		}
	}
	
	public function deleteWorkout($id_workout) {
		$stmt = "
    		select
    			max(`time`) as `max`, min(`time`) as `min`
    		from
    			`tp_" . Auth::user()->user_id . "_gps`
    		where
    			`workout_number` = ?
    	";
		 
		$brackets = $this->objectToSingle(DB::query($stmt, array($id_workout)));
		 
		$stmt = "
    		delete
    		from
    			`tp_" . Auth::user()->user_id . "_gps`
    		where
    			`workout_number` = ?
    	";
		DB::query($stmt, array($id_workout));
		
		$stmt = "
    		delete
    		from
    			`user_news`
    		where
				`user_id` = ?
			and
    			`workout_number` = ?
    	";
		DB::query($stmt, array(Auth::user()->user_id, $id_workout));
		 
		$stmt = "
    		delete
    		from
    			`tp_" . Auth::user()->user_id . "_pulse`
    		where
    			`time` between ? and ?
    	";
		DB::query($stmt, array($brackets['min'], $brackets['max']));
	}
	
	public function visible($workout_number) {
		if(empty($workout_number)) {
			return false;
		}
		$stmt = "
			update 
				`user_news`
			set
				`visible` = 1
			where
				`user_id` = ?
			and
				`workout_number` = ?
			";
		DB::query($stmt, array(Auth::user()->user_id, $workout_number));
	}
	
	public function invisible($workout_number) {
		if(empty($workout_number)) {
			return false;
		}
		$stmt = "
			update
				`user_news`
			set
				`visible` = 0
			where
				`user_id` = ?
			and
				`workout_number` = ?
			";
		DB::query($stmt, array(Auth::user()->user_id, $workout_number));
	}
	
	public function generateFeedHTML($feed) {
		$new_feed = array();
		foreach($feed as $key => $cur_feed) {
			$new_feed .= '
				<div class="white-block">
					<a href="' . URL::home() . 'workout/'. $cur_feed['user_id'] .'/'. $cur_feed['workout_number'] .'" style="decoration: none;">
						<div style="display: inline-block;">
							<div style="margin: 10px; display: inline-block;  width: 110px; height: 110px;">
								<img alt="" src="'. URL::home() .'img/workout/sports/'. $cur_feed['sport_type'] .'.png" width="100%" height="100%">
							</div>
							<div style="margin: 10px; display: inline-block; font-size: 8pt; float: right;">
								<p style="padding: 0;">'. Lang::line('locale.date_doubledot')->get($language) .' <b>'. $cur_feed['formatted_date'] .'</b></p>
								<p style="padding: 0;">'. Lang::line('locale.distance_doubledot')->get($language) .' <b>'. round($cur_feed['distance'], 2) .' '. Lang::line('locale.km')->get($language) .'</b></p>
								<p style="padding: 0;">'. Lang::line('locale.duration')->get($language) .' <b>'. $cur_feed['time'] .'</b></p>
								<p style="padding: 0;">'. Lang::line('locale.avg_speed')->get($language) .' <b>'. round($cur_feed['avg_speed'],2) .' '. Lang::line('locale.km_h')->get($language) .'</b></p>
								<p style="padding: 0;">'. Lang::line('locale.time_for_km')->get($language) .' <b>'. $cur_feed['time_for_km'] .'</b></p>
								<p style="padding: 0;">'. Lang::line('locale.avg_pulse')->get($language) .' <b>'. $cur_feed['avg_pulse'] .' '. Lang::line('locale.bps')->get($language) .'</b></p>
								<p style="padding: 0;">'. Lang::line('locale.arrhythmia')->get($language) .' <b>'. $cur_feed['arrhythmia'] .'</b></p>
							</div>
						</div>
					</a>
					<div style="display: block;">
						<a href="#" class="comment" id="workout_'. $cur_feed['workout_number'] .'">'. Lang::line('locale.comment')->get($language) .'</a>
							<form id="form_workout_'. $cur_feed['workout_number'] .'" class="form_workout" action="">
							<div class="" id="div_workout_'. $cur_feed['workout_number'] .'" style="display: none; margin-bottom: 5px;">
								<input type="text" name="workout_'. $cur_feed['workout_number'] .'" style="margin-bottom: 0px; width: 390px;" placeholder="'. Lang::line('locale.your_comment')->get($language) .'">
							</div>
						</form>
						<div id="comment_line_'. $cur_feed['workout_number'] .'"></div>
					</div>
				</div>';
		}
	}
}

















