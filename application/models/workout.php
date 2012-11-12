<?php 
class Workout extends Base {
	public static $table = 'users';
	
	public function getRoute($id_user, $workout_number) {
		$stmt = " 
			select 
				*
			from
				`tp_" . $id_user . "_gps`
			where
				`workout_number` = ?
			order by
				`time`
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
}