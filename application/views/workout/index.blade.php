@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	{{ HTML::script('http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBcvvPkZZG00mSIrEMyrc4mc5w38c0xooY&sensor=true') }}
	{{ HTML::script('js/lib_infobox.js') }}
	{{ HTML::script('js/flot/jquery.flot.js') }}
	{{ HTML::script('js/flot/jquery.flot.crosshair.js') }}
	{{ HTML::script('js/functions_math.js') }}
	{{ HTML::script('js/functions_api.js') }}
	{{ HTML::script('js/main_map_chart.js') }}
	
	<script type="text/javascript">
		/* Defining variables */
		HOME 		= '{{ URL::home() }}';
		ID_USER 	= {{ $id_user }};
		W_NUMBER	= {{ $workout_number }};
	</script>
	
@endsection

@section('content')

<div class="white-block">
	<div>
		<div class="calendar_left">
		</div>
		<table id="calendar" class="calendar" border="1" cellspacing="0" align="center">
		</table>
		<div class="calendar_right">
		</div>
	</div>
	<div id="map_canvas" style="height: 500px; width: 800px; margin: 0 auto; position: relative;" ></div>
	<hr>
	<div id="chart_canvas" style="height: 120px; width: 800px; margin: 0 auto; position: relative;" ></div>
</div>

@endsection

@include('common.skeleton')