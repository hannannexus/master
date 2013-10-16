@section('title')
Signup
@endsection

@section('errors')

	<? $email_error = Session::get('email_error'); ?>
    
    @if (!empty($email_error))
        <div class="alert alert-error">{{ Lang::line('locale.email_error')->get($language) }}</div>
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

	@if ($errors->has('password_confirm'))
	    @foreach ($errors->get('password_confirm', '<div class="alert alert-error">:message</div>') as $password_confirm_error)
	    	{{ $password_confirm_error }}
	    @endforeach
    @endif

@endsection

@section('content')
<div class="white-block">
	{{ Form::open('signup/process', 'POST') }}
	{{ Form::label('email', Lang::line('locale.label_signup_email')->get($language)) }}
	{{ Form::text('email') }}
	<br>
	{{ Form::label('password', Lang::line('locale.label_signup_password')->get($language)) }}
	{{ Form::password('password') }}
	<br>
	{{ Form::label('password_confirm', Lang::line('locale.label_signup_confirm')->get($language)) }}
	{{ Form::password('password_confirm') }}
	<br>
	{{ Form::submit(Lang::line('locale.button_signup')->get($language), array('class' => 'blue-button')) }}
	<a href="https://www.facebook.com/dialog/oauth?client_id=565943740127810&redirect_uri=http://www.localsport.com/fbsignup&response_type=code" title="Зайти через Facebook"><img src="../../img/system/social/Facebook.png" width="25px" height="25px"></a>
	<a href="{{ 'http://oauth.vk.com/authorize?client_id=3905879&redirect_uri=http://www.localsport.com/vksignup&response_type=code' }}" title="Зайти через ВКонтакте"><img src="../../img/system/social/vk.png" width="25px" height="25px"></a>
	{{ Form::close() }}
</div>
@endsection

@include('common.skeleton')