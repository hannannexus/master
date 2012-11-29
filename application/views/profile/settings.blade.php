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
		});
	</script>
	
@endsection

@section('content')

<div class="white-block">
    <h4 align="center">{{ Lang::line('locale.profile_title')->get($language) }}</h4>
    {{ Form::open_for_files('profile/settings/process', 'POST') }}
    <div class="well">
        {{ Form::label('name', Lang::line('locale.name')->get($language)) }}
        {{ Form::text('name', $user_data['name']) }}
        {{ Form::label('midname', Lang::line('locale.midname')->get($language)) }}
        {{ Form::text('midname', $user_data['patronymic']) }}
        {{ Form::label('surname', Lang::line('locale.surname')->get($language)) }}
        {{ Form::text('surname', $user_data['surname']) }}
        {{ Form::label('borndate', Lang::line('locale.born_date')->get($language)) }}
        {{ Form::date('borndate', $user_data['born_date'], array('id' => 'borndate')) }}
        {{ Form::label('gender', Lang::line('locale.gender')->get($language)) }}
        {{ Form::select('gender', array('male' => Lang::line('locale.gender_male')->get($language), 'female'=> Lang::line('locale.gender_female')->get($language)), $user_data['sex']) }}
        {{ Form::label('photo', Lang::line('locale.photo')->get($language)) }}
        {{ Form::file('photo') }}
        <br />
        {{ Form::submit(Lang::line('locale.save')->get($language), array('class' => 'btn btn-primary')) }}
    </div>
    {{ Form::close() }}
</div>

@endsection

@include('common.skeleton')