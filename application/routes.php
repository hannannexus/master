<?php
use Laravel\Redirect;

/**
 * Admin routes
 */

Route::get('admin', 'admin@index');
Route::post('admin/set/left', 'admin@set_left');
Route::post('admin/set/right', 'admin@set_right');

/**
 * Login routes
 */
Route::get('login', 'auth@index');
Route::any('logout', 'auth@logout');
Route::get('signup', 'auth@signup');
Route::get('confirm', 'auth@confirm');
Route::post('confirm/process', 'auth@number_confirm');
Route::get('confirm/(:num)/(:num)', 'auth@link_confirm');
Route::post('login/process', 'auth@login_process');
Route::post('signup/process', 'auth@signup_process');

/**
 * User routes
 */
Route::post('profile/settings/process', 'user@settings_process');
Route::get('profile/messages',  array('before' => 'auth', 'uses' => 'user@messages'));
Route::post('profile/messages',  array('before' => 'auth', 'uses' => 'user@messages'));
Route::get('profile/outmessages',  array('before' => 'auth', 'uses' => 'user@outmessages'));
Route::post('profile/outmessages',  array('before' => 'auth', 'uses' => 'user@outmessages'));
Route::post('profile/messages/send', 'user@send_message');
Route::get('profile/messages/(:num)', array('before' => 'auth', 'uses' => 'user@inbox'));
Route::get('profile/messages/outbox/(:num)', array('before' => 'auth', 'uses' => 'user@outbox'));
Route::get('information', array('before' => 'confirm', 'uses' => 'user@information'));
Route::post('information/process', array('before' => 'confirm', 'uses' => 'user@information_process'));
Route::get('profile', array('before' => 'information', 'uses' => 'user@profile'));
Route::post('profile', array('before' => 'information', 'uses' => 'user@profile'));
Route::post('language', 'user@language');
Route::get('profile/settings', array('before' => 'auth', 'uses' => 'user@settings'));
Route::get('users', array('before' => 'auth', 'before' => 'confirm', 'before' => 'information', 'uses' => 'user@users'));
Route::post('users', array('before' => 'auth', 'before' => 'confirm', 'before' => 'information', 'uses' => 'user@users'));
Route::get('user/(:num)', array('before' => 'auth', 'uses' => 'user@user'));
Route::post('user/(:num)', array('before' => 'auth', 'uses' => 'user@user'));
Route::get('workouts/(:num)', array('before' => 'auth', 'uses' => 'user@workouts'));
Route::get('user/add/(:num)', array('before' => 'auth', 'uses' => 'user@add_friend'));
Route::get('user/accept/(:num)', array('before' => 'auth', 'uses' => 'user@accept_friend'));
Route::any('search', array('before' => 'auth', 'uses' => 'user@search'));
Route::post('add_feed_comment', array('before' => 'auth', 'uses' => 'user@add_comment'));
Route::post('get_feed_comment', array('before' => 'auth', 'uses' => 'user@get_comment'));

/**
 * Workout routes
 */

Route::get('workout/(:num)/(:num)', array('before' => 'auth', 'uses' => 'workout@index'));
Route::post('workout/get', array('before' => 'auth', 'uses' => 'workout@get_route'));
Route::post('workout/update_calendar', 'workout@update_calendar');
/**
 * Root route
 */
Route::get('/', function()
{
	if(Auth::check()) {
		$user = new User();
		$messages_count = $user->getUserMessages(Auth::user()->user_id, TRUE);
	}
	else {
		$messages_count = '-';
	}
	$admin = new Admin();
	$settings = $admin->getParams();
	return View::make('home.index')->with('messages_count', $messages_count)->with('settings', $settings);
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function() {
	$language = 'en';
	if(Auth::check()) {
		$cur_language = DB::table('user_config')->where('id_user', '=', Auth::user()->user_id)->get();
		$cur_language = (array)$cur_language[0];
		$language = $cur_language['language'];
	}
	elseif (Cookie::has('language')) {
		$language = Cookie::get('language');
	}
	else {
		$language = Session::get('language');
	}
	View::share('language', $language);
});

Route::filter('confirm', function() {
	$user = new User();
    if(Auth::check()) {
        $confirm = $user->getUserConfirmStatus(Auth::user()->user_id);
    }
    else {
        return Redirect::to('/');
    }
	if(!$confirm) {
		return Redirect::to('confirm');
	}
});

Route::filter('information', function () {
	$user = new User();
	if(Auth::check()) {
		if(!$user->checkInformation(Auth::user()->user_id)) {
			return Redirect::to('information');
		}
	}
	else {
		return Redirect::back();
	}
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});