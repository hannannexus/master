@section('title')
	Mhealth Sport
@endsection

@section('content')
@if(isset($settings) && !empty($settings))
<div class="white-block" style="margin: 0 auto; text-align: center;">
	<div class="well" id="left_panel" style="display:inline-block; height: auto; padding-top: 5px; vertical-align: top;">
		{{ $settings['left_panel_text'] }}
	</div>
	<div class="well" style="width: 600px; display:inline-block; vertical-align: top;">
		<div class="title-gray">
			{{Lang::line('locale.feed')->get($language)}}
		</div>
		@if(is_null($feed))
			<div class="white-block">
				{{ Lang::line('locale.no_feeds')->get($language) }}
			</div>
		@else
			@foreach ($feed as $cur_feed)
    			<div class="white-block" style="">
	    			<a href="{{ URL::home() }}workout/{{ $cur_feed['user_id'] }}/{{ $cur_feed['workout_number'] }}" style="decoration: none;">
	    				<div style="display: inline-block;">
	    					<div style="margin: 10px; display: inline-block;  width: 120px; height: 120px;">
    							<img alt="" src="{{ URL::home() }}img/workout/sports/{{ $cur_feed['sport_type'] }}.png" width="100%" height="100%">
	    					</div>
	    					<div style="margin: 10px; display: inline-block; font-size: 8pt; float: right;">
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.date_doubledot')->get($language) }} <b>{{ $cur_feed['formatted_date'] }}</b></p>
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.distance_doubledot')->get($language) }} <b>{{ round($cur_feed['distance']/1000, 2) }} {{ Lang::line('locale.km')->get($language) }}</b></p>
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.duration')->get($language) }} <b>{{ $cur_feed['time'] }}</b></p>
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.avg_speed')->get($language) }} <b>{{ round($cur_feed['avg_speed'],2) }} {{ Lang::line('locale.km_h')->get($language) }}</b></p>
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.time_for_km')->get($language) }} <b>{{ $cur_feed['time_for_km'] }}</b></p>
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.avg_pulse')->get($language) }} <b>{{ round($cur_feed['avg_pulse'], 2) }} {{ Lang::line('locale.bps')->get($language) }}</b></p>
	    						<p style="padding: 0; margin: 0;">{{ Lang::line('locale.arrhythmia')->get($language) }} <b>{{ $cur_feed['arrhythmia'] }}</b></p>
	    					</div>
	    				</div>
	    			</a>
    			</div>
    		@endforeach
		@endif
	</div>
	<div class="well" id="left_panel" style="display:inline-block; height: auto; padding-top: 5px; vertical-align: top;">
		{{ $settings['right_panel_text'] }}
	</div>
</div>
@endif
@endsection

@include('common.skeleton')