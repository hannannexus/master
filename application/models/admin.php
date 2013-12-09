<?php
class Admin extends Base {
	
	public function getParams() {
		$stmt = "
			select
				*
			from
				`advertisement`
		";
		
		$result = $this->objectToSingle(DB::query($stmt));
		return $result;
	}
	
	public function setParams($params, $side) {
		if($side == 'left') {
			$stmt = "
			update
				`advertisement`
			set
				`left_panel_show` = ? ,
				`left_panel_text` = ?
			where
				`id` = 1
			";
			DB::query($stmt, array($params['left_panel_show'], $params['left_panel_text']));
		}
		else {
			$stmt = "
			update
				`advertisement`
			set
				`right_panel_show` = ? ,
				`right_panel_text` = ?
			where
				`id` = 1
			";
			DB::query($stmt, array($params['right_panel_show'], $params['right_panel_text']));
		}
	}
	
	public function isAdmin($user_id) {
		$stmt = "
			select
				`role`
			from
				`users`
			where
				`user_id` = ?
			limit 1
		";
		
		$role = $this->objectToSingle(DB::query($stmt, array($user_id)));
		
		return ($role['role'] == 'ADM') ? TRUE : FALSE;
	}
	
	public function setManual($text) {
		$stmt = "
			update
				`advertisement`
			set
				`manual` = ?
			where
				`id` = 1
		";
		
		DB::query($stmt, array($text));
		return;
	}
}