@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="well admin">
	<div class="title-gray">
		{{ Lang::line('locale.admin_left_advertisement')->get($language) }}
	</div>
	<div class="admin-content">
		{{ Form::open() }}
		<label for="left_advertisement"> {{ Lang::line('locale.admin_left_advertisement_show')->get($language) }} </label>
		<input type="checkbox" name="left_advertisement" <?  if($settings['left_block_show']) echo 'checked'; ?> >
		<br>
		<label for="left_code">{{ Lang::line('locale.admin_left_advertisement_code')->get($language) }}</label>
		<textarea class="code" rows="" cols="" name="left_code"></textarea>
		<input type="submit" class="blue-button" value="{{ Lang::line('locale.save')->get($language) }}">
		{{ Form::close() }}
	</div>
</div>
@endsection

@include('common.skeleton')