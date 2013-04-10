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
        {{ Form::text('name') }}<br>
        {{ Form::label('surname', Lang::line('locale.surname')->get($language)) }}
        {{ Form::text('surname') }}<br>
        {{ Form::label('gender', Lang::line('locale.gender')->get($language)) }}
        <div class="styled-select" style="width: 100px;">
        	{{ Form::select('gender', array('male' => Lang::line('locale.gender_male')->get($language), 'female'=> Lang::line('locale.gender_female')->get($language)), '', array("style" => "width: 125px;")) }}
        </div>
        <br />
        {{ Form::submit(Lang::line('locale.save')->get($language), array('class' => 'blue-button')) }}
    </div>
	{{ Form::close() }}
</div>
@endsection

@include('common.skeleton')