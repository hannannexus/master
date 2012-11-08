<?php 
class User_Controller extends Controller {
	
	/**
	 * Changes user language
	 * @return Laravel\Redirect
	 */
	public function action_language() {
		$user = new User();
		Session::put('language', Input::get('language'));
		Cookie::forever('language', Input::get('language'));
		if(Auth::check()) {
			$user->storeUserLanguage(Auth::user()->user_id, Input::get('language'));
		}
		return Redirect::back();
	}
	
	/**
	 * Show user profile
	 * @return Laravel\Response
	 */
	public function action_profile() {
		return View::make('profile.index');
	}
}
?>