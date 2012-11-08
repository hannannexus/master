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
	    $user = new User();
        $user_data = $user->getUserData(Auth::user()->user_id);
        foreach($user_data as $key => $data) {
            if(is_null($data) || empty($data)) {
                $user_data[$key] = '-';
            }
        }
		return View::make('profile.index')->with('user_data', $user_data);
	}
}
?>