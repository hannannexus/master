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
}