{{ HTML::style('css/bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/main.js') }}
{{ HTML::script('js/jquery-1.8.2.min.js') }}

@include('common.header')
@include('common.footer')

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
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