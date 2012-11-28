@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="white-block">
	@foreach($users as $user)
	<?php $flag = 'false'; ?>
	<div class="alert alert-block" style="display: inline-block; width: 200px; height: 100px; margin: 5px; padding: 5px;">
	
	@if(!empty($user['photo']))
		<img src="{{ URL::home() . 'public/img/photos/' . $user['user_id'] . '/60/' . $user['photo'] }}" style="float: left; display: inline;">
	@else
		<img alt="" src="{{ URL::home() . 'public/img/system/no_image_60.jpg' }}" style="float: left; display: inline;">
	@endif
	
		<a style="font-size: 10pt;" href="{{ URL::home() }}user/{{ $user['user_id'] }}">{{$user['name']}} <br> {{$user['patronymic']}} <br> {{$user['surname']}}</a>
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
				 <br><b><a style="font-size: x-small;" href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">{{ Lang::line('locale.add_to_friends')->get($language) }}</a></b>
			@endif
		@else
			<br><p style="font-size: x-small;" > {{ Lang::line('locale.this_is_you')->get($language) }} </p>
		@endif
		@if($flag == 'true')
			<br><b><a style="font-size: x-small;" href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">{{ Lang::line('locale.add_to_friends')->get($language) }}</a></b>
		@endif
	</div>
	@endforeach
</div>
@endsection

@include('common.skeleton')