@section('footer')

<div class="white-block" style="text-align:right">
	<?php
		echo Form::open('language', 'POST', array('id' => 'language_form'));
		echo Form::select('language', array('en' => 'en', 'cz' => 'cz', 'ru' => 'ru'), $language, array('onchange' => 'changeLanguage();'));
		echo Form::close();
	?>
</div>

@endsection