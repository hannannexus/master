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
	    @if($success == 'reciever_not_found')
	    	<div class="alert alert-error" style="margin: 0 auto; margin-top: 5px; margin-bottom: 5px; width: 640px; height: 15px; text-align: center;">
	    		{{ Lang::line('locale.reciever_not_found')->get($language) }}
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
		$('#end').hide();
		pack = 1;
		ended = false;
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
		function getPack() {
			$('#end').show();
			$.post (
				'{{URL::home()}}profile/outmessages',
				{
					pack : pack
				},
				function (result) {
					if(result.length != 0) {
						for(i = 0; i < result.length; i++) {
							if(result[i].status == 'unread') {
								var home = '{{URL::home()}}';
								var inside = '<div id="message'+result[i].id_message+'" style="border:1px solid #CEECF5; background: #FFFFFF; width: 630px; font-family: \'Century Gothic\', \'Helvetica\'; margin-top: 3px; border-radius: 3px; font-size: 10pt;">';
				    			inside += '&nbsp;&nbsp;<b><a href="'+home+'profile/messages/outbox/'+result[i].id_message+'" id="'+result[i].id_message+'">';
				    			inside += " {{Lang::line('locale.from')->get($language) }}" + result[i].name + ' ' + result[i].surname + ' (' + result[i].time + ') &#8211' + result[i].short;
				    			inside += '</a></b>';
				    			inside += '</div>';
				    			$("#end").after(inside);
				    			$("#end").remove();
				    			$("#message"+result[i].id_message).after('<div id="end"></div>');
							}
							else {
								var home = '{{URL::home()}}';
								var inside = '<div id="message'+result[i].id_message+'" style="border:1px solid #CEECF5; background: #FFFFFF; width: 630px; font-family: \'Century Gothic\', \'Helvetica\'; margin-top: 3px; border-radius: 3px; font-size: 10pt;">';
				    			inside += '&nbsp;&nbsp'; 
				    			inside += '<a href="'+home+'profile/messages/'+result[i].id_message+'" id="'+result[i].id_message+'">';
				    			inside += " {{Lang::line('locale.from')->get($language) }}" + result[i].name + ' ' + result[i].surname + ' (' + result[i].time + ') &#8211' + result[i].short;
				    			inside += '</a>';
				    			inside += '</div>';
				    			$("#end").after(inside);
				    			$("#end").remove();
				    			$("#message"+result[i].id_message).after('<div id="end" class="end"></div>');
							}
						}
					}
					else {
						pack--;
						ended = true;
						$("#end").hide();
					}
				},
				'json'
			);
			pack++;
		};

		$(window).scroll(function(){
	        if  ($(window).scrollTop() == $(document).height() - $(window).height()){
	          if(!ended) getPack();
	        }
		}); 
		
	});
</script>

<div class="well" id="container" style="width: 640px; margin: 0 auto; margin-bottom: 5px;">
	<div class="title-gray" style="width: auto; height: auto;">
    	{{ Lang::line('locale.messages_uppercase')->get($language) }}
    </div>
    <div style="margin: 0 auto; text-align: center;">
    	<a id="send_message" href="#" class="blue-button" style="margin-top: 5px;">
			{{ Lang::line('locale.new_message')->get($language) }}
		</a>
		<a href="{{URL::home()}}profile/messages" class="blue-button" style="margin-top: 5px;">
			{{ Lang::line('locale.messages_inbox')->get($language) }}
		</a>
	</div>
	<div id="msg">
		{{ Form::open('profile/messages/send', 'POST', array('style' => 'display: inline;')) }}
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
    {{ Form::open('profile/messages/reply', 'POST', array('style' => 'display: inline;')) }}
    <div style="margin-top: 5px;">
    @if(!empty($messages))
    	@foreach($messages as $key => $message)
    		<div id="message{{$message['id_message']}}" style="border:1px solid #CEECF5; background: #FFFFFF; width: 630px; font-family: 'Century Gothic', 'Helvetica'; margin-top: 3px; border-radius: 3px; font-size: 10pt;">
    			&nbsp;&nbsp; 
    			@if($message['status'] == 'unread')
    				<b>
    			@endif
    			<a href="{{URL::home()}}profile/messages/outbox/{{$message['id_message']}}" id="{{$message['id_message']}}">
    				{{Lang::line('locale.to')->get($language) }}{{$message['name'] . ' ' . $message['surname']}} ({{ $message['time'] }}) &#8211 {{ $message['short'] }}
    			</a>
    			@if($message['status'] == 'unread')
    				</b>
    			@endif
    		</div>
    	@endforeach
    @endif
    	<div id="end" class="end"></div>
    </div>
	{{ Form::close() }}
</div>
@endsection

@include('common.skeleton')