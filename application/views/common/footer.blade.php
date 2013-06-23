@section('footer')

<div class="header-block" style="height: 32px;">
	<a href="{{ URL::home() }}manual" class="grey-button" style="margin-top: 0px; ">
		{{ Lang::line('locale.manual')->get($language) }}
	</a>
</div>

@endsection