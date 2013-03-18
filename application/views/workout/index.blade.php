@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	{{ HTML::script('http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBcvvPkZZG00mSIrEMyrc4mc5w38c0xooY&sensor=true') }}
	{{ HTML::script('js/lib_infobox.js') }}
	{{ HTML::script('js/flot/jquery.flot.js') }}
	{{ HTML::script('js/flot/jquery.flot.crosshair.js') }}
	{{ HTML::script('js/flot/jquery.flot.navigate.js') }}
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
		DAYS = [];
		DAYS[0] = '{{ Lang::line('locale.sunday')->get($language) }}';
		DAYS[1] = '{{ Lang::line('locale.monday')->get($language) }}';
		DAYS[2] = '{{ Lang::line('locale.tuesday')->get($language) }}';
		DAYS[3] = '{{ Lang::line('locale.wednesday')->get($language) }}';
		DAYS[4] = '{{ Lang::line('locale.thursday')->get($language) }}';
		DAYS[5] = '{{ Lang::line('locale.friday')->get($language) }}';
		DAYS[6] = '{{ Lang::line('locale.saturday')->get($language) }}';

		$(function() {
	        $( "#radio_div_1" ).buttonsetv();
	        $( "#radio_div_2" ).buttonsetv();
	        $( "#tabs").tabs();
	    });
		
	</script>
	
@endsection

@section('content')

<div class="white-block">
	<div class="well" style="margin-bottom: 5px;">
		<table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td style="vertical-align: top; padding-top: 18px;">
					<table id="calendar" class="calendar" border="1" cellspacing="0" align="center" style="vertical-align: top; border-color: #1c5d9e; font-size: 10pt; font-family: 'Century Gothic', 'Helvetica';">
					</table>
				</td>
				<td style="vertical-align: top;">
					<div id="months-picker" style="margin-top: 13px; margin-left: 15px; visibility: hidden;">
						<div id="radio_div_1" class="calendar_months" >
							<input type="radio" id="radio1" name="radio" value="1"/><label for="radio1">{{ Lang::line('locale.january')->get($language) }}</label>
					        <input type="radio" id="radio2" name="radio" value="2"/><label for="radio2">{{ Lang::line('locale.february')->get($language) }}</label>
					        <input type="radio" id="radio3" name="radio" value="3"/><label for="radio3">{{ Lang::line('locale.march')->get($language) }}</label>
					        <input type="radio" id="radio4" name="radio" value="4"/><label for="radio4">{{ Lang::line('locale.april')->get($language) }}</label>
							<input type="radio" id="radio5" name="radio" value="5"/><label for="radio5">{{ Lang::line('locale.may')->get($language) }}</label>
					        <input type="radio" id="radio6" name="radio" value="6"/><label for="radio6">{{ Lang::line('locale.june')->get($language) }}</label>
					    </div>
					    <div id="radio_div_2" class="calendar_months">
					        <input type="radio" id="radio7" name="radio" value="7"/><label for="radio7">{{ Lang::line('locale.july')->get($language) }}</label>
					        <input type="radio" id="radio8" name="radio" value="8"/><label for="radio8">{{ Lang::line('locale.august')->get($language) }}</label>
							<input type="radio" id="radio9" name="radio" value="9"/><label for="radio9">{{ Lang::line('locale.september')->get($language) }}</label>
					        <input type="radio" id="radio10" name="radio" value="10"/><label for="radio10">{{ Lang::line('locale.october')->get($language) }}</label>
					        <input type="radio" id="radio11" name="radio" value="11"/><label for="radio11">{{ Lang::line('locale.november')->get($language) }}</label>
					        <input type="radio" id="radio12" name="radio" value="12"/><label for="radio12">{{ Lang::line('locale.december')->get($language) }}</label>
						</div>
					</div>
					<div id="years-picker" style="margin-left: 15px; visibility: hidden;">
						<div class="styled-select" style="width: 210px; display: inline-block; margin-top: 5px;">
							<select id="year" style="width: 235px;">
								<option value="2012">2012</option>
								<option value="2013">2013</option>
							</select>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="well" id="chart_container">
		<div id="map_canvas" style="height: 400px; width: 1000px; margin: 0 auto; position: relative;" ></div>
		<hr>
		<p align="center">
			<a href="#" id="show_pulse" class="blue-button">
				{{ Lang::line('locale.show_pulse')->get($language) }}
			</a>
			<a href="#" id="show_chart" class="blue-button" >
				{{ Lang::line('locale.show_chart')->get($language) }}
			</a>
		</p>
		<div style="height: 140px;">
			<div id="chart_canvas" style="height: 120px; width: 800px; margin: 0 auto; position: relative;" ></div>
			<div id="pulse_canvas" style="height: 120px; width: 800px; margin: 0 auto; position: relative;" ></div>
		</div>
	</div>
</div>

@endsection

@include('common.skeleton')