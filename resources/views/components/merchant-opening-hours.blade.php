<div class="merchant-banner__opening spacer-bottom--30">
    <span class="merchant-banner__opening-status">{{$storeOpenStatus}}</span>
    <div class="merchant-banner__opening-time">
        <input type="checkbox" id="opening-times" />
        @if (!$openingHours->isEmpty())
        <label for="opening-times">Opening times <span class="icon icon--down">@svg('arrow-down')</span>
            <span class="merchant-banner__list">
                @foreach($openingHours as $openingTime)
                    @if($openingTime->open_time->toTimeString() === '00:00:00' && $openingTime->close_time->toTimeString() === '00:00:00')
                        <span>{{$dayOfWeek[$openingTime->day_of_week]}}: <span class="opening-times__time">Closed</span></span>
                     @else
                    <span>{{$dayOfWeek[$openingTime->day_of_week]}}: <span class="opening-times__time">{{$openingTime->open_time->format('H:i')}} - {{$openingTime->close_time->format('H:i')}}</span></span>
                    @endif
                @endforeach
            </span>
        </label>
        @endif
    </div>
</div>
