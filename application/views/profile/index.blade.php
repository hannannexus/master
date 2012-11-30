@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	
	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		$().ready(function() {
			$('a#user_photo').fancybox(
				{
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	400, 
					'speedOut'		:	400, 
					'overlayShow'	:	true
				}
			);
		});
	</script>
	
@endsection

@section('content')
<div class="white-block">
    <h4 align="center">{{ Lang::line('locale.profile_title')->get($language) }}</h4>
    @if(Session::get('saved') == 'success')
	<div class="alert alert-success">
		{{Lang::line('locale.settings_saved')->get($language) }}
	</div>
	@endif
    <div class="well" style="width: 220px; display: inline-block;">
    	@if($user_data['photo'] != '-')
    	<div>
    		<a id="user_photo" href="{{ URL::home() . 'img/photos/' . $user_data['user_id'] . '/320/' . $user_data['photo'] }}">
    			<img alt="" src="{{ URL::home() . 'img/photos/' . $user_data['user_id'] . '/100/' . $user_data['photo'] }}">
    		</a>
    	</div>
    	@else
    	<div>
    		<img alt="" src="{{ URL::home() . 'img/system/no_image.jpg' }}">
    	</div>
    	@endif
        <span>
            {{ Lang::line('locale.name')->get($language) }} : {{ $user_data['name'] }}
        </span>
        <br />
        <span>
            {{ Lang::line('locale.midname')->get($language) }} : {{ $user_data['patronymic'] }}
        </span>
        <br />
        <span>
            {{ Lang::line('locale.surname')->get($language) }} : {{ $user_data['surname'] }}
        </span>
        <br />
        <span>
            {{ Lang::line('locale.age')->get($language) }} : {{ $user_data['age'] }}
        </span>
        <br />
        <span>
            {{ Lang::line('locale.gender')->get($language) }} : {{ Lang::line('locale.gender_' . $user_data['sex'])->get($language) }}
        </span>
        <hr />
        {{ Form::open('profile/settings', 'GET', array('style' => 'display: inline;')) }}
        {{ Form::submit(Lang::line('locale.button_settings')->get($language), array('class' => 'blue-button')) }}
        {{ Form::close() }}
        <br><br>
        {{ Form::open('profile/workouts', 'GET', array('style' => 'display: inline;')) }}
        {{ Form::submit(Lang::line('locale.button_workouts')->get($language), array('class' => 'blue-button')) }}
        {{ Form::close() }}
    </div>
</div>
@endsection

@include('common.skeleton')