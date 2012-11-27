@section('title')
	Registration Confirm
@endsection

@section('errors')

    <? $information_error = Session::get('information_error'); ?>
    
    @if (!empty($information_error))
        <div class="alert alert-error">{{ Lang::line('locale.information_error')->get($language) }}</div>
    @endif

@endsection

@section('content')
<div class="white-block">
	<p> {{ Lang::line('locale.information_form')->get($language) }} </p>
	{{ Form::open('information/process', 'POST') }}
	<div class="well">
        {{ Form::label('name', Lang::line('locale.name')->get($language)) }}
        {{ Form::text('name') }}
        {{ Form::label('surname', Lang::line('locale.surname')->get($language)) }}
        {{ Form::text('surname') }}
        {{ Form::label('gender', Lang::line('locale.gender')->get($language)) }}
        {{ Form::select('gender', array('male' => Lang::line('locale.gender_male')->get($language), 'female'=> Lang::line('locale.gender_female')->get($language))) }}
        <br />
        {{ Form::submit(Lang::line('locale.save')->get($language), array('class' => 'btn btn-primary')) }}
    </div>
	{{ Form::close() }}
</div>
@endsection

@include('common.skeleton')