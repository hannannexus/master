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
		} elseif (!$check_user) {
			$curl->setDefaultsExceptOptions();
			$replacements = array(
				'[%UIDS%]' => $vk_user_id,
				'[%TOKEN%]' => $vk_access_token
			);
			$curl->create(strtr(Config::get('application.vk_get_user_url'), $replacements));
			$result = json_decode($curl->execute());
			$vk_user_data = array_pop($result->response);
			if(!$this->auth->registerUserByVk((array)$vk_user_data)) {
				return Redirect::to('/')->with('login_error', Lang::line('locale.social_login_troubles')->get(Session::get('language')));
			}
		}
		$replacements = array(
			'[%USER_ID%]' => $vk_user_id,
			'[%MD5%]' => md5($vk_user_id)
		);
		if(Auth::attempt(array('username' => strtr(Config::get('application.vk_default_username'), $replacements), 'password' =>md5($vk_user_id . Config::get('application.vk_salt'))))) {
			$language = Cookie::get('language');
			if(!empty($language)) {
				$user = new User();
				$user->storeUserLanguage(Auth::user()->user_id, $language);
			}
			$curl->setDefaultsExceptOptions();
			$replacements = array(
				'[%UIDS%]' => $vk_user_id,
				'[%TOKEN%]' => $vk_access_token
			);
			$curl->create(strtr(Config::get('application.vk_get_user_url'), $replacements));
			$result = json_decode($curl->execute());
			$vk_user_data = array_pop($result->response);
			$this->auth->updateVkInfo((array)$vk_user_data);
			return Redirect::to('profile');
		} else {
			$login_error = Lang::line('locale.login_error')->get(Cookie::get('language'));
			return Redirect::to('login')->with('login_error', $login_error);
		}
	}
	
	public function action_fblogin() {
		$code = Input::get('code');
		$curl = new Curl();
		$curl->ssl(false);
		$replacements = array(
			'[%CLIENT_ID%]' => Config::get('application.facebook_app_id'),
			'[%CLIENT_SECRET%]' => Config::get('application.facebook_secret_key'),
			'[%CODE%]' => $code
		);
		$curl->create(strtr(Config::get('application.facebook_auth_url'), $replacements));
		$result = $curl->execute();
		$pretoken = explode('&', $result);
		$token = explode('=', $pretoken[0]);
		$fb_access_token = $token[1];
		$curl->setDefaultsExceptOptions();
		$replacements = array(
			'[%TOKEN%]' => $fb_access_token
		);
		$curl->create(strtr(Config::get('application.facebook_get_user_url'), $replacements));
		$facebook_user_data = json_decode($curl->execute());
		$fb_user_id = $facebook_user_data->id;
		$check_user = $this->auth->checkUserByExternalId($fb_user_id, 'facebook');
		if(is_null($check_user)) {
			return Redirect::to('/')->with('login_error', Lang::line('locale.social_login_troubles')->get(Session::get('language')));
		} elseif (!$check_user) {
			if(!$this->auth->registerUserByFacebook((array)$facebook_user_data)) {
				return Redirect::to('/')->with('login_error', Lang::line('locale.social_login_troubles')->get(Session::get('language')));
			}
		}
		$replacements = array(
				'[%USER_ID%]' => $fb_user_id,
				'[%MD5%]' => md5($fb_user_id)
		);
		if(Auth::attempt(array('username' => strtr(Config::get('application.facebook_default_username'), $replacements), 'password' =>md5($fb_user_id . Config::get('application.facebook_salt'))))) {
			$language = Cookie::get('language');
			if(!empty($language)) {
				$user = new User();
				$user->storeUserLanguage(Auth::user()->user_id, $language);
			}
			$this->auth->updateFacebookInfo((array)$facebook_user_data);
			return Redirect::to('profile');
		} else {
			$login_error = Lang::line('locale.login_error')->get(Cookie::get('language'));
			return Redirect::to('login')->with('login_error', $login_error);
		}
	}
}
?>