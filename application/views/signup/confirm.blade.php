@section('title')
	Registration Confirm
@endsection

@section('errors')

    <? $confirm_error = Session::get('confirm_error'); ?>
    
    @if (!empty($confirm_error))
        <div class="alert alert-error">{{ $confirm_error }}</div>
    @endif

	@if ($errors->has('number'))
	    @foreach ($errors->get('number', '<div class="alert alert-error">:message</div>') as $number_error)
	    	{{ $number_error }}
	    @endforeach
    @endif

@endsection

@section('content')
<div class="white-block">
	{{ Form::open('confirm/process', 'POST') }}
	{{ Form::label('confirm', Lang::line('locale.label_confirm')->get($language)) }}
	{{ Form::text('number') }}
	<br />
	{{ Form::Submit(Lang::line('locale.button_confirm')->get($language), array('class' => 'btn')) }}
	{{ Form::close() }}
</div>
@endsection

@include('common.skeleton')