@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	
	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		comment_count = {{ count($feed) }};
		pack = 1;
		ended = false;

		function roundPlus(x, n) { //x - число, n - количество знаков 
		  if(isNaN(x) || isNaN(n)) return false;
		  var m = Math.pow(10,n);
		  return Math.round(x*m)/m;
		}
					
		
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
			$('#end').hide();
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

			function getPack() {
				$('#end').show();
				$.post (
					'{{URL::home()}}user/'+{{$user_data['user_id']}},
					{
						pack : pack
					},
					function (result) {
						if(result.length != 0) {
							for(i = 0; i < result.length; i++) {
								input = '<div class="white-block" id="forend'+result[i].workout_number+'">';
			    				input += '<a href="{{ URL::home() }}profile">';
			    				input += "{{ $user_data['name'] }} {{ $user_data['surname'] }} ";
			    				input += '</a>'; 
			    				input += "{{ Lang::line('locale.was_out')->get($language) }} "; 
		    					input += "{{ Lang::line('locale.temp_training')->get($language) }}. ";
		    					input += "{{ Lang::line('locale.he_tracked')->get($language) }} "; 
		    					input += roundPlus(result[i].distance, 2) + ' ';
		    					input += "{{ Lang::line('locale.km')->get($language) }} ";
		    					input += "{{ Lang::line('locale.in')->get($language) }} ";
		    					input += result[i].time + ' '; 
		    					input += "<a href=\"{{ URL::home()}}workout/"+result[i].user_id+"/"+result[i].workout_number+"\">";
		    					input += "{{ Lang::line('locale.show')->get($language) }}";
		    					input += "</a>";
		    					input += "<br />";
		    					input += '<i style="font-size: x-small;">';
		    					input += '<b>';
			    				input += "{{ Lang::line('locale.date_doubledot')->get($language) }} ";
			    				input += result[i].date + ' ';
				    			input += "{{ Lang::line('locale.time_doubledot')->get($language) }} ";
				    			input += result[i].date + ' ';
			    				input += "</b>";
			    				input += "</i>";
			    				input += "<a href=\"#\" class=\"comment"+result[i].workout_number+"\" id=\"workout_"+result[i].workout_number+"\">{{ Lang::line('locale.comment')->get($language) }}</a>";
			    				input += "<form id=\"form_workout_"+result[i].workout_number+"\" class=\"form_workout"+result[i].workout_number+"\" action=\"\">";
				    			input += "<div class=\"\" id=\"div_workout_"+result[i].workout_number+"\" style=\"display: none; margin-bottom: 5px;\">";
				    			input += "<input type=\"text\" name=\"workout_"+result[i].workout_number+"\" style=\"margin-bottom: 0px; width: 270px;\" placeholder=\"{{ Lang::line('locale.your_comment')->get($language) }}\">";
				    			input += "</div>";
			    				input += "</form>";
			    				input += "<div id=\"comment_line_"+result[i].workout_number+"\"></div>";
			    				input += "</div>";
		    					$("#end").after(input);
		    					$("#end").remove();
		    					$("#forend"+result[i].workout_number).after('<div id="end" class="end"></div>');

		    					$(".comment"+result[i].workout_number).click(function(event) {
		    						event.preventDefault();
		    						var target = $(event.target);
		    						$("#div_" + target.attr('id')).css('display', 'block');
		    					});
		    					$(".form_workout"+result[i].workout_number).submit(function(event) {
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
							}
							loadComments();
						}
						else {
							ended = true;
							pack--;
							$("#end").hide();
						}
					},
					'json'
				);
				pack++;
			};
			
			$(window).scroll(function(){
		        if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		          if (!ended) getPack();
		        }
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
    			    	<div class="centered">
    			    		<a id="user_photo" href="{{ $user_data['photo'] }}">
    			    			<img alt="" src="{{ $user_data['photo_min'] }}">
    			    		</a>
    			    	</div>
			    	@else
    			    	<div>
    			    		<img alt="" src="{{ URL::home() . 'img/system/no_image.jpg' }}">
    			    	</div>
			    	@endif
			    	<div class="centered" style="margin-top: 10px;">
			    	    <b>{{ $user_data['name'] }} @if($user_data['patronymic'] != '-') {{ $user_data['patronymic'] }} @endif {{ $user_data['surname'] }}</b>
			    	    <br>
			    	    @if($user_data['sex'] == 'male')
			    	        <img alt="{{ $user_data['sex'] }}" src="{{ URL::home() . 'img/system/sex-male.png' }}">
			    	    @endif
			    	    @if($user_data['sex'] == 'female')
			    	        <img alt="{{ $user_data['sex'] }}" src="{{ URL::home() . 'img/system/sex-female.png' }}">
			    	    @endif
			    	    <br>
			    	    @if($user_data['age'] != 0)
			    	        <span>
        			            {{ Lang::line('locale.age')->get($language) }} : {{ $user_data['age'] }}
        			        </span>
        			    @endif
			    	</div>
			    	@if(isset($stats))
    			    	<div class="centered">
        			    	<table class="centered">
        			    	    <tr>
        			    	        <td>
        			    	            <img alt="" src="{{ URL::home() . 'img/system/flag-red-icon.png' }}"  width="21px" height="21px">
        			    	        </td>
        			    	        <td>
        			    	            {{ $stats['total_distance'] }} {{ Lang::line('locale.km')->get($language) }}
        			    	        </td>
        			    	    </tr>
        			    	    <tr>
        			    	        <td>
        			    	            <img alt="" src="{{ URL::home() . 'img/system/clock-icon.png' }}"  width="21px" height="21px">
        			    	        </td>
        			    	        <td>
        			    	            {{ $stats['total_time'] }}
        			    	        </td>
        			    	    </tr>
        			    	    <tr>
        			    	        <td>
        			    	            <img alt="" src="{{ URL::home() . 'img/system/speed-icon.png' }}"  width="24px" height="24px">
        			    	        </td>
        			    	        <td>
        			    	            {{ $stats['avg_speed'] }} {{ Lang::line('locale.km_h')->get($language) }}
        			    	        </td>
        			    	    </tr>
        			    	</table>
    			    	</div>
    			    @endif
			        <hr />
			        @if($user_data['user_id'] != Auth::user()->user_id)
			        <div class="centered">
						@if(!$friend)
							@if(is_null($status))
								<a class="mini-button" style="font-size: x-small;" href="{{ URL::home() }}user/add/{{ $user_data['user_id'] }}">
									{{ Lang::line('locale.add_to_friends')->get($language) }}
								</a>
								<br>
								<br>
							@endif
							@if($status == 'waiting_for_confirm')
								<div class="info-message" style="font-size: x-small;" >
									{{ Lang::line('locale.you_sent_request')->get($language) }}
								</div>
								<br>
							@endif
							@if($status == 'confirm_friend')
								<div class="info-message" style="font-size: x-small;" >
									{{ Lang::line('locale.wants_to_be_friends')->get($language) }}
								</div>
								<br>
								<b>
									<a class="mini-button" href="{{ URL::home() }}user/accept/{{ $user_data['user_id'] }}">
										{{ Lang::line('locale.accept')->get($language) }}
									</a>
								</b>
								<br>
								<br>
							@endif
						@else
							<div class="green-message" style="font-size: x-small;" >
								{{ Lang::line('locale.your_friend')->get($language) }}
							</div>
							<br>
						@endif
					</div>
					@endif
					<div class="centered">
    			        <a href="{{ URL::home() }}workouts/{{ Auth::user()->user_id }}" class="blue-button" style="margin-top: 7px; width:120px;">
    			        	{{ Lang::line('locale.button_workouts')->get($language) }}
    			        </a>
			        </div>
			    </div>
		    </td>
		    <td style="vertical-align: top;">
			    <div class="well" style="display: inline-block; width:420px; height: auto; margin-left: 15px;">
			    	<div class="title-gray" style="width: auto; height: auto; margin-bottom: 7px;">
			    		{{ Lang::line('locale.feed')->get($language) }}
			    	</div>
			    	@if(!is_null($feed))
				    	@foreach ($feed as $cur_feed)
			    			<div class="white-block">
				    			<a href="{{ URL::home() }}workout/{{ $cur_feed['user_id'] }}/{{ $cur_feed['workout_number'] }}" style="decoration: none;">
				    				<div style="display: inline-block;">
				    					<div style="margin: 10px; display: inline-block;  width: 110px; height: 110px;">
			    							<img alt="" src="{{ URL::home() }}img/workout/sports/{{ $cur_feed['sport_type'] }}.png" width="100%" height="100%">
				    					</div>
				    					<div style="margin: 10px; display: inline-block; font-size: 8pt; float: right;">
				    						<p style="padding: 0;">{{ Lang::line('locale.date_doubledot')->get($language) }} <b>{{ $cur_feed['formatted_date'] }}</b></p>
				    						<p style="padding: 0;">{{ Lang::line('locale.distance_doubledot')->get($language) }} <b>{{ round($cur_feed['distance'], 2) }} {{ Lang::line('locale.km')->get($language) }}</b></p>
				    						<p style="padding: 0;">{{ Lang::line('locale.duration')->get($language) }} <b>{{ $cur_feed['time'] }}</b></p>
				    						<p style="padding: 0;">{{ Lang::line('locale.avg_speed')->get($language) }} <b>{{ round($cur_feed['avg_speed'],2) }} {{ Lang::line('locale.km_h')->get($language) }}</b></p>
				    						<p style="padding: 0;">{{ Lang::line('locale.time_for_km')->get($language) }} <b>{{ $cur_feed['time_for_km'] }}</b></p>
				    						<p style="padding: 0;">{{ Lang::line('locale.avg_pulse')->get($language) }} <b>{{ $cur_feed['avg_pulse'] }} {{ Lang::line('locale.bps')->get($language) }}</b></p>
				    						<p style="padding: 0;">{{ Lang::line('locale.arrhythmia')->get($language) }} <b>{{ $cur_feed['arrhythmia'] }}</b></p>
				    					</div>
				    				</div>
				    			</a>
				    			<div style="display: block;">
					    			<a href="#" class="comment" id="workout_{{ $cur_feed['workout_number'] }}">{{ Lang::line('locale.comment')->get($language) }}</a>
				    				<form id="form_workout_{{ $cur_feed['workout_number'] }}" class="form_workout" action="">
					    				<div class="" id="div_workout_{{ $cur_feed['workout_number'] }}" style="display: none; margin-bottom: 5px;">
					    					<input type="text" name="workout_{{ $cur_feed['workout_number'] }}" style="margin-bottom: 0px; width: 390px;" placeholder="{{ Lang::line('locale.your_comment')->get($language) }}">
					    				</div>
				    				</form>
			    					<div id="comment_line_{{ $cur_feed['workout_number'] }}"></div>
		    					</div>
			    			</div>
			    		@endforeach
			    		<div id="end" class="end"></div>
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