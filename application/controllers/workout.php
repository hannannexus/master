<?php 

class Workout_Controller extends Controller {
	protected $workout;
	protected $user;
	
	public function __construct() {
		$this->workout = new Workout();
		$this->user = new User();
	}
	
	public function action_index($id_user, $workout_number) {
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		$stats = $this->workout->getTotalInfo($id_user, $workout_number);
		return View::make('workout.index')->with('id_user', $id_user)
										  ->with('workout_number', $workout_number)
										  ->with('messages_count', $messages_count)
										  ->with('stats', $stats);
	}
	
	public function action_get_route() {
		
		$route = array();
		
		$id_user = Input::get('id_user');
		$workout_number = Input::get('workout_number');
		$route['points'] = $this->workout->getRoute($id_user, $workout_number);
		$route['pulse'] = $this->workout->getPulse($id_user, $workout_number);
		$route['arythmy'] = $this->workout->getArythmy($route['pulse'], $id_user);
		$route['markers'] = $this->workout->getMarkers($route['points']);
		$date = $this->workout->getLastWorkout($id_user);
		$route['calendar'] = $this->workout->getCalendarByDate(Input::get('id_user'), $date['date'][1], $date['date'][0]);
		$route['stats'] = $this->workout->getTotalInfo($id_user, $workout_number);
		echo json_encode($route);
		return; 
	}
	
	public function action_update_calendar() {
		
		$month = Input::get('month');
		$year = Input::get('year');
		
		echo json_encode($this->workout->getCalendarByDate(Input::get('id_user'), $month, $year));
	}
	
	public function action_delete_workout($workout_number) {
		$this->workout->deleteWorkout($workout_number);
		return Redirect::to('profile');
	}
	
	public function action_visible($workout_number) {
		$this->workout->visible($workout_number);
		return Redirect::to('profile');
	}
	
	public function action_invisible($workout_number) {
		$this->workout->invisible($workout_number);
		return Redirect::to('profile');
	}
}
