@section('title')
	Mhealth Sport
@endsection

@section('content')
{{ Form::open('restore/send', 'POST', array('id' => 'restore')) }}
	{{ Form::label('email', Lang::line('locale.label_signup_email')->get($language)) }}
	{{ Form::text('email') }}
	{{ Form::submit(Lang::line('locale.button_restore')->get($language), array('class' => 'blue-button')) }}
{{ Form::close() }}
	
@endsection

@include('common.skeleton')