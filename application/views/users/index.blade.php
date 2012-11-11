@section('title')
	Mhealth Sport
@endsection

@section('content')
<br>
	@foreach($users as $user)
	<div class="alert alert-block">
		<a href="{{ URL::home() }}user/{{ $user['user_id'] }}">{{$user['name']}} {{$user['patronymic']}} {{$user['surname']}}</a>
	</div>
	@endforeach
@endsection

@include('common.skeleton')