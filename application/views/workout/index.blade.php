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
	{{ HTML::script('js/lib_jquery_ui.js') }}
	{{ HTML::style('css/jquery-ui.css') }}
	
	<script type="text/javascript">
		/* Defining variables */
		HOME 		= '{{ URL::home() }}';
		ID_USER 	= {{ $id_user }};
		W_NUMBER	= {{ $workout_number }};

		$(function() {
	        $( "#radio_div_1" ).buttonsetv();
	        $( "#radio_div_2" ).buttonsetv();
	    });
		
	</script>
	
@endsection

@section('content')

<div class="white-block">
	<div>
		<!-- <div class="calendar_left">
		</div> -->
		<table id="calendar" class="calendar" border="1" cellspacing="0" align="center">
		</table>
		<!-- <div class="calendar_right">
		</div> -->
		<div id="radio_div_1" class="calendar_months">
			<input type="radio" id="radio1" name="radio" /><label for="radio1">January</label>
	        <input type="radio" id="radio2" name="radio" /><label for="radio2">February</label>
	        <input type="radio" id="radio3" name="radio" /><label for="radio3">March</label>
	        <input type="radio" id="radio4" name="radio" /><label for="radio4">April</label>
			<input type="radio" id="radio5" name="radio" /><label for="radio5">May</label>
	        <input type="radio" id="radio6" name="radio" /><label for="radio6">June</label>
	    </div>
	    <div id="radio_div_2" class="calendar_months">
	        <input type="radio" id="radio7" name="radio" /><label for="radio7">July</label>
	        <input type="radio" id="radio8" name="radio" /><label for="radio8">August</label>
			<input type="radio" id="radio9" name="radio" /><label for="radio9">September</label>
	        <input type="radio" id="radio10" name="radio" /><label for="radio10">October</label>
	        <input type="radio" id="radio11" name="radio" /><label for="radio11">November</label>
	        <input type="radio" id="radio12" name="radio" /><label for="radio12">December</label>
		</div>
	</div>
	<div id="map_canvas" style="height: 500px; width: 800px; margin: 0 auto; position: relative;" ></div>
	<hr>
	<div id="chart_canvas" style="height: 120px; width: 800px; margin: 0 auto; position: relative;" ></div>
</div>

@endsection

@include('common.skeleton')