@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')

	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		$(function() {
			$('#end').hide();
			ended = false;
			pack = 1;
			$('a#user_photo').fancybox(
				{
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	400, 
					'speedOut'		:	400, 
					'overlayShow'	:	true
				}
			);

			function getPack() {
				$('#end').show();
				$.post(
					'{{URL::home()}}users',
					{
						pack : pack
					},
					function(result) {
						if(result.users.length != 0) {
							for(i=0; i< result.users.length; i++) {
								if(result.users[i].name != '' || result.users[i].surname != '') {
									var flag = false;
									var content = '<div class="well" id="forend'+result.users[i].user_id+'" style="display: block; width: 400px; height: 70px; padding: 10px; margin: 3px auto;">';
									content += '<div style="float: left; display: inline; margin-right: 10px; width: 65px;">';
									if(result.users[i].photo != '') {
										content += "<a id=\"user_photo\" href=\"{{ URL::home()}}img/photos/"+result.users[i].user_id+"'/320/'"+result.users[i].photo+"\">";
										content += "<img src=\"{{ URL::home()}}img/photos/"+result.users[i].user_id+"'/60/'"+result.users[i].photo+"\">";
										content += "</a>";
									}
									else {
										content += "<img alt=\"\" src=\"{{ URL::home() . 'img/system/no_image_60.jpg' }}\">";
									}
									content += "</div>";
									content += '<div style="display: inline; margin-right: 10px; vertical-align: top;">';
									content += "<a style=\"font-size: 9pt; font-weight: bold;\" href=\"{{ URL::home() }}user/"+result.users[i].user_id+"\">";
									content += result.users[i].name + ' ';
									if(result.users[i].patronymic != '') {
										content += result.users[i].patronymic + ' ';
									} 
									content += result.users[i].surname;
									content += '</a></div>';
									content += '<div style="display: block; margin-right: 10px;">';
									
									content+= '<div style="display: block; margin-top: 3px;">';
									content += '<a class="mini-button" href="{{ URL::home() }}workouts/'+result.users[i].user_id+'">';
									content += "{{ Lang::line('locale.friends_workouts')->get($language) }}";
									content += '</a>';
									content += '</div>';
									content += "</div></div>";
									$("#end").after(content);
									$("#end").remove();
									$("#forend"+result.users[i].user_id).after('<div id="end" class="end"></div>');
								}
							}
						}
						else {
							pack--;
							ended = true;
							$("#end").hide();
						}
						pack++;
					},
					'json'
				);
			}

			$(window).scroll(function(){
		        if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		          if(!ended) getPack();
		        }
			}); 
			
		});
	</script>
@endsection

@section('content')
<div class="white-block">
    <div class="centered">
    {{ Form::open('search', 'POST') }}
    <input type="search" name="search" placeholder="{{ Lang::line('locale.search_lowercase')->get($language) }}" style="width: 180px;">
    {{ Form::submit(Lang::line('locale.search_none_dots')->get($language), array('class' => 'blue-button', 'style' => 'display: inline; margin-left: 10px; margin-top: -6px;')) }}
    {{ Form::close() }}
    </div>
@if(!is_null($users))
	@foreach($users as $user)
	@if(!empty($user['name']) || !empty($user['surname']))
		<div class="well" style="display: block; width: 400px; height: 60px; padding: 10px; margin: 3px auto;">
			<div style="float: left; display: inline; margin-right: 10px; width: 65px;">
				@if(!empty($user['photo']))
				<a id="user_photo" href="{{ URL::home() . 'img/photos/' . $user['user_id'] . '/320/' . $user['photo'] }}">
					<img src="{{ URL::home() . 'img/photos/' . $user['user_id'] . '/60/' . $user['photo'] }}" >
				</a>
				@else
					<img alt="" src="{{ URL::home() . 'img/system/no_image_60.jpg' }}">
				@endif
			</div>
			<div style="display: inline; margin-right: 10px; vertical-align: top;">
				<a style="font-size: 9pt; font-weight: bold;" href="{{ URL::home() }}user/{{ $user['user_id'] }}">
				{{$user['name']}} 
				@if(!empty($user['patronymic'])) 
					{{$user['patronymic']}}
				@endif 
				{{$user['surname']}}</a>
				@if(empty($user['patronymic'])) 
				@endif 
			</div>
			@if(isset($user['relation']) && $user['relation'] == 'accepted')
				<div style="display: block; margin-top: 3px;">
					<a class="mini-button" href="{{ URL::home() }}workouts/{{ $user['user_id'] }}">
						{{ Lang::line('locale.friends_workouts')->get($language) }}
					</a>
				</div>
			@elseif(isset($user['relation']) && $user['relation'] == 'waiting')
				<div style="display: block; margin-top: 3px;">
					<a class="mini-button-orange" style="font-size: x-small;" href="{{ URL::home() }}user/accept/{{ $user['user_id'] }}">
						{{ Lang::line('locale.add_to_friends')->get($language) }}
					</a>
				</div>
			@else
				<div style="display: block; margin-top: 3px;">
					<a class="mini-button" style="font-size: x-small;" href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">
						{{ Lang::line('locale.add_to_friends')->get($language) }}
					</a>
				</div>
			@endif
		</div>
	@else
		<div class="well" style="display: block; width: 400px; height: 60px; padding: 10px; margin: 3px auto;">
			{{ Lang::line('locale.no_friends')->get($language) }}
		</div>
	@endif
	@endforeach
@else
	<div class="well" style="display: block; width: 400px; padding: 10px; margin: 3px auto;">
		{{ Lang::line('locale.no_friends')->get($language) }}
	</div>
@endif
	<div id="end" class="end"></div>
</div>
@endsection

@include('common.skeleton')