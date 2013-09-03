<?php 
class User extends Base {
	public static $table = 'users'; 
	
	public function storeUserLanguage($id_user, $language) {
		$stmt = "
			update 
				`user_config`
			set
				`language` = ?
			where
				`id_user` = ?
		";
		return DB::query($stmt, array($language, $id_user));
	}
	
	public function registerUser($name, $password, $language, $confirmation) {
		$stmt = "
			insert into
				`users` (`email`, `login_name`, `pwd`, `registration_confirm`)
			values
				(?, ?, ?, ?)
		";
		DB::query($stmt, array($name, $name, $password, 0));
		$stmt = "
			insert into
				`user_config` (`id_user`, `language`, `confirmation_number`)
			values
				((select `user_id` from `users` WHERE `login_name` = ?), ?, ?)
		";
		DB::query($stmt, array($name, $language, $confirmation));
		$stmt = "
			select
				*
			from
				`users`
			where
				`email` = ? 		
		";
		$data = $this->objectToSingle(DB::query($stmt, array($name)));
		
		$stmt = "
			create table if not exists `tp_" . $data['user_id'] . "_gps` (
				`id` int(11) not null auto_increment,
				`lat` double not null,
				`lan` double not null,
				`alt` double not null,
				`speed` float not null,
				`workout_number` int(10) unsigned not null,
				`time` bigint(20) not null,
				primary key (`id`)
			) engine=MyISAM  default charset=utf8 collate=utf8_general_ci auto_increment=1
		";
		
		DB::query($stmt);
		
		$stmt = "
			create table if not exists `tp_" . $data['user_id'] . "_pulse` (
				`id` int(11) not null auto_increment,
				`pulse` int(4) not null,
				`time` bigint(20) not null,
				primary key (`id`)
			) engine=MyISAM default charset=utf8 collate=utf8_unicode_ci auto_increment=1 ;	
		";
		
		DB::query($stmt);
		
		$this->sendMail($name, $language, $confirmation);
	}
	
	protected function sendMail($name, $language, $confirmation) {
		$stmt = "
			select
				`user_id`
			from
				`users`
			where
				`login_name` = ?
		";
		$user = $this->objectToSingle(DB::query($stmt, array($name)));
		$link = URL::home() . 'confirm/' . $user['user_id'] .'/'. $confirmation;
		mail(
			$name, 
			Lang::line('locale.mail_subject')->get($language), 
			Lang::line('locale.mail_content', array('title' => Lang::line('locale.title')->get($language), 'link' => $link, 'code' => $confirmation ))->get($language)
			);
	}
	
	/**
	 * Check, if user confirmed his registration
	 */
	public function getUserConfirmStatus($id_user) {
		$stmt = "
			select
				`registration_confirm`
			from
				`users`
			where
				`user_id` = ?
		";
		$user = $this->objectToSingle(DB::query($stmt, array($id_user)));
		return $user['registration_confirm'];
	}
	
	public function getUserConfirmNumber($id_user) {
		$stmt = "
			select
				`confirmation_number`
			from
				`user_config`
			where
				`id_user` = ?
		";
		$user = $this->objectToSingle(DB::query($stmt, array($id_user)));
		return $user['confirmation_number'];
	}
	
	public function confirmUser($id_user) {
		$stmt = "
			update
				`users`
			set
				`registration_confirm` = 1
			where
				`user_id` = ?
		";
		DB::query($stmt, array($id_user));
	}
    
    public function getUserData($id_user) {
        $stmt = "
            select
                *
            from
                `users` as users
        	join
        		`user_config` as config
        	on
        		users.`user_id` = config.`id_user`
            where
                `user_id` = ?
        ";
        $user = $this->objectToSingle(DB::query($stmt, array($id_user)));
        return $user;
    } 
    
    public function setUserData($data, $user_id) {
    	$stmt = "
    		update
    			`users`
    		set
    			`name` = ? ,
    			`surname` = ? ,
    			`patronymic` = ? ,
    			`born_date` = ? ,
    			`sex` = ?
    		where
    			`user_id` = ?
    	";
    	DB::query($stmt, array($data['name'], $data['surname'], $data['midname'], $data['borndate'], $data['gender'], $user_id));
    	
    	$stmt = "
    		update
    			`user_config`
    		set
    			`weight` = ?,
    			`arythmy_step` = ?
    		where
    			`id_user` = ?
    		";
    	DB::query($stmt, array($data['weight'], $data['arythmy_step'], $user_id));
    	
    	if(!empty($data['photo']['name'])) {
    		$stmt = "
    		update
    			`user_config`
    		set
    			`photo` = ?
    		where
    			`id_user` = ?
    		";
    		DB::query($stmt, array(md5(Auth::user()->email . $data['photo']['name']) . '.' . File::extension($data['photo']['name']), $user_id));
    	}
    }
    
    public function getAllUsers($pack = 0) {
    	$stmt = "
    		select
    			users.`user_id`,
    			users.`name`,
    			users.`surname`,
    			users.`patronymic`,
    			config.*
    		from
    			`users` as users
    		join
    			`user_config` as config
    		on
    			users.`user_id` = config.`id_user`
    		where
    			users.`user_id` != ?
    		and
    			users.`name` != ''
    		order by
    			users.`name`
    		asc
    	";
    	$result = $this->objectToArray(DB::query($stmt, array(Auth::user()->user_id)));
    	$users = array();
    	
    	for($i = $pack*10; $i < ($pack+1)*10; $i++) {
    		if(isset($result[$i])) {
    			array_push($users, $result[$i]);
    		}
    		else {
    			break;
    		}
    	}
    	return $users;
    }
    
    public function getUserWorkouts($id_user) {
    	$stmt = "
    		select
    			min(from_unixtime(substr(`time`, 1, 10))) as `min`,
    			max(from_unixtime(substr(`time`, 1, 10))) as `max`,
    			`workout_number`
    		from
    			`tp_" .$id_user. "_gps`
    		group by
    			`workout_number`
    		order by
    			`workout_number`
    		asc
    	";
    	$workouts = $this->objectToArray(DB::query($stmt));
    	return $workouts;
    }
    
    public function getUserFriends($id_user, $page = 'users') {
    	$stmt = "
    		select
    			*
    		from
    			`user_relations`
    		where
    			(`id_user` = ?
    		or
    			`id_friend` = ?)
    	";
    	$friendlist = $this->objectToArray(DB::query($stmt, array($id_user, $id_user)));
    	
    	if($page == 'users') return $friendlist;
    	
    	$friends = array();
    	
    	$stmt = "
   			select
   				*
   			from
   				`users`
    		where
    			`user_id` = ?
    	";
    	
    	foreach($friendlist as $key => $friend) {
    		if($friend['id_user'] == $id_user) {
    			$result = $this->objectToSingle(DB::query($stmt, array($friend['id_friend'])));
    			array_push($friends, $result);
    		}
    		else {
    			$result = $this->objectToSingle(DB::query($stmt, array($friend['id_user'])));
    			array_push($friends, $result);
    		}
    		
    	}
    	
    	return $friends;
    }
    
     public function getUserFriendsFull($id_user, $pack = 0) {
     	$friends = array();
     	$stmt = "
     		select
     			u.`user_id`,
     			u.`name`,
     			u.`surname`,
     			u.`patronymic`,
     			r.`relation`
     		from
     			`users` as u
     		join
     			`user_relations` as r
     		on
     			u.`user_id` = r.`id_friend`
     		where
     			r.`id_user` = ?
     		and
     			r.`relation` = 'accepted'
     		order by
     			u.`name`
     	";
     	
     	$result = $this->objectToArray(DB::query($stmt, array($id_user)));
     	if(!empty($result)) {
     		foreach($result as $key => $res) {
     			array_push($friends, $res);
     		}
     	}
     	
     	$stmt = "
     		select
     			u.`user_id`,
     			u.`name`,
     			u.`surname`,
     			u.`patronymic`,
     			r.`relation`
     		from
     			`users` as u
     		join
     			`user_relations` as r
     		on
     			u.`user_id` = r.`id_user`
     		where
     			r.`id_friend` = ?
     		and
     			(r.`relation` = 'accepted'
     				or
     			r.`relation` = 'waiting')
     		order by
     			u.`name`
     	";
     	
     	$result = $this->objectToArray(DB::query($stmt, array($id_user)));
     	if(!empty($result)) {
     		foreach($result as $key => $res) {
     			array_push($friends, $res);
     		}
     	}
     	
     	if(empty($friends)) {
     		return NULL;
     	}
     	else {
     		$users = array();
    	
	    	for($i = $pack*10; $i < ($pack+1)*10; $i++) {
	    		if(isset($friends[$i])) {
	    			array_push($users, $friends[$i]);
	    		}
	    		else {
	    			break;
	    		}
	    	}
	    	return $users;
     	}
     	
     }
    
    public function makeFriendRequest($id_user, $id_friend) {
    	$stmt = "
    		insert into
    			`user_relations`
    			(`id_user`, `id_friend`, `relation`)
    		values
    			(?, ?, 'waiting')
    	";
    	DB::query($stmt, array($id_user, $id_friend));
    }
    
    public function acceptFriendRequest($id_user, $id_friend) {
    	$stmt = "
    		update
    			`user_relations`
    		set
    			`relation` = 'accepted',
    			`stamp` = now()
    		where
    			`id_user` = ?
    		and
    			`id_friend` = ?		
    	";
    	DB::query($stmt, array($id_friend, $id_user));
    }
    
    public function checkInformation($id_user) {
    	$stmt = "
    		select
    			`name`,
    			`surname`,
    			`sex`
    		from
    			`users`
    		where
    			`user_id` = ?		
    	";
    	$information = $this->objectToSingle(DB::query($stmt, array($id_user)));
    	if($information['name'] == '' || $information['surname'] == '' || $information['sex'] == '') {
    		return FALSE;
    	}
    	else {
    		return TRUE;
    	}
    }
    
    public function checkUserEmail($email) {
    	$stmt = "
    		select
    			`login_name`,
    			`email`
    		from
    			`users`
    		where
    			`login_name` = ?
    		or
    			`email` = ?
    	";
    	$email = $this->objectToSingle(DB::query($stmt, array($email, $email)));
    	if(!empty($email['email']) || !empty($email['login_name'])) {
    		return TRUE;
    	}
    	else {
    		return FALSE;
    	}
    }
    
    public function searchUser($words = NULL) {
    	if(is_null($words)) {
    		return FASLE;
    	}
    	else {
    		$search = array();
    		$words = $this->removeWhitespace($words);
    		$words = explode(' ', $words);
    		foreach($words as $key => $word) {
    			$stmt = "
    				select
    					*
    				from
    					`users`
    				where
    					`name` like '%" . $word . "%'
    				or
    					`surname` like '%" . $word . "%'
    				or
    					`patronymic` like '%" . $word . "%'
    				order by
    					`name`
    				asc
    			";
    			
    			$result = $this->objectToArray(DB::query($stmt));
    			if(!empty($result)) {
    				foreach($result as $key => $res) {
    					array_push($search, $res);
    				}
    			}
    		}
    		
    		foreach($search as $key => $src) {
    			foreach($search as $skey => $srch) {
    				if($search[$skey]['user_id'] == $src['user_id'] && $key != $skey) {
    					unset($search[$key]);
    				}
    			}
    		}
    		return $search;
    	}
    }
    
    public function addFeedComment($w_number, $id_workout, $id_user, $text) {
    	$stmt = "
			insert into
				`feed_comments`
					(`workout_number`, `id_workout`, `id_user`, `stamp`, `text`)
			values
				(?, ?, ?, now(), ?)
		";
    
    	$result = DB::query($stmt, array($w_number, $id_workout, $id_user, $text));
    	return $result;
    }
    
    public function getFeedComment($id_user) {
    	$stmt = "
    		select
    			fc.`stamp`,
    			fc.`text`,
    			fc.`workout_number`,
    			u.`name`,
    			u.`surname`,
    			u.`user_id`
    		from
    			`feed_comments` fc
    		join
    			`users` u
    		on
    			fc.`id_user` = u.`user_id`
    		where
    			`id_workout` = ?
    		order by
    			`workout_number`, `stamp`
    		asc
    	";
    	
    	$result = $this->objectToArray(DB::query($stmt, array($id_user)));
    	return $result;
    }
    
    public function checkFriend($id_user) {
    	$stmt = "
    		select
    			count(*) as `count`
    		from
    			`user_relations` as ur
    		where
    			(
	    			ur.`id_user` = ? 
	    		and
	    			ur.`id_friend` = ?
	    		and
	    			ur.`relation` = 'accepted'
    			)
    		or
    			(
	    			ur.`id_user` = ? 
	    		and
	    			ur.`id_friend` = ?
	    		and
	    			ur.`relation` = 'accepted'
    			)
    	";
    	
    	$count = $this->objectToSingle(DB::query($stmt, array($id_user, Auth::user()->user_id, Auth::user()->user_id, $id_user)));
    	if($count['count'] > 0) {
    		return TRUE;
    	}
    	else {
    		return FALSE;
    	}
    }
    
    public function checkFriendStatus($id_user) {
    	$stmt = "
    		select
    			*
    		from
    			`user_relations` as ur
    		where
    			ur.`id_user` = ?
    		and
    			ur.`id_friend` = ?
    	";
    	$result = $this->objectToSingle(DB::query($stmt, array(Auth::user()->user_id, $id_user)));
    	
		if(isset($result['relation'])) {
			switch($result['relation']) {
				case 'waiting' : {
					$status = 'waiting_for_confirm';
					break;
				}
				case 'accepted' : {
					$status = 'accepted';
					break;
				}
				default: $status = 'accepted';
			}
		}
		else {
			$stmt = "
	    		select
	    			*
	    		from
	    			`user_relations` as ur
	    		where
	    			ur.`id_user` = ?
	    		and
	    			ur.`id_friend` = ?
	    	";
			$result = $this->objectToSingle(DB::query($stmt, array($id_user, Auth::user()->user_id)));
			
			if(isset($result['relation'])) {
				switch($result['relation']) {
					case 'waiting' : {
						$status = 'confirm_friend';
						break;
					}
					case 'accepted' : {
						$status = 'accepted';
						break;
					}
					default: $status = 'accepted';
				}
			}
			else {
				$status = NULL;
			}
		} 
    	return $status;
    }
    
    public function getUserWeight($user_id) {
    	$stmt = "
    		select
    			uc.`weight` as `weight`
    		from
    			`user_config` as uc
    		where
    			uc.`id_user` = ?
    		limit 
    			1
    	";
    	$result = $this->objectToSingle(DB::query($stmt, array($user_id)));
    	if($result['weight'] <= 0) {
    		return 0;
    	}
    	else {
    		return $result['weight'];
    	}
    }
    
    /**
     * 
     * @param unknown_type $user_id
     * @param unknown_type $count
     * @return NULL|Ambigous <number, multitype:>|number
     */
    public function getUserMessages($user_id, $count = NULL) {
    	if(!is_numeric($user_id)) {
    		return NULL;
    	}
    	else {
    		$stmt = "
    			select
    				*
    			from
    				`messages`
    			where 
    				`sender` = ?
    		";
    		
    		$result = DB::query($stmt, array($user_id));
    		if(!empty($result)) {
    			$sent_messages = $this->objectToArray($result);
    		}
    		else {
    			$sent_messages = 0;
    		}
    		
    		$stmt = "
    			select
    				*
    			from
    				`messages`
    			where
    				`reciever` = ?		
    		";
    		
    		$result = DB::query($stmt, array($user_id));
    		if(!empty($result)) {
    			$recieved_messages = $this->objectToArray($result);
    		}
    		else {
    			$recieved_messages = 0;
    		}
    		
    		$stmt = "
    				select
    					*
    				from
    					`messages`
    				where
    					`reciever` = ?
    				and
    					`status` = 'unread'
    			";
    		$result = DB::query($stmt, array($user_id));
    		
    		if(!empty($result)) {
    			$unread_messages = $this->objectToArray($result);
    		}
    		else {
    			$unread_messages = 0;
    		}
    		
    		if(is_null($count)) {
    			$messages['sent'] = $sent_messages;
    			$messages['recieved'] = $recieved_messages;
    			$messages['unread'] = $unread_messages;
    			return $messages;
    		}
    		else {
    			$count_messages['sent'] = ($sent_messages == 0) ? 0 : count($sent_messages);
    			$count_messages['recieved'] = ($recieved_messages == 0) ? 0 : count($recieved_messages);
    			$count_messages['unread'] = ($unread_messages == 0) ? 0 : count($unread_messages);
    			return $count_messages;
    		}
    	}
    }
    
    public function sendMessage($data) {
    	$stmt = "
    		insert into
    			`messages`
    			(
    				`sender`,
    				`reciever`,
    				`type`,
    				`text`,
    				`status`,
    				`stamp`
    			)
    		values
    			(
    				?,
    				?,
    				'user',
    				?,
    				'unread',
    				now()
    			)
    	";
    	if(!empty($data['text'])) {
    		DB::query($stmt, array($data['sender'], $data['reciever'], $data['text']));
    		return TRUE;
    	}
    	else {
    		return FALSE;
    	}
    }
    
    public function getInbox($user_id, $pack = 0) {
    	
    	$stmt = "
    		select
    			m.*,
    			u.`name`,
    			u.`surname`,
    			u.`user_id`
    		from
    			`messages` as m
    		join
    			`users` as u
    		on
    			m.`sender` = u.`user_id`
    		where
    			`reciever` = ?
    		order by
    			`stamp` desc,
    			`status` asc
    	";
    	
    	$result = $this->objectToArray(DB::query($stmt, array($user_id)));
    	
    	if(empty($result)) {
    		return FALSE;
    	}
    	else {
    		$messages = array();
    		    		
    		for($i = $pack*30; $i < ($pack+1)*30; $i++) {
    			if(isset($result[$i])) {
    				if(strlen($result[$i]['text']) > 40) {
    					$result[$i]['short'] = mb_substr($result[$i]['text'], 0, 39, 'utf8') . '...';
    				}
    				else {
    					$result[$i]['short'] = $result[$i]['text'];
    				}
    				$result[$i]['time'] = date('d M Y H:i', strtotime($result[$i]['stamp']));
    				array_push($messages, $result[$i]);
    			}
    			else {
    				break;
    			}
    		}
    		return $messages;
    	}
    }
    
    public function getMessage($message_id, $outbox = FALSE) {
    	if($outbox == FALSE) {
    		$stmt = "
	    		update
	    			`messages`
	    		set
	    			`status` = 'read'
	    		where
	    			`id_message` = ?
	    		";
    		 
    		DB::query($stmt, array($message_id));
    		
    		$stmt = "
	    		select
	    			m.*,
	    			u.`name`,
	    			u.`surname`,
	    			u.`user_id`
	    		from
	    			`messages` as m
	    		join
	    			`users` as u
	    		on
	    			m.`sender` = u.`user_id`
	    		where
	    			m.`id_message` = ?
	    	";
    		 
    		$result = $this->objectToSingle(DB::query($stmt, array($message_id)));
    		$result['time'] = date('H:i d M Y', strtotime($result['stamp']));
    		return $result;
    	}
    	
    	else {
    		$stmt = "
	    		select
	    			m.*,
	    			u.`name`,
	    			u.`surname`,
	    			u.`user_id`
	    		from
	    			`messages` as m
	    		join
	    			`users` as u
	    		on
	    			m.`reciever` = u.`user_id`
	    		where
	    			m.`id_message` = ?
	    	";
    		 
    		$result = $this->objectToSingle(DB::query($stmt, array($message_id)));
    		$result['time'] = date('H:i d M Y', strtotime($result['stamp']));
    		return $result;
    	}
    	
    	
    }
    
	public function getOutbox($user_id, $pack = 0) {
    	$stmt = "
    		select
    			m.*,
    			u.`name`,
    			u.`surname`,
    			u.`user_id`
    		from
    			`messages` as m
    		join
    			`users` as u
    		on
    			m.`reciever` = u.`user_id`
    		where
    			`sender` = ?
    		order by
    			`stamp` desc,
    			`status` asc
    	";
    	
    	$result = $this->objectToArray(DB::query($stmt, array($user_id)));
    	
    	if(empty($result)) {
    		return FALSE;
    	}
    	else {
    		$messages = array();
    		    		
    		for($i = $pack*30; $i <= ($pack+1)*30; $i++) {
    			if(isset($result[$i])) {
    				if(strlen($result[$i]['text']) > 40) {
    					$result[$i]['short'] = substr($result[$i]['text'], 0, 39) . '...';
    				}
    				else {
    					$result[$i]['short'] = $result[$i]['text'];
    				}
    				$result[$i]['time'] = date('d M Y H:i', strtotime($result[$i]['stamp']));
    				array_push($messages, $result[$i]);
    			}
    			else {
    				break;
    			}
    			
    		}
    		return $messages;
    	}
    }
    
    public function sendNewPassword($name, $language) {
    	$stmt = "
			select
				`user_id`
			from
				`users`
			where
				`login_name` = ?
		";
    	$user = $this->objectToSingle(DB::query($stmt, array($name)));
    	$password = $this->generatePassword();
    	
    	$stmt = "
    		update 
    			`users`
    		set
    			`pwd` = ?
    		where
    			`login_name` = ?
    	";
    	
    	DB::query($stmt, array(Hash::make($password), $name));
    	
    	mail(
    	$name,
    	Lang::line('locale.mail_new_password')->get($language),
    	Lang::line('locale.mail_new_password')->get($language) . ' ' . $password
    	);
    }
    
    public function generatePassword ($length = 8) {
    	$password = "";
    
    	$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    
    	$maxlength = strlen($possible);
    
    	if ($length > $maxlength) {
    		$length = $maxlength;
    	}
    
    	$i = 0;
    
    	while ($i < $length) {
    
    		$char = substr($possible, mt_rand(0, $maxlength-1), 1);
    
    		if (!strstr($password, $char)) {
    			$password .= $char;
    			$i++;
    		}
    
    	}
    	return $password;
    }
}

?>