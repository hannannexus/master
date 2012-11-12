<?php 

class User_Controller extends Controller 
{
	protected $user;
	
	public function __construct() {
		$this->user = new User();
	}
	
	/**
	 * Changes user language
	 * @return Laravel\Redirect
	 */
	public function action_language() {
		Session::put('language', Input::get('language'));
		Cookie::forever('language', Input::get('language'));
		if(Auth::check()) {
			$this->user->storeUserLanguage(Auth::user()->user_id, Input::get('language'));
		}
		return Redirect::back();
	}
	
	/**
	 * Show user profile
	 * @return Laravel\Response
	 */
	public function action_profile() {
        $user_data = $this->user->getUserData(Auth::user()->user_id);
        foreach($user_data as $key => $data) {
            if(is_null($data) || empty($data)) {
                $user_data[$key] = '-';
            }
        }
        if($user_data['born_date'] != '0000-00-00' && !is_null($user_data['born_date'])) {
        	$born_date = explode('-', $user_data['born_date']);
        	$born_date = mktime(0, 0, 0, $born_date[1], $born_date[2], $born_date[0]);
        	$user_data['age'] = round((time()-$born_date)/31536000, 0);
        }
        else {
        	$user_data['age'] = '-';
        }
		return View::make('profile.index')->with('user_data', $user_data);
	}
	
	/**
	 * Show profile settings
	 * @return Laravel\Response
	 */
	public function action_settings() {
		$user_data = $this->user->getUserData(Auth::user()->user_id);
		return View::make('profile.settings')->with('user_data', $user_data);
	}
	
	/**
	 * Update profile informatio
	 * @return Laravel\Redirect
	 */
	public function action_settings_process() {
		$data = array();
		$data['name'] = Input::get('name');
		$data['midname'] = Input::get('midname');
		$data['surname'] = Input::get('surname');
		$data['gender'] = Input::get('gender');
		$date = Input::get('borndate');
		$date = explode('.', $date);
		$data['borndate'] = $date[0]; 
		$user_id = Auth::user()->user_id;
		$this->user->setUserData($data, $user_id);
		return Redirect::to('profile')->with('saved', 'success');
	}
	
	/**
	 * Show all users
	 * @return Laravel\Response
	 */
	public function action_users() {
		$users = $this->user->getAllUsers();
		$friendlist = $this->user->getUserFriends(Auth::user()->user_id);
		return View::make('users.index')->with('users', $users)->with('friendlist', $friendlist);
	}
	
	/**
	 * Show user's page
	 * @param int $id_user
	 * @return Laravel\Response
	 */
	public function action_user($id_user) {
		$user_data = $this->user->getUserData($id_user);
		foreach($user_data as $key => $data) {
			if(is_null($data) || empty($data)) {
				$user_data[$key] = '-';
			}
		}
		if($user_data['born_date'] != '0000-00-00' && !is_null($user_data['born_date'])) {
			$born_date = explode('-', $user_data['born_date']);
			$born_date = mktime(0, 0, 0, $born_date[1], $born_date[2], $born_date[0]);
			$user_data['age'] = round((time()-$born_date)/31536000, 0);
		}
		else {
			$user_data['age'] = '-';
		}
		return View::make('users.user')->with('user_data', $user_data);
	}
	
	public function action_workouts() {
		$workouts = $this->user->getUserWorkouts(Auth::user()->user_id);
		return View::make('profile.workouts')->with('workouts', $workouts);
	}
	
	public function action_add_friend($id_friend) {
		$this->user->makeFriendRequest(Auth::user()->user_id, $id_friend);
		return Redirect::back();
	}
	
	public function action_accept_friend($id_friend) {
		$this->user->aceeptFriendRequest(Auth::user()->user_id, $id_friend);
		return Redirect::back();
	}
}
?>