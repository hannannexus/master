@section('title')
	Mhealth Sport
@endsection

@section('content')
@if(isset($settings))
<div class="white-block" style="margin: 0 auto; text-align: center;">
	<div class="well" id="left_panel" style="display:inline-block; height: auto; padding-top: 5px; vertical-align: top;">
		{{ $settings['left_panel_text'] }}
	</div>
	<div class="well" id="left_panel" style="width: 600px; display:inline-block; vertical-align: top;">
		<div class="title-gray">
			{{Lang::line('locale.feed')->get($language)}}
		</div>
		@if(is_null($feed))
			<div class="white-block">
				{{ Lang::line('locale.no_feeds')->get($language) }}
			</div>
		@else
			@foreach($feed as $value)
				<div class="white-block">
					<div style="display:inline-block; align:left;">
						@if(!empty($value['photo']))
							<a id="user_photo" href="{{ URL::home() . 'user/' . $value['id_user'] }}">
								<img src="{{ URL::home() . 'img/photos/' . $value['user_id'] . '/60/' . $value['photo'] }}" >
							</a>
						@else
							<a id="user_photo" href="{{ URL::home() . 'user/' . $value['id_user'] }}">
								<img alt="" src="{{ URL::home() . 'img/system/no_image_60.jpg' }}">
							</a>
						@endif
					</div>
					<div style="display:inline;">
						<a href="{{ URL::home() . 'user/' . $value['id_user'] }}">
							@if(!empty($value['name']))
								{{ $value['name'] . ' ' }}
							@endif
							@if(!empty($value['surname']))
								{{ $value['surname'] . ' ' }}
							@endif
						</a>
						{{ Lang::line('locale.was_out')->get($language) }} 
	    				{{ Lang::line('locale.temp_training')->get($language) }}.
	    				<br>
	    				{{ Lang::line('locale.he_tracked')->get($language) }} 
	    				{{ round($value['distance'], 2) }} 
	    				{{ Lang::line('locale.km')->get($language) }}
	   					{{ Lang::line('locale.in')->get($language) }}
	   					{{ $value['time'] }} 
	   					<a href="{{ URL::home()}}workout/{{ $value['user_id'] }}/{{ $value['workout_number'] }}">
	    					{{ Lang::line('locale.show')->get($language) }}
	    				</a>
	    				<br />
	    				<i style="font-size: x-small;">
	    					<b>
		   						{{ Lang::line('locale.date_doubledot')->get($language) }}
		   						{{ date("d M Y", mktime(0, 0, 0, substr($value['date'], 5, 2), substr($value['date'], 8, 2), substr($value['date'], 0, 4))) }}
		    					{{ Lang::line('locale.time_doubledot')->get($language) }}
		    					{{ substr($value['date'], 11, 5) }}
		    				</b>
		    			</i>
					</div>
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