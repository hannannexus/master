@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="well admin" style="margin: 0 auto; width: 600px; height: 600px; display: block;">
	<div class="admin-content">
	{{ Form::open('admin/set/manual', 'POST') }}
		<textarea class="code" rows="600" cols="200" name="manual_text" style="display: block; margin: 0 auto; max-height: 550px; max-width: 580px; min-height: 550px; min-width: 580px;">{{ $settings['manual'] }}</textarea><br>
		<input type="submit" class="blue-button" style="display: block; margin: 0 auto;" value="{{ Lang::line('locale.save')->get($language) }}">
	{{ Form::close() }}
	</div>
</div>
@endsection

@include('common.skeleton')