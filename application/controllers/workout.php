<?php 

class Workout_Controller extends Controller {
	protected $workout;
	
	public function __construct() {
		$this->workout = new Workout();
	}
	
	public function action_index($id_user, $workout_number) {
		return View::make('workout.index')->with('id_user', $id_user)->with('workout_number', $workout_number);
	}
	
	public function action_get_route() {
		
		$route = array();
		
		$id_user = Input::get('id_user');
		$workout_number = Input::get('workout_number');
		$route['points'] = $this->workout->getRoute($id_user, $workout_number);
		$route['markers'] = $this->workout->getMarkers($route['points']);
		echo json_encode($route);
	}
}
