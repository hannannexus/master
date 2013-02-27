@section('header')

<div class="header-block">
<img id="logo-image" src="../../img/system/logo.png" style="display: inline; float: left; padding-top: 3px;">
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
	<div class="white-block" style="text-align: center;">
		<a href="{{ URL::home() }}" class="blue-button">
			{{ Lang::line('locale.button_home')->get($language) }}
		</a>
		<a href="{{ URL::home() }}profile" class="blue-button">
			{{ Lang::line('locale.button_profile')->get($language) }}
		</a>
		<a href="{{ URL::home() }}workouts/{{ Auth::user()->user_id }}" class="blue-button">
			{{ Lang::line('locale.my_workouts')->get($language) }}
		</a>
		<a href="{{ URL::home() }}users" class="blue-button">
			{{ Lang::line('locale.button_users')->get($language) }}
		</a>
	</div>
@endif
@endsection