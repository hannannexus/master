<?php
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
Route::get('profile', array('before' => 'confirm', 'uses' => 'user@profile'));
Route::post('language', 'user@language');
Route::get('profile/settings', array('before' => 'auth', 'uses' => 'user@settings'));
Route::get('users', array('before' => 'auth', 'uses' => 'user@users'));
Route::get('user/(:num)', array('before' => 'auth', 'uses' => 'user@user'));
Route::get('profile/workouts', array('before' => 'auth', 'uses' => 'user@workouts'));
Route::get('user/add/(:num)', array('before' => 'auth', 'uses' => 'user@add_friend'));
Route::get('user/accept/(:num)', array('before' => 'auth', 'uses' => 'user@accept_friend'));

/**
 * Workout routes
 */

Route::get('workout/(:num)/(:num)', array('before' => 'auth', 'uses' => 'workout@index'));

/**
 * Root route
 */
Route::get('/', function()
{
	return View::make('home.index');
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
	$confirm = $user->getUserConfirmStatus(Auth::user()->user_id);
	if(!$confirm) {
		return Redirect::to('confirm');
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