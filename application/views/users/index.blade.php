@section('title')
	Mhealth Sport
@endsection

@section('content')
<br>
	@foreach($users as $user)
	<?php $flag = 'false'; ?>
	<div class="alert alert-block">
		<a href="{{ URL::home() }}user/{{ $user['user_id'] }}">{{$user['name']}} {{$user['patronymic']}} {{$user['surname']}}</a>
		@if($user['user_id'] != Auth::user()->user_id)
			@if(!empty($friendlist))
				@foreach($friendlist as $list)
					@if($user['user_id'] == $list['id_user'])
						@if($list['relation'] == 'accepted')
							{{ Lang::line('locale.your_friend')->get($language) }}
							<?php break; ?>
						@else
							{{ Lang::line('locale.wants_to_be_friends')->get($language) }} 
							<b><a href="{{ URL::home }}user/accept/{{ $user['user_id'] }}">{{ Lang::line('locale.accept')->get($language) }}</a></b>
							<?php break; ?>
						@endif
					@elseif($user['user_id'] == $list['id_friend'])
						@if($list['relation'] == 'accepted')
							{{ Lang::line('locale.your_friend')->get($language) }}
							<?php break; ?>
						@else
							{{ Lang::line('locale.you_sent_request')->get($language) }}
							<?php break; ?> 
						@endif
					@endif
				@endforeach
			@else
				 <b><a href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">{{ Lang::line('locale.add_to_friends')->get($language) }}</a></b>
			@endif
		@else
			{{ Lang::line('locale.this_is_you')->get($language) }}
		@endif
		@if($flag == 'true')
			<b><a href="{{ URL::home() }}user/add/{{ $user['user_id'] }}">{{ Lang::line('locale.add_to_friends')->get($language) }}</a></b>
		@endif
	</div>
	@endforeach
@endsection

@include('common.skeleton')