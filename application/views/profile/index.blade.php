@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="white-block">
    <h4 align="center">{{ Lang::line('locale.profile_title')->get($language) }}</h4>
    {{ Form::open('profile/settings', 'POST') }}
    <div class="well">
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
            {{ Lang::line('locale.age')->get($language) }} : {{ $user_data['patronymic'] }}
        </span>
        <br>
        {{ Form::submit(Lang::line('locale.button_settings')->get($language), array('class' => 'btn btn-primary')) }}
    </div>
    {{ Form::close() }}
</div>
@endsection

@include('common.skeleton')