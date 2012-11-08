@section('title')
Signup
@endsection

@section('errors')

	@if ($errors->has('email'))
	    @foreach ($errors->get('email', '<p class="error-message">:message</p>') as $email_error)
	    	{{ $email_error }}
	    @endforeach
    @endif
    
    @if ($errors->has('password'))
	    @foreach ($errors->get('password', '<p class="error-message">:message</p>') as $password_error)
	    	{{ $password_error }}
	    @endforeach
    @endif

	@if ($errors->has('password_confirm'))
	    @foreach ($errors->get('password_confirm', '<p class="error-message">:message</p>') as $password_confirm_error)
	    	{{ $password_confirm_error }}
	    @endforeach
    @endif

@endsection

@section('content')
<div class="white-block">
	<?php
		echo Form::open('signup/process', 'POST');
		echo Form::label('email', Lang::line('locale.label_signup_email')->get($language));
		echo Form::text('email');
		echo '<br>';
		echo Form::label('password', Lang::line('locale.label_signup_password')->get($language));
		echo Form::password('password');
		echo '<br>';
		echo Form::label('password_confirm', Lang::line('locale.label_signup_confirm')->get($language));
		echo Form::password('password_confirm');
		echo '<br>';
		echo Form::submit(Lang::line('locale.button_signup')->get($language), array('class' => 'btn'));
		echo Form::close();
	?>
</div>
@endsection

@include('common.skeleton')