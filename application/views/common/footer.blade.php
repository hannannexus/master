@section('footer')

<div class="header-block" style="height: 23px;">
	
	{{ Form::open('language', 'POST', array('id' => 'language_form')) }}
	<div class="styled-select" style="text-align: right; display: block; float: right;">
		{{ Form::select('language', array('en' => 'EN', 'cz' => 'CZ', 'ru' => 'RU'), $language, array('onchange' => 'changeLanguage()')) }}
	</div>
	{{ Form::close() }}
	
</div>

@endsection