@section('title')
	Mhealth Sport
@endsection

@section('content')

<script type="text/javascript">
	$(function() {
		$("#msg").hide();
		$("#send_message").click(function(event) {
			event.preventDefault();
			if($("#msg").css('display') == 'none') {
				$("#msg").show();
			}
			else {
				$("#msg").hide();
			}
		});
	});
</script>

<div class="well" style="width: 640px; margin: 0 auto; margin-bottom: 5px;">
	<div class="title-gray" style="width: auto; height: auto;">
    	{{ Lang::line('locale.messages_uppercase')->get($language) }}
    </div>
    <div class="white-block">
    	{{Lang::line('locale.to')->get($language)}} <b>{{$message['name']}} {{$message['surname']}}</b> {{Lang::line('locale.at')->get($language)}} {{$message['time']}}
    </div>
    <div class="white-block">
    	{{$message['text']}}
    </div>
    <a id="send_message" href="#" class="blue-button" style="margin-top: 5px;">
			{{ Lang::line('locale.reply')->get($language) }}
		</a>
    <div id="msg">
		{{ Form::open('profile/messages/send', 'POST', array('style' => 'display: inline;')) }}
	    <div style="font-family: 'Century Gothic','Helvetica'; margin-top:10px;">
	    	{{Lang::line('locale.recipient')->get($language)}} 
	    </div>
	    <div class="styled-select" style="width: 200px; margin-top:5px; display: inline-block;">
	    	<select name="reciever" style="width: 225px;">
	    		@foreach($friends as $key => $friend)
	    			@if($friend['user_id'] == $message['user_id'])
	    				<option value="{{ $friend['user_id'] }}" selected>{{ $friend['name'] }} {{ $friend['surname'] }}</option>
	    			@else
	    				<option value="{{ $friend['user_id'] }}">{{ $friend['name'] }} {{ $friend['surname'] }}</option>
	    			@endif
	    		@endforeach
	    	</select>
	    </div>
	    <div style="margin-top: 10px;">
	    	<textarea name="text" placeholder="{{Lang::line('locale.type_your_message')->get($language)}}" style="width: 620px; height: 100px;"></textarea>
	    </div>
	    {{ Form::submit(Lang::line('locale.button_send_message')->get($language), array('class' => 'blue-button')) }}
	    {{ Form::close() }}
    </div>
</div>
@endsection

@include('common.skeleton')