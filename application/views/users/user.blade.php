@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	
	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		comment_count = {{ count($feed) }};
		function loadComments() {
			$(".comment_text").remove();
			$.post(
				'{{ URL::home() }}get_feed_comment',
				{
					id_workout: {{ $user_data['user_id'] }}
				},
				function(result) {
					for(i = 0; i < result.length; i++) {
						$("#comment_line_" + result[i].workout_number).after(
							'<div class="comment_text"><a style="font-size: xx-small;" href=" {{ URL::home()}}user/'+result[i].user_id +'">'+result[i].name+ ' ' + result[i].surname + '</a> <i style="font-size: xx-small;">('+result[i].stamp+')</i> <br>'+result[i].text+'</div>'
						);
					}
				},
				'json'
			);
		}
	
		$(function() {
			loadComments();
			$('a#user_photo').fancybox(
				{
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	400, 
					'speedOut'		:	400, 
					'overlayShow'	:	true
				}
			);
			$(".comment").click(function(event) {
				event.preventDefault();
				var target = $(event.target);
				$("#div_" + target.attr('id')).css('display', 'block');
			});
			$(".form_workout").submit(function(event) {
				event.preventDefault();
				var target = $(event.target);
				var targetId = target.attr('id');
				var workoutNumber = parseInt(targetId.substring(13));
				if($("input[name='workout_" + workoutNumber + "']").val() == '') return;
				$.post(
					'{{ URL::home() }}add_feed_comment',
					{
						workout_number: workoutNumber,
						id_workout: {{ $user_data['user_id'] }},
						comment: $("input[name='workout_" + workoutNumber + "']").val()
					},
					function(result) {
						if(result == 0) {
							$("#div_workout_" + workoutNumber).css('display', 'none');
							$("#div_workout_" + workoutNumber).after('<div class="alert" data-dismiss="alert">Error! Click to hide message.</div>');
							return;
						}
						else {
							loadComments();
							$("input[name='workout_" + workoutNumber + "']").val('');
							$("#div_workout_" + workoutNumber).css('display', 'none');
						}
					},
					'json'
				);
			});
		});
	</script>
	
@endsection

@section('content')
<div class="white-block">
    @if(Session::get('saved') == 'success')
	<div class="alert alert-success">
		{{Lang::line('locale.settings_saved')->get($language) }}
	</div>
	@endif
	<table style="margin: 0 auto;">
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
			        <a href="{{ URL::home() }}workouts/{{ $user_data['id_user'] }}" class="blue-button">
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
			    				<a href="{{ URL::home() }}user/{{ $user_data['user_id'] }}">
			    					{{ $user_data['name'] }} {{ $user_data['surname'] }}
			    				</a> 
 		    					{{ Lang::line('locale.was_out')->get($language) }} 
		    					{{ Lang::line('locale.temp_training')->get($language) }}.
		    					{{ Lang::line('locale.he_tracked')->get($language) }} 
		    					{{ round($cur_feed['distance']/1000, 2) }} 
		    					{{ Lang::line('locale.km')->get($language) }}
		    					{{ Lang::line('locale.in')->get($language) }}
		    					{{ $cur_feed['time'] }} 
		    					<a href="{{ URL::home()}}workout/{{ $user_data['user_id'] }}/{{ $cur_feed['workout_number'] }}">
		    						{{ Lang::line('locale.show')->get($language) }}
		    					</a>
		    					<br />
		    					<i style="font-size: x-small;">
		    						<b>
			    						{{ Lang::line('locale.date_doubledot')->get($language) }}
			    						{{ date("d M Y", mktime(0, 0, 0, substr($cur_feed['date'], 5, 2), substr($cur_feed['date'], 8, 2), substr($cur_feed['date'], 0, 4))) }}
				    					{{ Lang::line('locale.time_doubledot')->get($language) }}
				    					{{ substr($cur_feed['time_start'], 0, 5) }}
			    					</b>
			    				</i>
			    				<a href="#" class="comment" id="workout_{{ $cur_feed['workout_number'] }}">{{ Lang::line('locale.comment')->get($language) }}</a>
			    				<form id="form_workout_{{ $cur_feed['workout_number'] }}" class="form_workout" action="">
				    				<div class="" id="div_workout_{{ $cur_feed['workout_number'] }}" style="display: none; margin-bottom: 5px;">
				    					<input type="text" name="workout_{{ $cur_feed['workout_number'] }}" style="margin-bottom: 0px; width: 270px;" placeholder="{{ Lang::line('locale.your_comment')->get($language) }}">
				    				</div>
			    				</form>
			    				<div id="comment_line_{{ $cur_feed['workout_number'] }}"></div>
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
			    	{{ Form::open('search', 'POST') }}
			    	<input type="search" name="search" placeholder="{{ Lang::line('locale.search_lowercase')->get($language) }}" style="width: 180px;">
			    	{{ Form::submit(Lang::line('locale.search_none_dots')->get($language), array('class' => 'blue-button', 'style' => 'display: inline; margin-left: 10px; margin-top: -6px;')) }}
			    	{{ Form::close() }}
			    </div>
		    </td>
		</tr>
    </table>
</div>
@endsection

@include('common.skeleton')