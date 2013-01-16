@section('header')

<div class="header-block">
<img src="../../img/system/logo.png" style="display: inline; float: left; margin-left: 15px; padding-top: 3px;">
	@if(!Auth::check())  
		{{ Form::open('signup', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_signup')->get($language), array('class' => 'grey-button')) }}
		{{ Form::close() }}
		{{ Form::open('login', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_login')->get($language), array('class' => 'grey-button')) }}
		{{ Form::close() }}
	@else 
		{{ Form::open('logout', 'POST') }}
		{{ Form::submit(Lang::line('locale.button_logout')->get($language), array('class' => 'grey-button')) }}
		{{ Form::close() }}
	@endif
</div>
@if(Auth::check())
	<div class="white-block top-menu">
		<!-- {{ Form::open('/', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_home')->get($language), array('class' => 'blue-button')) }}
		{{ Form::close() }}
		{{ Form::open('profile', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_profile')->get($language), array('class' => 'blue-button')) }}
		{{ Form::close() }}
		{{ Form::open('users', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_users')->get($language), array('class' => 'blue-button')) }}
		{{ Form::close() }} -->
		<a href="{{ URL::home() }}" class="blue-button">
			{{ Lang::line('locale.button_home')->get($language) }}
		</a>
		<a href="{{ URL::home() }}profile" class="blue-button">
			{{ Lang::line('locale.button_profile')->get($language) }}
		</a>
		<a href="{{ URL::home() }}users" class="blue-button">
			{{ Lang::line('locale.button_users')->get($language) }}
		</a>
	</div>
@endif
@endsection