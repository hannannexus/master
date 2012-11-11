@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="white-block">
    @if(Session::get('saved') == 'success')
	<div class="alert alert-success">
		{{Lang::line('locale.settings_saved')->get($language) }}
	</div>
	@endif
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
            {{ Lang::line('locale.age')->get($language) }} : {{ $user_data['age'] }}
        </span>
        <br />
        <span>
            {{ Lang::line('locale.gender')->get($language) }} : {{ Lang::line('locale.gender_' . $user_data['sex'])->get($language) }}
        </span>
    </div>
</div>
@endsection

@include('common.skeleton')