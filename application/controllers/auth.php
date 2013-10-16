<?php 

use Laravel\Redirect;

use Laravel\Config;

class Auth_Controller extends Controller {
	private $auth;
	
	public function __construct() {
		$this->auth = new Authorization();
	}
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
	
	public function action_vklogin() {
		$code = Input::get('code');
		$curl = new Curl();
		$curl->ssl(false);
		$replacements = array(
			'[%CLIENT_ID%]' => Config::get('application.vk_app_id'),
			'[%CLIENT_SECRET%]' => Config::get('application.vk_secret_key'),
			'[%CODE%]' => $code 
		);
		$curl->create(strtr(Config::get('application.vk_auth_url'), $replacements));
		$result = json_decode($curl->execute());
		$vk_access_token = $result->access_token;
		$vk_user_id = $result->user_id;
		$check_user = $this->auth->checkUserByExternalId($vk_user_id, 'vk');
		if(is_null($check_user)) {
			return Redirect::to('/')->with('login_error', Lang::line('locale.social_login_troubles')->get(Session::get('language')));
		}
		$curl->setDefaultsExceptOptions();
		$replacements = array(
			'[%UIDS%]' => $vk_user_id,
			'[%TOKEN%]' => $vk_access_token
		);
		$curl->create(strtr(Config::get('application.vk_get_user_url'), $replacements));
		$result = json_decode($curl->execute());
		$vk_user_data = array_pop($result->response);
		return Redirect::to('/');
	}
	
	public function action_fblogin() {
		$code = Input::get('code');
		$curl = new Curl();
		$curl->ssl(false);
		$curl->create('https://graph.facebook.com/oauth/access_token?client_id=565943740127810&redirect_uri=http://www.localsport.com/fblogin&client_secret=1d4c265955804eea42203cdbd12bb0fb&code=' . $code);
		$result = $curl->execute();
		$pretoken = explode('&', $result);
		$token = explode('=', $pretoken[0]);
		$token = $token[1];
		unset($curl);
		$curl = new Curl();
		$curl->ssl(false);
		return Redirect::to('/');
		print_r($token);
		$curl->create('https://graph.facebook.com/me?access_token=' . $token);
		$res = $curl->execute();
		print_r(json_decode($res));
	}
}
?>