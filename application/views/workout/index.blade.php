@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	{{ HTML::script('http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBcvvPkZZG00mSIrEMyrc4mc5w38c0xooY&sensor=true') }}
	{{ HTML::script('js/jquery-ui-1.9.1.custom.min.js') }}
	{{ HTML::script('js/infobox.js') }}
@endsection

@section('content')

<div class="white-block">
	<div id="map_canvas" style="height: 800px; width: 100%; margin: 0 auto; position: relative;" ></div>
</div>

@endsection

@include('common.skeleton')