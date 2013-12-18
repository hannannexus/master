<div class="title-gray" id="feed-title">
  {{Lang::line('locale.feed')->get($language)}}
</div>
@foreach ($feed as $workout)
  <div class="white-block" style="padding-bottom: 10px; margin-bottom: 7px;">
    <table class="centered">
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