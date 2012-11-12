@section('title')
	Mhealth Sport
@endsection

@section('meta-custom')
	{{ HTML::script('http://maps.googleapis.com/maps/api/js?key=AIzaSyBcvvPkZZG00mSIrEMyrc4mc5w38c0xooY&sensor=true') }}
@endsection

@section('content')

<div class="white-block">
	<div id="map_canvas" style="width:70%; height:70%; margin:0 auto; top:10%;"></div>
</div>
@endsection

@include('common.skeleton')