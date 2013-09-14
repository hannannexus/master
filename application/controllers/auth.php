<?php 

class Auth_Controller extends Controller {
	
	/**
	 * Show index page
	 * @return Laravel\Response
	 */
	public function action_index() {
		if(Auth::check()) {
			return Redirect::to('/');
		}
		return View::make('login.index');
	}
    
	/**
	 * User logout
	 * @return Laravel\Redirect
	 */
	public function action_logout() {
		Auth::logout();
		return Redirect::to('/');
	}
	
	/**
	 * User login to system
	 * @return Laravel\Redirect
	 */
	public function action_login_process() {
		$rules = array(
			'email' => 'required|max:30|min:6|email', 
			'password' => 'required|min:3'
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()) {
			return Redirect::to('login')->with_errors($validation);
            
		}
		else {
			if(Auth::attempt(array('username' => Input::get('email'), 'password' =>Input::get('password')))) {
			    $language = Cookie::get('language');
                if(!empty($language)) {
                    $user = new User();
                    $user->storeUserLanguage(Auth::user()->user_id, $language);
                }
				return Redirect::to('profile');
			}
			else {
				$login_error = Lang::line('locale.login_error')->get(Cookie::get('language'));
				return Redirect::to('login')->with('login_error', $login_error);
			}
		}
	}
	
	/**
	 * Show signup page
	 * @return Laravel\Response
	 */
	public function action_signup() {
		return View::make('signup.index');
	}
	
	/**
	 * User signup
	 * @return Laravel\Redirect
	 */
	public function action_signup_process() {
		$user = new User();
		$rules = array(
			'email' => 'required|max:30|min:6|email', 
			'password' => 'required|min:3', 
			'password_confirm' => 'required|min:3|same:password'
		);
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()) {
			return Redirect::to('signup')->with_errors($validation);
		}
		else {
			if($user->checkUserEmail(Input::get('email'))) {
				return Redirect::to('signup')->with('email_error', 'true');
			}
			else {
				$user->registerUser(Input::get('email'), Hash::make(Input::get('password')), Cookie::get('language'), rand(100000, 999999));
				if(!Auth::attempt(array('username' => Input::get('email'), 'password' =>Input::get('password')))){
					return Redirect::to('/');
				}
				return Redirect::to('confirm');
			}
		}
	}
	
	/**
	 * Login confirmation
	 * @return Laravel\Response
	 */
	public function action_confirm() {
		$user = new User();
		$confirm = $user->getUserConfirmStatus(Auth::user()->user_id);
		if(!$confirm) {
			return View::make('signup.confirm');
		}
		else {
			return Redirect::back();
		}
	}
	
	/**
	 * Login confirmation from number
	 * Return Laravel\Response;
	 */
	
	public function action_number_confirm() {
		$rules = array('number' => 'required|numeric');
		$validation = Validator::make(Input::all(), $rules);
		if($validation->fails()) {
			return Redirect::to('confirm')->with_errors($validation);
		}
		$user = new User();
		$conf_number = $user->getUserConfirmNumber(Auth::user()->user_id);
		if(Input::get('number') == $conf_number) {
			$user->confirmUser(Auth::user()->user_id);
			return Redirect::to('profile');
		}
		else {
			return Redirect::to('confirm')->with('confirm_error', Lang::line('locale.confirm_error')->get(Cookie::get('language')));
		}
	}
	
	/** 
	 * Login confirmation from link
	 * @return Laravel\Response
	 */
	
	public function action_link_confirm($id_user, $number) {
		$user = new User();
		$status = $user->getUserConfirmStatus($id_user);
		if($status) {
			return Redirect::to('/');
		}
		else {
			$conf_number = $user->getUserConfirmNumber($id_user);
			if($number == $conf_number) {
				$user->confirmUser($id_user);
				return Redirect::to('profile');
			}
			else {
				return Redirect::to('confirm');
			}
		}
	}
	
	public function action_restore() {
		return View::make('restore.index');
	}
	
	public function action_restore_send() {
		$user = new User();
		$user->sendNewPassword(Input::get('email'), Cookie::get('language'));
		return Redirect::to('login')->with('restore', Lang::line('locale.password_sent')->get(Cookie::get('language')));
	}
}
?>