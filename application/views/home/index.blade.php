@section('title')
	Mhealth Sport
@endsection

@section('content')
@if(isset($settings))
<div class="white-block" style="margin: 0 auto; text-align: center;">
	<div class="well" id="left_panel" style="display:inline-block; height: auto; padding-top: 5px; vertical-align: top;">
		{{ $settings['left_panel_text'] }}
	</div>
	<div class="well" id="left_panel" style="width: 600px; display:inline-block; vertical-align: top;">
		<div class="title-gray">
			{{Lang::line('locale.feed')->get($language)}}
		</div>
	</div>
	<div class="well" id="left_panel" style="display:inline-block; height: auto; padding-top: 5px; vertical-align: top;">
		{{ $settings['right_panel_text'] }}
	</div>
</div>
@endif
@endsection

@include('common.skeleton')