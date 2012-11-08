@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="white-block">
    <h4 align="center">{{ Lang::line('locale.profile_title')->get($language) }}</h2>
    <div>
        {{$user_data['user_id']}}
    </div>
</div>
@endsection

@include('common.skeleton')