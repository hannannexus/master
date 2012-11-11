@section('header')

<div class="white-block" style="text-align:right">

	@if(!Auth::check())  
		{{ Form::open('signup', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_signup')->get($language), array('class' => 'btn')) }}
		{{ Form::close() }}
		{{ Form::open('login', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_login')->get($language), array('class' => 'btn')) }}
		{{ Form::close() }}
	@else 
		{{ Form::open('logout', 'POST') }}
		{{ Form::submit(Lang::line('locale.button_logout')->get($language), array('class' => 'btn')) }}
		{{ Form::close() }}
	@endif
</div>
@if(Auth::check())
	<div class="white-block">
		{{ Form::open('/', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_home')->get($language), array('class' => 'btn btn-info')) }}
		{{ Form::close() }}
		{{ Form::open('profile', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_profile')->get($language), array('class' => 'btn btn-info')) }}
		{{ Form::close() }}
		{{ Form::open('users', 'GET', array('style' => 'display: inline;')) }}
		{{ Form::submit(Lang::line('locale.button_users')->get($language), array('class' => 'btn btn-info')) }}
		{{ Form::close() }}
	</div>
@endif
@endsection