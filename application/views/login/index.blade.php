@section('title')
Login
@endsection

@section('errors')

    <? $login_error = Session::get('login_error') ?>
    <? $restore = Session::get('restore') ?>
	@if(!empty($login_error))
		<div class="alert alert-error">{{ Session::get('login_error') }}</div>
	@endif
	
	@if(!empty($restore))
		<div class="alert alert-info">{{ Session::get('restore') }}</div>
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
	<a href="https://www.facebook.com/dialog/oauth?client_id=565943740127810&redirect_uri={{ URL::home() }}fblogin&response_type=code" title="Зайти через Facebook"><img src="../../img/system/social/Facebook.png" width="25px" height="25px"></a>
	<a href="http://oauth.vk.com/authorize?client_id=3905879&redirect_uri={{ URL::home() }}vklogin&response_type=code" title="Зайти через ВКонтакте"><img src="../../img/system/social/vk.png" width="25px" height="25px"></a>
	<br>
	<a href="{{ URL::home() }}restore" style="display:inline-block;" >{{ Lang::line('locale.forgot_password')->get($language) }}</a>
	{{ Form::close() }}
</div>	
@endsection

@include('common.skeleton')