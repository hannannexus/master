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
	<?php 
		echo Form::open('confirm/process', 'POST');
		echo Form::label('confirm', Lang::line('locale.label_confirm')->get($language));
		echo Form::number('number');
		echo '<br />';
		echo Form::Submit(Lang::line('locale.button_confirm')->get($language), array('class' => 'btn'));
		echo Form::close();
	?>
</div>
@endsection

@include('common.skeleton')