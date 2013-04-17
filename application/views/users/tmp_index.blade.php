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
									if(result.users[i].user_id != {{Auth::user()->user_id}}) {
										if(result.friendlist.length != 0) {
											for(j=0; j < result.friendlist.length; j++) {
												if(result.users[i].user_id == result.friendlist[j].id_user) {
													if(result.friendlist[j].relation === 'accepted') {
														content += "<br><p style=\"font-size: x-small;\" >{{ Lang::line('locale.your_friend')->get($language) }} </p>";
														flag = false;
														break;
													}
													else {
														content += "<br><p style=\"font-size: x-small;\" >{{ Lang::line('locale.wants_to_be_friends')->get($language) }} </p>";
														content += "<b><a href=\"{{ URL::home() }}user/accept/"+result.users[i].user_id+"\">{{ Lang::line('locale.accept')->get($language) }}</a></b>";
														flag = false;
														break;
													}
												}
												if(result.users[i].user_id == result.friendlist[j].id_friend) {
													if(result.friendlist[j].relation === 'accepted') {
														content += "<br><p style=\"font-size: x-small;\" >{{ Lang::line('locale.your_friend')->get($language) }} </p>";
														flag = false;
														break;
													}
													else {
														content += "<p style=\"font-size: x-small;\" > {{ Lang::line('locale.you_sent_request')->get($language) }} </p>";
														flag = false;
														break; 
													}
												}
												flag = true;
											}
										}
										else {
											content += "<br><a class=\"mini-button\" style=\"font-size: x-small;\" href=\"{{ URL::home() }}user/add/"+result.users[i].user_id+"\">{{ Lang::line('locale.add_to_friends')->get($language) }}</a>";
										}
									}
									else {
										content += "<br><p style=\"font-size: x-small;\" > {{ Lang::line('locale.this_is_you')->get($language) }} </p>";
									}
									if(flag == true) {
										content += "<br><a class=\"mini-button\" style=\"font-size: x-small;\" href=\"{{ URL::home() }}user/add/"+result.users[i].user_id+"\">{{ Lang::line('locale.add_to_friends')->get($language) }}</a>";
									}
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
	@foreach($users as $user)
	@if(!empty($user['name']) || !empty($user['surname']))
		<?php $flag = 'false'; ?>
		<div class="well" style="display: block; width: 400px; height: 70px; padding: 10px; margin: 3px auto;">
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
			<div style="display: block; margin-right: 10px;">
				@if($user['user_id'] != Auth::user()->user_id)
					@if(!empty($friendlist))
						@foreach($friendlist as $list)
							@if($user['user_id'] == $list['id_user'])
								@if($list['relation'] == 'accepted')
									<br><p style="font-size: x-small;" >{{ Lang::line('locale.your_friend')->get($language) }} </p>
									<?php $flag = 'false'; ?>
									<?php break; ?>
								@else
									<br><p style="font-size: x-small;" >{{ Lang::line('locale.wants_to_be_friends')->get($language) }} </p>
									<b><a href="{{ URL::home() }}user/accept/{{ $user['user_id'] }}">{{ Lang::line('locale.accept')->get($language) }}</a></b>
									<?php $flag = 'false'; ?>
									<?php break; ?>
								@endif
							@elseif($user['user_id'] == $list['id_friend'])
								@if($list['relation'] == 'accepted')
									<br><p style="font-size: x-small;" >{{ Lang::line('locale.your_friend')->get($language) }} </p>
									<?php $flag = 'false'; ?>
									<?php break; ?>
								@else
									<p style="font-size: x-small;" > {{ Lang::line('locale.you_sent_request')->get($language) }} </p>
									<?php $flag = 'false'; ?>
									<?php break; ?> 
								@endif
							@endif
							<?php $flag = 'true'; ?>
						@endforeach
					@else
						 <br><a class="mini-button" style="font-size: x-small;" href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">{{ Lang::line('locale.add_to_friends')->get($language) }}</a>
					@endif
				@else
					<br><p style="font-size: x-small;" > {{ Lang::line('locale.this_is_you')->get($language) }} </p>
				@endif
				@if($flag == 'true')
					<br><a class="mini-button" style="font-size: x-small;" href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">{{ Lang::line('locale.add_to_friends')->get($language) }}</a>
				@endif
			</div>
		</div>
	@endif
	@endforeach
	<div id="end" class="end"></div>
</div>
@endsection

@include('common.skeleton')