@section('header')

<div class="white-block" style="text-align:right">
	<?php
	if(!Auth::check()) { 
		echo Form::open('signup', 'GET', array('style' => 'display: inline;'));
		echo Form::submit(Lang::line('locale.button_signup')->get($language), array('class' => 'btn'));
		echo Form::close();
		echo Form::open('login', 'GET', array('style' => 'display: inline;'));
		echo Form::submit(Lang::line('locale.button_login')->get($language), array('class' => 'btn'));
		echo Form::close();
	}
	else {
		echo Form::open('logout', 'POST');
		echo Form::submit(Lang::line('locale.button_logout')->get($language), array('class' => 'btn'));
		echo Form::close();
	}
	?>
</div>

@endsection