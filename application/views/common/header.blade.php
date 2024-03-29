@section('header')

<div class="header-block">
<img id="logo-image" src="../../img/system/logo.png" style="display: inline; float: left; padding-top: 3px;">
<a href="{{ URL::home() }}manual" class="grey-button" style="display: inline; float: right; padding-top: 0px;">
	{{ Lang::line('locale.manual')->get($language) }}
</a>
{{ Form::open('language', 'POST', array('id' => 'language_form')) }}
	<div class="styled-select" style="text-align: right; display: inline; float: right; margin-top: 5px; margin-left: 5px;">
		{{ Form::select('language', array('en' => 'EN', 'cz' => 'CZ', 'ru' => 'RU', 'hu' => 'HU'), $language, array('onchange' => 'changeLanguage()')) }}
	</div>
{{ Form::close() }}
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
			{{ Lang::line('locale.friends')->get($language) }}
		</a>
		<a href="{{ URL::home() }}profile/messages" class="blue-button">
			{{ Lang::line('locale.button_messages')->get($language) }} 
			@if(isset($messages_count))
			({{ $messages_count['unread'] }})
			@endif
		</a>
	</div>
@endif
@endsection