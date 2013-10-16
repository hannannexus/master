<?php

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
		return empty($result) ? false : $result['user_id'];
	}
	
	public function registerUserByExternalId($args) {
		if(empty($args)) return false;
		$stmt = "
			insert into
				`users` (`email`, `login_name`, `pwd`, `registration_confirm`)
			values
				(?, ?, ?, ?)
		";
		DB::query($stmt, array($name, $name, $password, 1));
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
	}
}