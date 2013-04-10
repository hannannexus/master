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
        $workout = new Workout();
        $pack = Input::get('pack');
        if(empty($pack)) {
        	$feed = $workout->getUserFeed(Auth::user()->user_id);
        }
        else {
        	$feed = $workout->getUserFeed(Auth::user()->user_id, $pack);
        	echo json_encode($feed); return;
        }
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
        $messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		return View::make('profile.index')->with('user_data', $user_data)->with('feed', $feed)->with('messages_count', $messages_count);
	}
	
	/**
	 * Show profile settings
	 * @return Laravel\Response
	 */
	public function action_settings() {
		$user_data = $this->user->getUserData(Auth::user()->user_id);
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		return View::make('profile.settings')->with('user_data', $user_data)->with('messages_count', $messages_count);
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
		
		if(!empty($data['photo']['name'])) {
			
			$input = array(
					'name' => $data['name'],
					'midname' => $data['midname'],
					'surname' => $data['surname'],
					'photo' => Input::file('photo'),
					'weight' => $data['weight']
			);
			$rules = array(
					'name' => 'required|max:40|min:1',
					'midname' => 'max:40|min:1',
					'surname' => 'required|max:40|min:1',
					'photo' => 'mimes:jpeg,jpg,png,gif|max:2048',
					'weight' => 'numeric|between:10,200'
			);
			$validator = Validator::make($input, $rules);
			if(!$validator->fails()) {
				
				$user_id = Auth::user()->user_id;
				$this->user->setUserData($data, $user_id);
				
				Input::upload('photo', 'public/img/photos/' . Auth::user()->user_id . '/original/', md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']));
				File::mkdir('public/img/photos/' . Auth::user()->user_id . '/60' );
				File::mkdir('public/img/photos/' . Auth::user()->user_id . '/100' );
				File::mkdir('public/img/photos/' . Auth::user()->user_id . '/320' );
				
				Resizer::open( 'public/img/photos/' . Auth::user()->user_id . '/original/' . md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']) )
				->resize( 60 , 60 , 'auto' )
				->save( 'public/img/photos/' . Auth::user()->user_id . '/60/' . md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']) , 100 );
				
				Resizer::open( 'public/img/photos/' . Auth::user()->user_id . '/original/' . md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']) )
				->resize( 200 , 200 , 'auto' )
				->save( 'public/img/photos/' . Auth::user()->user_id . '/100/' . md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']) , 100 );
				
				Resizer::open( 'public/img/photos/' . Auth::user()->user_id . '/original/' . md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']) )
				->resize( 320 , 320 , 'auto' )
				->save( 'public/img/photos/' . Auth::user()->user_id . '/320/' . md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']) , 100 );
				
			}
			else {
				return Redirect::to('profile/settings')->with_errors($validator->errors);
			}
		}
		else {
			$input = array(
					'name' => $data['name'],
					'midname' => $data['midname'],
					'surname' => $data['surname'],
					'weight' => $data['weight']
			);
			$rules = array(
					'name' => 'required|max:40|min:1',
					'midname' => 'max:40|min:1',
					'surname' => 'required|max:40|min:1',
					'weight' => 'numeric|between:10,200'
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
		$pack = Input::get('pack');
		if(empty($pack)) {
			$users = $this->user->getAllUsers();
			$friendlist = $this->user->getUserFriends(Auth::user()->user_id);
			$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
			return View::make('users.index')->with('users', $users)->with('friendlist', $friendlist)->with('messages_count', $messages_count);
		}
		else {
			unset($users);
			$users = array();
			$users['users'] = $this->user->getAllUsers($pack);
			$users['friendlist'] = $this->user->getUserFriends(Auth::user()->user_id);
			echo json_encode($users);
			return;
		}
	}
	
	/**
	 * Show user's page
	 * @param int $id_user
	 * @return Laravel\Response
	 */
	public function action_user($id_user) {
		$user_data = $this->user->getUserData($id_user);
		$workout = new Workout();
		$pack = Input::get('pack');
		if(empty($pack)) {
			$feed = $workout->getUserFeed($id_user);
		}
		else {
			$feed = $workout->getUserFeed($id_user, $pack);
			echo json_encode($feed); return;
		}
		
		$friend = $this->user->checkFriend($id_user);
		$status = $this->user->checkFriendStatus($id_user);
		
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
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		return View::make('users.user')->
				with('user_data', $user_data)->
				with('feed', $feed)->
				with('friend', $friend)->
				with('status', $status)->
				with('messages_count', $messages_count);
	}
	
	/**
	 * 
	 * @param unknown_type $id_user
	 * @return Ambigous <\Laravel\Redirect, \Laravel\Response, \Laravel\\Laravel\Response>
	 */
	public function action_workouts($id_user) {
		$workout = new Workout();
		$workouts = $workout->getLastWorkout($id_user);
		if(empty($workouts['workout_number'])) {
			return Redirect::to('profile')->with('workouts', 'no');
		}
		return Redirect::to('workout/' . $id_user . '/' . $workouts['workout_number']);
	}
	
	/**
	 * 
	 * @param unknown_type $id_friend
	 * @return Ambigous <\Laravel\Redirect, \Laravel\Response, \Laravel\\Laravel\Response>
	 */
	public function action_add_friend($id_friend) {
		$this->user->makeFriendRequest(Auth::user()->user_id, $id_friend);
		return Redirect::back();
	}
	
	/**
	 * 
	 * @param unknown_type $id_friend
	 * @return Ambigous <\Laravel\Redirect, \Laravel\Response, \Laravel\\Laravel\Response>
	 */
	public function action_accept_friend($id_friend) {
		$this->user->acceptFriendRequest(Auth::user()->user_id, $id_friend);
		return Redirect::back();
	}
	
	/**
	 * 
	 * @return Ambigous <\Laravel\Redirect, \Laravel\Response, \Laravel\\Laravel\Response>
	 */
	public function action_information() {
		$check = $this->user->checkInformation(Auth::user()->user_id);
		if(!$check) {
			return View::make('signup.information');
		}
		else {
			return Redirect::back();
		}
		
	}
	
	/**
	 * 
	 * @return Ambigous <\Laravel\Redirect, \Laravel\Response, \Laravel\\Laravel\Response>|Ambigous <\Laravel\Redirect, \Laravel\\Laravel\Redirect>
	 */
	public function action_information_process() {
		$data = array();
		$id_user = Auth::user()->user_id;
		$data['name'] = Input::get('name');
		$data['surname'] = Input::get('surname');
		$data['gender'] = Input::get('gender');
		$data['midname'] = $data['patronymic'] = $data['borndate'] = '';
		if(!empty($data['name']) && !empty($data['surname']) && !empty($data['gender'])) {
			$this->user->setUserData($data, $id_user);
			return Redirect::to('profile');
		}
		else {
			return Redirect::to('information')->with('information_error', 'true');
		}
	}
	
	public function action_search() {
		$string = Input::get('search');
		$is_empty = trim($string);
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		if(empty($is_empty)) {
			$users = $this->user->getAllUsers();
			$friendlist = $this->user->getUserFriends(Auth::user()->user_id);
			return View::make('users.index')->with('users', $users)->with('friendlist', $friendlist)->with('messages_count', $messages_count);
		}
		$users = $this->user->searchUser($string);
		if($search = FALSE) {
			return View::make('users.index')->with('messages_count', $messages_count);
		}
		$friendlist = $this->user->getUserFriends(Auth::user()->user_id);
		return View::make('users.search')->with('users', $users)->with('friendlist', $friendlist)->with('messages_count', $messages_count);
	}
	
	public function action_add_comment() {
		$text = Input::get('comment');
		$w_number = Input::get('workout_number');
		$id_workout = Input::get('id_workout');
		$result = $this->user->addFeedComment($w_number, $id_workout, Auth::user()->user_id, $text);
		echo $result;
	}
	
	public function action_get_comment() {
		$id_user = Input::get('id_workout');
		$result = $this->user->getFeedComment($id_user);
		echo json_encode($result);
	}
	
	public function action_messages() {
		$friends = $this->user->getUserFriends(Auth::user()->user_id, 'messages');
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		$pack = Input::get('pack');
		if(empty($pack)) {
			$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
			$messages = $this->user->getInbox(Auth::user()->user_id, 0);
			return View::make('profile.messages')->with('messages_count', $messages_count)->with('messages', $messages)->with('friends', $friends);
		}
		else {
			$messages = $this->user->getInbox(Auth::user()->user_id, $pack);
			echo json_encode($messages);
			return;
		}
	}
	
	public function action_outmessages() {
		$friends = $this->user->getUserFriends(Auth::user()->user_id, 'messages');
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		$pack = Input::get('pack');
		if(empty($pack)) {
			$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
			$messages = $this->user->getOutbox(Auth::user()->user_id, 0);
			return View::make('profile.outmessages')->with('messages_count', $messages_count)->with('messages', $messages)->with('friends', $friends);
		}
		else {
			$messages = $this->user->getOutbox(Auth::user()->user_id, $pack);
			echo json_encode($messages);
			return;
		}
	}
	
	/**
	 * 
	 * @return Ambigous <\Laravel\Redirect, \Laravel\\Laravel\Redirect>
	 */
	public function action_send_message() {
		$data['reciever'] = Input::get('reciever');
		$data['text'] = Input::get('text');
		$data['sender'] = Auth::user()->user_id;
		
		$friends = $this->user->getUserFriends(Auth::user()->user_id, 'messages');
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		
		if(empty($data['text'])) {
			return Redirect::to('profile/messages')->with('friends', $friends)->with('messages_count', $messages_count)->with('success', 'empty_message');
		}
		
		if($this->user->sendMessage($data)) {
			return Redirect::to('profile/messages')->with('friends', $friends)->with('messages_count', $messages_count)->with('success', 'success');
		}
		else {
			return Redirect::to('profile/messages')->with('friends', $friends)->with('messages_count', $messages_count)->with('success', 'error');
		}
	}
	
	public function action_inbox($message_id) {
		$friends = $this->user->getUserFriends(Auth::user()->user_id, 'messages');
		$messages_count = $this->user->getUserMessages(Auth::user()->user_id, TRUE);
		$message = $this->user->getMessage($message_id);
		return View::make('profile.inbox')->with('friends', $friends)->with('messages_count', $messages_count)->with('message', $message); 
	}
}
?>