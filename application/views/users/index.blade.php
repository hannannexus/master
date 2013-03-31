@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')

	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		$(function() {
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
</div>
@endsection

@include('common.skeleton')