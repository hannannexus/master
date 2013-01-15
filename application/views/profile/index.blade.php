@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	
	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		$().ready(function() {
			$('a#user_photo').fancybox(
				{
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	400, 
					'speedOut'		:	400, 
					'overlayShow'	:	true
				}
			);
		});
	</script>
	
@endsection

@section('content')
<div class="white-block">
    <!-- <h4 align="center">{{ Lang::line('locale.profile_title')->get($language) }}</h4>  -->
    @if(Session::get('saved') == 'success')
	<div class="alert alert-success">
		{{Lang::line('locale.settings_saved')->get($language) }}
	</div>
	@endif
	<table style="margin-left: 200px;">
		<tr>
			<td style="vertical-align: top;">
			    <div class="well" style="width: 220px; display: inline-block;">
				    <div class="title-gray" style="width: auto; height: auto;">
				    	{{ Lang::line('locale.profile_title')->get($language) }}
				    </div>
			    	@if($user_data['photo'] != '-')
			    	<div>
			    		<a id="user_photo" href="{{ URL::home() . 'img/photos/' . $user_data['user_id'] . '/320/' . $user_data['photo'] }}">
			    			<img alt="" src="{{ URL::home() . 'img/photos/' . $user_data['user_id'] . '/100/' . $user_data['photo'] }}">
			    		</a>
			    	</div>
			    	@else
			    	<div>
			    		<img alt="" src="{{ URL::home() . 'img/system/no_image.jpg' }}">
			    	</div>
			    	@endif
			        <span>
			            {{ Lang::line('locale.name')->get($language) }} : {{ $user_data['name'] }}
			        </span>
			        <br />
			        <span>
			            {{ Lang::line('locale.midname')->get($language) }} : {{ $user_data['patronymic'] }}
			        </span>
			        <br />
			        <span>
			            {{ Lang::line('locale.surname')->get($language) }} : {{ $user_data['surname'] }}
			        </span>
			        <br />
			        <span>
			            {{ Lang::line('locale.age')->get($language) }} : {{ $user_data['age'] }}
			        </span>
			        <br />
			        <span>
			            {{ Lang::line('locale.gender')->get($language) }} : {{ Lang::line('locale.gender_' . $user_data['sex'])->get($language) }}
			        </span>
			        <hr />
			        <!-- {{ Form::open('profile/settings', 'GET', array('style' => 'display: inline;')) }}
			        {{ Form::submit(Lang::line('locale.button_settings')->get($language), array('class' => 'blue-button')) }}
			        {{ Form::close() }} -->
			        <a href="{{ URL::home() }}profile/settings" class="blue-button">
			        	{{ Lang::line('locale.button_settings')->get($language) }}
			        </a>
			        <br><br>
			        <!-- {{ Form::open('profile/workouts', 'GET', array('style' => 'display: inline;')) }}
			        {{ Form::submit(Lang::line('locale.button_workouts')->get($language), array('class' => 'blue-button')) }}
			        {{ Form::close() }} -->
			        <a href="{{ URL::home() }}profile/workouts" class="blue-button">
			        	{{ Lang::line('locale.button_workouts')->get($language) }}
			        </a>
			    </div>
		    </td>
		    <td style="vertical-align: top;">
			    <div class="well" style="display: inline-block; width:300px; height: auto; margin-left: 15px;">
			    	<div class="title-gray" style="width: auto; height: auto; margin-bottom: 7px;">
			    		{{ Lang::line('locale.feed')->get($language) }}
			    	</div>
			    	@if(!is_null($feed))
				    	@foreach ($feed as $cur_feed)
			    			<div class="white-block">
			    				<a href="{{ URL::home() }}profile">
			    					{{ $user_data['name'] }} {{ $user_data['surname'] }}
			    				</a> 
 		    					{{ Lang::line('locale.was_out')->get($language) }} 
		    					{{ Lang::line('locale.temp_training')->get($language) }}.
		    					{{ Lang::line('locale.he_tracked')->get($language) }} 
		    					{{ round($cur_feed['distance']/1000, 2) }} 
		    					{{ Lang::line('locale.km')->get($language) }}
		    					{{ Lang::line('locale.in')->get($language) }}
		    					{{ $cur_feed['time'] }} 
		    					<a href="{{ URL::home()}}workout/{{ Auth::user()->user_id }}/{{ $cur_feed['workout_number'] }}">
		    						{{ Lang::line('locale.show')->get($language) }}
		    					</a>
			    			</div>
			    		@endforeach
			    	@else
			    		{{ Lang::line('locale.no_trainings')->get($language) }}
			    	@endif
			    </div>
		    </td>
		    <td style="vertical-align: top;">
			     <div class="well" style="display: inline-block; width:300px; height: auto; margin-left: 15px;">
			    	<div class="title-gray" style="width: auto; height: auto; margin-bottom: 7px;">
			    		{{ Lang::line('locale.search')->get($language) }}
			    	</div>
			    	<input type="search" placeholder="{{ Lang::line('locale.search_lowercase')->get($language) }}">
			    </div>
		    </td>
		</tr>
    </table>
</div>
@endsection

@include('common.skeleton')