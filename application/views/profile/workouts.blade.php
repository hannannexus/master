@section('title')
	Mhealth Sport
@endsection

@section('content')
<table border="1" cellspacing="0">
	<tr>
		<td>
		Workout number
		</td>
		<td>
		From
		</td>
		<td>
		To
		</td>
	</tr>
	@foreach ($workouts as $workout)
		<tr>
			<td>
			{{ $workout['workout_number'] }}
			</td>
			<td>
			{{ $workout['min'] }}
			</td>
			<td>
			{{ $workout['max'] }}
			</td>
			<td>
			{{ Form::open('workout/' . Auth::user()->user_id . '/' . $workout['workout_number'], 'GET') }}
			{{ Form::submit(Lang::line('locale.view_workout')->get($language), array('class' => 'blue-button')) }}
			{{ Form::close() }}
			</td>
		</tr>
	@endforeach
</table>
@endsection

@include('common.skeleton')