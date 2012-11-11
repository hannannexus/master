@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="white-block">
    <h4 align="center">{{ Lang::line('locale.profile_title')->get($language) }}</h4>
    {{ Form::open('profile/settings/process', 'POST') }}
    <div class="well">
        {{ Form::label('name', Lang::line('locale.name')->get($language)) }}
        {{ Form::text('name', $user_data['name']) }}
        {{ Form::label('midname', Lang::line('locale.midname')->get($language)) }}
        {{ Form::text('midname', $user_data['patronymic']) }}
        {{ Form::label('surname', Lang::line('locale.surname')->get($language)) }}
        {{ Form::text('surname', $user_data['surname']) }}
        {{ Form::label('borndate', Lang::line('locale.born_date')->get($language)) }}
        {{ Form::date('borndate', $user_data['born_date']) }}
        {{ Form::label('gender', Lang::line('locale.gender')->get($language)) }}
        {{ Form::select('gender', array('male' => Lang::line('locale.gender_male')->get($language), 'female'=> Lang::line('locale.gender_female')->get($language), '' => '' ), $user_data['sex']) }}
        <br />
        {{ Form::submit(Lang::line('locale.save')->get($language), array('class' => 'btn btn-primary')) }}
    </div>
    {{ Form::close() }}
</div>
@endsection

@include('common.skeleton')