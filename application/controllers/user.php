<?php 

use Laravel\Validator;

use Laravel\Redirect;

use Laravel\Input;

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
		
		$data = Input::all();
		
		$date = Input::get('borndate');
		$date = explode('.', $date);
		$data['borndate'] = $date[0];
		 
		if(!empty($data['photo'])) {
			
			$input = array(
					'name' => $data['name'],
					'midname' => $data['midname'],
					'surname' => $data['surname'],
					'photo' => Input::file('photo')
			);
			$rules = array(
					'name' => 'required|max:40|min:1',
					'midname' => 'max:40|min:1',
					'surname' => 'required|max:40|min:1',
					'photo' => 'mimes:jpeg,jpg,png,bmp,tiff|max:2048'
			);
			$validator = Validator::make($input, $rules);
			if(!$validator->fails()) {
				$user_id = Auth::user()->user_id;
				$this->user->setUserData($data, $user_id);
				Input::upload('photo', 'public/img/photos/', md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']));
			}
			else {
				return Redirect::to('profile/settings')->with_errors($validator->errors);
			}
		}
		else {
			$input = array(
					'name' => $data['name'],
					'midname' => $data['midname'],
					'surname' => $data['surname']
			);
			$rules = array(
					'name' => 'required|max:40|min:1',
					'midname' => 'max:40|min:1',
					'surname' => 'required|max:40|min:1'
			);
			$validator = Validator::make($input, $rules);
			if(!$validator->fails()) {
				$user_id = Auth::user()->user_id;
				$this->user->setUserData($data, $user_id);
			}
			else {
				return Redirect::to('profile/settings')->with_errors($validator->errors);
			}
		}
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
	
	public function action_information() {
		$check = $this->user->checkInformation(Auth::user()->user_id);
		if(!$check) {
			return View::make('signup.information');
		}
		else {
			return Redirect::back();
		}
		
	}
	
	public function action_information_process() {
		$data = array();
		$id_user = Auth::user()->user_id;
		$data['name'] = Input::get('name');
		$data['surname'] = Input::get('surname');
		$data['gender'] = Input::get('gender');
		$data['midname'] = $data['patronymic'] = $data['borndate'] = '';
		print_r($data);
		if(!empty($data['name']) && !empty($data['surname']) && !empty($data['gender'])) {
			$this->user->setUserData($data, $id_user);
			return Redirect::to('profile');
		}
		else {
			return Redirect::to('information')->with('information_error', 'true');
		}
	}
}
?>