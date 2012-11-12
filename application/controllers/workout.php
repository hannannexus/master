<?php 

class Workout_Controller extends Controller {
	
	public function action_index($id_user, $workout_number) {
		return View::make('workout.index')->with('id_user', $id_user)->with('workout_number', $workout_number);
	}
}
