@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	{{ HTML::script('http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBcvvPkZZG00mSIrEMyrc4mc5w38c0xooY&sensor=true') }}
@endsection

@section('content')

<div class="white-block">
	<div id="map_canvas" style="height: 800px; width: 1200px; margin: 0 auto; position: relative;" ></div>
</div>

@endsection

@include('common.skeleton')