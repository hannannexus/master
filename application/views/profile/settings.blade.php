@section('title')
	Mhealth Sport
@endsection

@section('errors')
	@if ($errors->has('name'))
	    @foreach ($errors->get('name', '<div class="alert alert-error">:message</div>') as $name_error)
	    	{{ $name_error }}
	    @endforeach
    @endif
    
    @if ($errors->has('surname'))
	    @foreach ($errors->get('surname', '<div class="alert alert-error">:message</div>') as $surname_error)
	    	{{ $surname_error }}
	    @endforeach
    @endif
    
    @if ($errors->has('photo'))
	    @foreach ($errors->get('photo', '<div class="alert alert-error">:message</div>') as $photo_error)
	    	{{ $photo_error }}
	    @endforeach
    @endif
    
@endsection

@section('meta-custom')

	{{ HTML::script('js/lib_jquery_ui.js') }}
	{{ HTML::style('css/jquery-ui.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.css') }}
	{{ HTML::style('css/fancybox/jquery.fancybox.style.css') }}
	{{ HTML::script('js/fancybox/jquery.fancybox-1.3.4.pack.js') }}
	
	<script type="text/javascript">
		$().ready(function() {
			$("#borndate").datepicker(
				{ 
					dateFormat: "yy-mm-dd", 
					changeMonth: true, 
					changeYear: true,
					yearRange : 'c-90:c'
				}
			);
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
    <div class="well" style="width: 640px;  margin: 0 auto;">
	    <div class="title-gray" style="width: auto; height: auto;">
	    	{{ Lang::line('locale.profile_settings')->get($language) }}
	    </div>
	    	{{ Form::open_for_files('profile/settings/process', 'POST') }}
	    <table style="margin-left: auto; margin-right: auto; margin-top: 20px;">
		    <tr>
		    	<td rowspan="5" style="padding-left: 15px;">
		    		@if($user_data['photo'] != '')
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
			    	<br>
			    	{{ Form::label('photo', Lang::line('locale.photo')->get($language)) }}
        			{{ Form::file('photo') }}
		    	</td>
		    	<td style="padding-left: 15px;">
		        	{{ Form::label('name', Lang::line('locale.name')->get($language)) }}
		        </td>
		        <td style="padding-left: 15px;">
		        	{{ Form::text('name', $user_data['name']) }}
		        </td>
		    </tr>
		    <tr>
		    	<td style="padding-left: 15px;">
		        	{{ Form::label('midname', Lang::line('locale.midname')->get($language)) }}
		        </td>
		        <td style="padding-left: 15px;">
		        	{{ Form::text('midname', $user_data['patronymic']) }}
		        </td>
		    </tr>
		    <tr>
		    	<td style="padding-left: 15px;">
		        	{{ Form::label('surname', Lang::line('locale.surname')->get($language)) }}
		        </td>
		        <td style="padding-left: 15px;">
		        	{{ Form::text('surname', $user_data['surname']) }}
		        </td>
		    </tr>
		    <tr>
		    	<td style="padding-left: 15px;">
		        	{{ Form::label('borndate', Lang::line('locale.born_date')->get($language)) }}
		        </td>
		        <td style="padding-left: 15px;">
		        	{{ Form::date('borndate', $user_data['born_date'], array('id' => 'borndate')) }}
		        </td>
		    </tr>
		    <tr>
		    	<td style="padding-left: 15px;">
		        	{{ Form::label('gender', Lang::line('locale.gender')->get($language)) }}
		        </td>
		        <td style="padding-left: 15px;">
			        <div class="styled-select" style="width: 100px;">
			        	{{ Form::select('gender', array('male' => Lang::line('locale.gender_male')->get($language), 'female'=> Lang::line('locale.gender_female')->get($language)), $user_data['sex'], array("style" => "width: 125px;")) }}
			        </div>
		        </td>
		    </tr>
        </table>
        <hr />
        {{ Form::submit(Lang::line('locale.save')->get($language), array('class' => 'blue-button')) }}
        {{ Form::close() }}
    </div>
</div>

@endsection

@include('common.skeleton')