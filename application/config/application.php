<?php

return array(
		
	'vk_app_id' => '3905879',
	'vk_secret_key' => 'g000gle',
	'vk_auth_url' => 'https://oauth.vk.com/access_token?client_id=[%CLIENT_ID%]&client_secret=[%CLIENT_SECRET%]&code=[%CODE%]&redirect_uri=http://www.localsport.com/vklogin',
	'vk_get_user_url' => 'https://api.vk.com/method/users.get?uids=[%UIDS%]&fields=uid,first_name,last_name,nickname,screen_name,sex,bdate,city,country,timezone,photo_200_orig&access_token=[%TOKEN%]',
	'vk_default_username' => 'vk_[%USER_ID%]_[%MD5%]',
	'vk_salt' => '06d7322c36b8c13e44703a45b3ff5d21',
	/* ================================================================================================ */
	'facebook_app_id' => '565943740127810',
	'facebook_secret_key' => '1d4c265955804eea42203cdbd12bb0fb',
	'facebook_auth_url' => 'https://graph.facebook.com/oauth/access_token?client_id=[%CLIENT_ID%]&redirect_uri=http://www.localsport.com/fblogin&client_secret=[%CLIENT_SECRET%]&code=[%CODE%]',
	'facebook_get_user_url' => 'https://graph.facebook.com/me?access_token=[%TOKEN%]&fields=id,first_name,last_name,middle_name,birthday,gender,picture.type(large)',
	'facebook_default_username' => 'facebook_[%USER_ID%]_[%MD5%]',
	'facebook_salt' => '829eee9aafe0dde1e0b34d305f181dc2',
	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| The URL used to access your application without a trailing slash. The URL
	| does not have to be set. If it isn't, we'll try our best to guess the URL
	| of your application.
	|
	*/

	'url' => '',

	/*
	|--------------------------------------------------------------------------
	| Asset URL
	|--------------------------------------------------------------------------
	|
	| The base URL used for your application's asset files. This is useful if
	| you are serving your assets through a different server or a CDN. If it
	| is not set, we'll default to the application URL above.
	|
	*/

	'asset_url' => '',

	/*
	|--------------------------------------------------------------------------
	| Application Index
	|--------------------------------------------------------------------------
	|
	| If you are including the "index.php" in your URLs, you can ignore this.
	| However, if you are using mod_rewrite to get cleaner URLs, just set
	| this option to an empty string and we'll take care of the rest.
	|
	*/

	'index' => '',

	/*
	|--------------------------------------------------------------------------
	| Application Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the encryption and cookie classes to generate secure
	| encrypted strings and hashes. It is extremely important that this key
	| remains secret and it should not be shared with anyone. Make it about 32
	| characters of random gibberish.
	|
	*/

	'key' => 'YourSecretKeyGoesHere!',

	/*
	|--------------------------------------------------------------------------
	| Profiler Toolbar
	|--------------------------------------------------------------------------
	|
	| Laravel includes a beautiful profiler toolbar that gives you a heads
	| up display of the queries and logs performed by your application.
	| This is wonderful for development, but, of course, you should
	| disable the toolbar for production applications.
	|
	*/

	'profiler' => false,

	/*
	|--------------------------------------------------------------------------
	| Application Character Encoding
	|--------------------------------------------------------------------------
	|
	| The default character encoding used by your application. This encoding
	| will be used by the Str, Text, Form, and any other classes that need
	| to know what type of encoding to use for your awesome application.
	|
	*/

	'encoding' => 'UTF-8',

	/*
	|--------------------------------------------------------------------------
	| Default Application Language
	|--------------------------------------------------------------------------
	|
	| The default language of your application. This language will be used by
	| Lang library as the default language when doing string localization.
	|
	*/

	'language' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Supported Languages
	|--------------------------------------------------------------------------
	|
	| These languages may also be supported by your application. If a request
	| enters your application with a URI beginning with one of these values
	| the default language will automatically be set to that language.
	|
	*/

	'languages' => array(),

	/*
	|--------------------------------------------------------------------------
	| SSL Link Generation
	|--------------------------------------------------------------------------
	|
	| Many sites use SSL to protect their users' data. However, you may not be
	| able to use SSL on your development machine, meaning all HTTPS will be
	| broken during development.
	|
	| For this reason, you may wish to disable the generation of HTTPS links
	| throughout your application. This option does just that. All attempts
	| to generate HTTPS links will generate regular HTTP links instead.
	|
	*/

	'ssl' => true,

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| The default timezone of your application. The timezone will be used when
	| Laravel needs a date, such as when writing to a log file or travelling
	| to a distant star at warp speed.
	|
	*/

	'timezone' => 'UTC',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| Here, you can specify any class aliases that you would like registered
	| when Laravel loads. Aliases are lazy-loaded, so feel free to add!
	|
	| Aliases make it more convenient to use namespaced classes. Instead of
	| referring to the class using its full namespace, you may simply use
	| the alias defined here.
	|
	*/

	'aliases' => array(
		'Auth'       	=> 'Laravel\\Auth',
		'Authenticator' => 'Laravel\\Auth\\Drivers\\Driver',
		'Asset'      	=> 'Laravel\\Asset',
		'Autoloader' 	=> 'Laravel\\Autoloader',
		'Blade'      	=> 'Laravel\\Blade',
		'Bundle'     	=> 'Laravel\\Bundle',
		'Cache'      	=> 'Laravel\\Cache',
		'Config'     	=> 'Laravel\\Config',
		'Controller' 	=> 'Laravel\\Routing\\Controller',
		'Cookie'     	=> 'Laravel\\Cookie',
		'Crypter'    	=> 'Laravel\\Crypter',
		'DB'         	=> 'Laravel\\Database',
		'Eloquent'   	=> 'Laravel\\Database\\Eloquent\\Model',
		'Event'      	=> 'Laravel\\Event',
		'File'       	=> 'Laravel\\File',
		'Filter'     	=> 'Laravel\\Routing\\Filter',
		'Form'       	=> 'Laravel\\Form',
		'Hash'       	=> 'Laravel\\Hash',
		'HTML'       	=> 'Laravel\\HTML',
		'Input'      	=> 'Laravel\\Input',
		'IoC'        	=> 'Laravel\\IoC',
		'Lang'       	=> 'Laravel\\Lang',
		'Log'        	=> 'Laravel\\Log',
		'Memcached'  	=> 'Laravel\\Memcached',
		'Paginator'  	=> 'Laravel\\Paginator',
		'Profiler'  	=> 'Laravel\\Profiling\\Profiler',
		'URL'        	=> 'Laravel\\URL',
		'Redirect'   	=> 'Laravel\\Redirect',
		'Redis'      	=> 'Laravel\\Redis',
		'Request'    	=> 'Laravel\\Request',
		'Response'   	=> 'Laravel\\Response',
		'Route'      	=> 'Laravel\\Routing\\Route',
		'Router'     	=> 'Laravel\\Routing\\Router',
		'Schema'     	=> 'Laravel\\Database\\Schema',
		'Section'    	=> 'Laravel\\Section',
		'Session'    	=> 'Laravel\\Session',
		'Str'        	=> 'Laravel\\Str',
		'Task'       	=> 'Laravel\\CLI\\Tasks\\Task',
		'URI'        	=> 'Laravel\\URI',
		'Validator'  	=> 'Laravel\\Validator',
		'View'       	=> 'Laravel\\View',
	),

);
