@include('common.header')
@include('common.footer')
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		{{ HTML::style('css/bootstrap.css') }}
		{{ HTML::script('js/jquery-1.8.2.min.js') }}
		{{ HTML::script('js/bootstrap.js') }}
		{{ HTML::script('js/main.js') }}
		
        @yield('meta-custom')
        
		<title>
			@yield('title')
		</title>
	</head>
	@if(isset($workout_number))
		<body onload="showMap('{{ URL::home() }}', {{ $id_user }}, {{ $workout_number }})">
	@else
		<body>
	@endif
	
		@yield('header')
		
		@yield('errors')
		
		@yield('content')
		
		@yield('footer')
	</body>
</html>