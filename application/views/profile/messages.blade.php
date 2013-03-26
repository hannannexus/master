@section('title')
	Mhealth Sport
@endsection

@section('errors')
<?php 
	$success = Session::get('success');
?>
	@if (isset($success))
	    @if($success == 'success')
	    	<div class="alert alert-success" style="margin: 0 auto; margin-top: 5px; margin-bottom: 5px; width: 640px; height: 15px; text-align: center;">
	    		{{ Lang::line('locale.message_sent')->get($language) }}
	    	</div>
	    @endif
	    @if($success == 'empty_message')
	    	<div class="alert alert-error" style="margin: 0 auto; margin-top: 5px; margin-bottom: 5px; width: 640px; height: 15px; text-align: center;">
	    		{{ Lang::line('locale.empty_message')->get($language) }}
	    	</div>
	    @endif
	    @if($success == 'error')
	    	<div class="alert alert-error" style="margin: 0 auto; margin-top: 5px; margin-bottom: 5px; width: 640px; height: 15px; text-align: center;">
	    		{{ Lang::line('locale.some_message_error')->get($language) }}
	    	</div>
	    @endif
    @endif
    
@endsection

@section('content')

<script type="text/javascript">
	$(function() {
		$("#msg").hide();
		$("#send_message").click(function(event) {
			event.preventDefault();
			$("#msg").show();
		});
	});
</script>

<div class="well" style="width: 640px; margin: 0 auto; margin-bottom: 5px;">
	{{ Form::open('profile/messages/send', 'POST', array('style' => 'display: inline;')) }}
	<div class="title-gray" style="width: auto; height: auto;">
    	{{ Lang::line('locale.messages_uppercase')->get($language) }}
    </div>
    <div style="margin: 0 auto; text-align: center;">
    	<a id="send_message" href="#" class="blue-button" style="margin-top: 5px;">
			{{ Lang::line('locale.new_message')->get($language) }}
		</a>
	    <a href="{{URL::home()}}profile/messages/inbox" class="blue-button" style="margin-top: 5px;">
		{{ Lang::line('locale.messages_inbox')->get($language) }}
			@if(isset($messages_count))
				({{ $messages_count['unread'] }})
			@endif
		</a>
		<a href="{{URL::home()}}profile/messages/outbox" class="blue-button" style="margin-top: 5px;">
			{{ Lang::line('locale.messages_outbox')->get($language) }}
		</a>
	</div>
	<div id="msg">
	    <div style="font-family: 'Century Gothic','Helvetica'; margin-top:10px;">
	    	{{Lang::line('locale.recipient')->get($language)}} 
	    </div>
	    <div class="styled-select" style="width: 200px; margin-top:5px; display: inline-block;">
	    	<select name="reciever" style="width: 225px;">
	    		@foreach($friends as $key => $friend)
	    			<option value="{{ $friend['user_id'] }}">{{ $friend['name'] }} {{ $friend['surname'] }}</option>
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