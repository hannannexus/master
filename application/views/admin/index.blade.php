@section('title')
	Mhealth Sport
@endsection

@section('content')
<div class="well admin">
	<div class="title-gray">
		{{ Lang::line('locale.admin_left_advertisement')->get($language) }}
	</div>
	<div class="admin-content">
		{{ Form::open('admin/set/left', 'POST') }}
		<label for="left_panel_show"> {{ Lang::line('locale.admin_left_advertisement_show')->get($language) }} </label>
		<input type="checkbox" name="left_panel_show" @if(isset($settings['left_panel_show']) && $settings['left_panel_show'] == 1) checked @endif >
		<br>
		<label for="left_panel_text">{{ Lang::line('locale.admin_left_advertisement_code')->get($language) }}</label>
		<textarea class="code" rows="" cols="" name="left_panel_text">@if(isset($settings['left_panel_text'])){{$settings['left_panel_text']}}@endif</textarea>
		<input type="submit" class="blue-button" value="{{ Lang::line('locale.save')->get($language) }}">
		{{ Form::close() }}
	</div>
</div>
<div class="well admin">
	<div class="title-gray">
		{{ Lang::line('locale.admin_right_advertisement')->get($language) }}
	</div>
	<div class="admin-content">
		{{ Form::open('admin/set/right', 'POST') }}
		<label for="right_panel_show"> {{ Lang::line('locale.admin_right_advertisement_show')->get($language) }} </label>
		<input type="checkbox" name="right_panel_show" @if(isset($settings['right_panel_show']) && $settings['right_panel_show'] == 1) checked @endif >
		<br>
		<label for="right_panel_text">{{ Lang::line('locale.admin_right_advertisement_code')->get($language) }}</label>
		<textarea class="code" rows="" cols="" name="right_panel_text">@if(isset($settings['right_panel_text'])){{$settings['right_panel_text']}}@endif</textarea>
		<input type="submit" class="blue-button" value="{{ Lang::line('locale.save')->get($language) }}">
		{{ Form::close() }}
	</div>
</div>
@endsection

@include('common.skeleton')