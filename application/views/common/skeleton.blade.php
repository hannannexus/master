@include('common.header')
@include('common.footer')
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		{{ HTML::style('css/bootstrap.css') }}
		{{ HTML::style('css/main/style.css') }}
		{{ HTML::script('js/lib_jquery.js') }}
		{{ HTML::script('js/lib_bootstrap.js') }}
		{{ HTML::script('js/functions_language.js') }}
		
        @yield('meta-custom')
        
		<title>
			@yield('title')
		</title>
	</head>
	<body>
		@yield('header')
		
		@yield('errors')
		
		@yield('content')
		
		@yield('footer')
	</body>
</html>