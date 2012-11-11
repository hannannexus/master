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
        if($user_data['born_date'] != '0000-00-00') {
        	$born_date = explode('-', $user_data['born_date']);
        	$born_date = mktime(0, 0, 0, $born_date[1], $born_date[2], $born_date[0]);
        	$user_data['age'] = round((time()-$born_date)/31536000, 0);
        }
        else {
        	$user_data['age'] = '-';
        }
		return View::make('profile.index')->with('user_data', $user_data);
	}
	
	public function action_settings() {
		$user_data = $this->user->getUserData(Auth::user()->user_id);
		return View::make('profile.settings')->with('user_data', $user_data);
	}
	
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
	
	public function action_users() {
		$users = $this->user->getAllUsers();
		return View::make('users.index')->with('users', $users);
	}
}
?>