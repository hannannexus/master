@section('title')
  Mhealth Sport
@endsection

@section('meta-custom')
<script type="text/javascript">
  home = '{{ URL::home() }}';
</script>
{{ HTML::script('js/home-feed.js') }}
@endsection

@section('content')
@if(isset($settings) && !empty($settings))
<div class="white-block centered">
  <div class="well inlined" id="left_panel" style="height: auto; padding-top: 5px; vertical-align: top;">
    {{ $settings['left_panel_text'] }}
  </div>
  <div class="well inlined" id="feed-container" style="width: 600px; vertical-align: top;">
    <div class="title-gray" id="feed-title">
      {{Lang::line('locale.feed')->get($language)}}
    </div>
    @if(is_null($feed))
      <div class="white-block">
        {{ Lang::line('locale.no_feeds')->get($language) }}
      </div>
    @else
      @foreach ($feed as $workout)
        <div class="white-block" style="padding-bottom: 10px; margin-bottom: 7px;">
          <table>
            <tr>
              <td>
                <a href="{{ $workout['user'] }}">
                  <b><p style="font-size: 12px; margin-bottom: 2px;">{{ $workout['name'] }} {{ $workout['surname'] }}</p></b>
                  <img class="third-sized middle-shadowed" src="{{ $workout['photo'] }}">
                </a>
              </td>
              <td>
                <a class="undecorated" href="{{ $workout['link'] }}">
                  <div class="main-feed-element">
                    <p class="null-idented">{{ Lang::line('locale.date_doubledot')->get($language) }} <b>{{ $workout['date'] }}</b></p>
                    <p class="null-idented">{{ Lang::line('locale.distance_doubledot')->get($language) }} <b>{{ $workout['distance'] }} {{ Lang::line('locale.km')->get($language) }}</b></p>
                    <p class="null-idented">{{ Lang::line('locale.duration')->get($language) }} <b>{{ $workout['time'] }}</b></p>
                  </div>
                </a>
              </td>
              <td>
                <a class="undecorated" href="{{ $workout['link'] }}">
                  <div class="inlined" style="margin: 10px;">
                    <img class="half-sized" src="{{ $workout['sport'] }}">
                  </div>
                </a>
              </td>
            </tr>
          </table>
        </div>
      @endforeach
    @endif
  </div>
  <div class="well" id="left_panel" style="display:inline-block; height: auto; padding-top: 5px; vertical-align: top;">
    {{ $settings['right_panel_text'] }}
  </div>
</div>
@endif
@endsection

@include('common.skeleton')