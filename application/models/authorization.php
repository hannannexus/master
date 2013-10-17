<?php

use Laravel\Config;

/**
 * Class for social networks login etc.
 * @author mephis
 *
 */
class Authorization extends Base {
	
	public function checkUserByExternalId($id = null, $social_network) {
		if(is_null($id)) return null;
		switch ($social_network) {
			case 'vk' : { $replacement = array('_SOCIAL_ID_' => 'vk_id'); break; }
			case 'facebook' : { $replacement = array('_SOCIAL_ID_' => 'facebook_id'); break; }
		}
		
		$stmt = '
			select
				*
			from
				`user_config`
			where
				`_SOCIAL_ID_` = ?
			limit 1
		';
		
		$result = $this->objectToSingle(DB::query(strtr($stmt, $replacement), $id));
		return empty($result) ? false : $result['id_user'];
	}
	
	public function registerUserByVk($args) {
		if(empty($args)) return false;
		$args['sex'] == 2 ? $args['sex'] = 'male' : $args['sex'] = 'female';
		$replacements = array(
			'[%USER_ID%]' => $args['uid'],
			'[%MD5%]' => md5($args['uid'])
		);
		$args['login_name'] = $args['email'] = strtr(Config::get('application.vk_default_username'), $replacements);
		$args['pwd'] = Hash::make(md5($args['uid'] . Config::get('application.vk_salt')));
		$birthday = explode('.', $args['bdate']);
		$args['born_date'] = $birthday[2] . '-' . $birthday[1] . '-' . $birthday[0];
		$stmt = "
			insert into
				`users` (
					`name`,
					`surname`,
					`patronymic`,
					`born_date`,
					`sex`,
					`email`, 
					`login_name`, 
					`pwd`, 
					`registration_confirm`
				)
			values
				(?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
		DB::query($stmt, array($args['first_name'], $args['last_name'], $args['nickname'], $args['born_date'], $args['sex'], $args['email'], $args['login_name'], $args['pwd'], 1));
		
		$stmt = "
			insert into
				`user_config` (`id_user`, `photo`, `vk_id`)
			values
				((select `user_id` from `users` WHERE `login_name` = ?), ?, ?)
		";
		DB::query($stmt, array($args['login_name'], $args['photo_200_orig'], $args['uid']));
		
		$stmt = "
			select
				*
			from
				`users`
			where
				`email` = ? 		
		";
		$data = $this->objectToSingle(DB::query($stmt, array($args['email'])));
		
		$stmt = "
			create table if not exists `tp_" . $data['user_id'] . "_gps` (
				`id` int(11) not null auto_increment,
				`lat` double not null,
				`lan` double not null,
				`alt` double not null,
				`speed` float not null,
				`workout_number` bigint(20) unsigned not null,
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
		return true;
	}
}