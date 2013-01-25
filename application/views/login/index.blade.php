@section('title')
Login
@endsection

@section('errors')

    <? $login_error = Session::get('login_error') ?>
	@if(!empty($login_error))
		<div class="alert alert-error">{{ Session::get('login_error') }}</div>
	@endif
	
	@if ($errors->has('email'))
	    @foreach ($errors->get('email', '<div class="alert alert-error">:message</div>') as $email_error)
	    	{{ $email_error }}
	    @endforeach
    @endif
    
    @if ($errors->has('password'))
	    @foreach ($errors->get('password', '<div class="alert alert-error">:message</div>') as $password_error)
	    	{{ $password_error }}
	    @endforeach
    @endif

@endsection

@section('content')
<div class="white-block">
	{{ Form::open('login/process', 'POST') }}
	{{ Form::label('email', Lang::line('locale.label_login_email')->get($language), array('style' => 'display: block;')) }}
	{{ Form::text('email') }}
	<br>
	{{ Form::label('password', Lang::line('locale.label_login_password')->get($language), array('style' => 'display: block;')) }}
	{{ Form::password('password') }}
	<br>
	{{ Form::submit(Lang::line('locale.button_login')->get($language), array('class' => 'blue-button')) }}
	{{ Form::close() }}
</div>	
@endsection

@include('common.skeleton')