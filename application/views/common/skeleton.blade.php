<?php echo HTML::style('css/bootstrap.css'); ?>
<?php echo HTML::script('js/bootstrap.js'); ?>
@include('common.header')
@include('common.footer')

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
        <script type="text/javascript" src="<?php echo URL::home(); ?>js/main.js"></script>
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