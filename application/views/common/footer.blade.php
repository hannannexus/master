@section('footer')

<div class="header-block">
	
	{{ Form::open('language', 'POST', array('id' => 'language_form')) }}
	{{ Form::select('language', array('en' => 'en', 'cz' => 'cz', 'ru' => 'ru'), $language, array('onchange' => 'changeLanguage()')) }}
	{{ Form::close() }}
	
</div>

@endsection