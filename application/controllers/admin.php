<?php
class Admin_controller extends Controller 
{
	public function action_index() {
		$settings['left_block_show'] = TRUE;
		return View::make('admin.index')->with('settings', $settings);
	} 
	
}