<?php 
class User extends Eloquent {
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
		$user = DB::query($stmt, array($name));
		$link = URL::home() . 'confirm/process/' . $user .'/'. $confirmation;
		mail(
			$name, 
			Lang::line('mail_subject')->get($language), 
			Lang::line('mail_content', array('title' => Lang::line('title')->get($language), 'link' => $link, 'code' => $confirmation ))->get($language)
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
		$user = DB::query($stmt, array($id_user));
		$user = (array)$user[0];
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
		$user = DB::query($stmt, array($id_user));
		$user = (array)$user[0];
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
}

?>