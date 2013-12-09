<?php

class Admin_controller extends Controller 
{
	private $admin;
	
	public function __construct() {
		$this->admin = new Admin();
	}
	
	/**
	 * @author <mephis>
	 * Administration page:
	 * - Set advertisement
	 */
	public function action_index() {
		if(Auth::check()) {
			if($this->admin->isAdmin(Auth::user()->user_id)) {
				$settings = $this->admin->getParams();
				return View::make('admin.index')->with('settings', $settings);
			}
		}
		return Redirect::to('/');
	}
	
	public function action_manual() {
		$settings = $this->admin->getParams();
		if(Auth::check()) {
			if($this->admin->isAdmin(Auth::user()->user_id)) {
				return View::make('manual.admin')->with('settings', $settings);
			}
		}
		return View::make('manual.index')->with('settings', $settings);
	}
	
	public function action_set_left() {
		$settings = Input::all();
		if(isset($settings['left_panel_show'])) {
			$settings['left_panel_show'] = 1;
		}
		else {
			$settings['left_panel_show'] = 0;
		}
		$this->admin->setParams($settings, 'left');
		$settings = $this->admin->getParams();
		return Redirect::to('admin')->with('settings', $settings);
	}
	
	public function action_set_right() {
		$settings = Input::all();
		if(isset($settings['right_panel_show'])) {
			$settings['right_panel_show'] = 1;
		}
		else {
			$settings['right_panel_show'] = 0;
		}
		$this->admin->setParams($settings, 'right');
		$settings = $this->admin->getParams();
		return Redirect::to('admin')->with('settings', $settings);
	}
	
	public function action_set_manual() {
		$text = Input::get('manual_text');
		$this->admin->setManual($text);
		$settings = $this->admin->getParams();
		return Redirect::to('manual')->with('settings', $settings);
	}
}