@extends('global.app')
@section('content')
    <section class="intro intro--opening-hours">
        <div class="row">
            <div class="content">
                <header class="intro__header col col--lg-12-8 col--lg-offset-12-2 col--sm-6-6 col--s-6-6 spacer-bottom--30">
                    <h1 class="header header--three color--carnation spacer-bottom--30">Opening Hours</h1>
                </header>
                <div class="intro__copy col col--lg-12-8 col--lg-offset-12-2 col--sm-6-6 col--s-6-6">
                    <p>Please select the times which you are open.</p>
                    <p>These can be modified at any time from your Merchant Dashboard after setup is complete.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="opening-hours">
        <div class="row">
            <div class="content">
                <form
                    class="col col--lg-12-12 col--l-12-12 col--sm-6-6 form form--opening"
                    id="openingHoursForm"
                    name="openingHoursForm"
                    autocomplete="off"
                    action="{{ route('registration.opening-hours.post') }}"
                    method="POST">
                    @csrf
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Monday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="monday-opening-hour" name="monday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('monday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="monday-opening-minute" name="monday[opening][minute]">
                                    <option value="00" @if (old('monday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('monday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('monday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('monday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="monday-closing-hour" name="monday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('monday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="monday-closing-minute" name="monday[closing][minute]">
                                    <option value="00" @if (old('monday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('monday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('monday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('monday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Tuesday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="tuesday-opening-hour" name="tuesday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('tuesday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="tuesday-opening-minute" name="tuesday[opening][minute]">
                                    <option value="00" @if (old('tuesday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('tuesday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('tuesday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('tuesday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="tuesday-closing-hour" name="tuesday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('tuesday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="tuesday-closing-minute" name="tuesday[closing][minute]">
                                    <option value="00" @if (old('tuesday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('tuesday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('tuesday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('tuesday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Wednesday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="wednesday-opening-hour" name="wednesday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('wednesday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="wednesday-opening-minute" name="wednesday[opening][minute]">
                                    <option value="00" @if (old('wednesday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('wednesday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('wednesday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('wednesday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="wednesday-closing-hour" name="wednesday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('wednesday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="wednesday-closing-minute" name="wednesday[closing][minute]">
                                    <option value="00" @if (old('wednesday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('wednesday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('wednesday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('wednesday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Thursday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="thursday-opening-hour" name="thursday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('thursday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="thursday-opening-minute" name="thursday[opening][minute]">
                                    <option value="00" @if (old('thursday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('thursday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('thursday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('thursday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="thursday-closing-hour" name="thursday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('thursday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="thursday-closing-minute" name="thursday[closing][minute]">
                                    <option value="00" @if (old('thursday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('thursday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('thursday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('thursday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Friday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="friday-opening-hour" name="friday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('friday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="friday-opening-minute" name="friday[opening][minute]">
                                    <option value="00" @if (old('friday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('friday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('friday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('friday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="friday-closing-hour" name="friday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('friday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="friday-closing-minute" name="friday[closing][minute]">
                                    <option value="00" @if (old('friday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('friday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('friday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('friday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Saturday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="saturday-opening-hour" name="saturday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('saturday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="saturday-opening-minute" name="saturday[opening][minute]">
                                    <option value="00" @if (old('saturday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('saturday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('saturday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('saturday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="saturday-closing-hour" name="saturday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('saturday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="monday-closing-minute" name="saturday[closing][minute]">
                                    <option value="00" @if (old('saturday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('saturday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('saturday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('saturday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="opening-hours__day col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <h2 class="header color--carnation">Sunday</h2>
                        <div class="opening-hours__time opening-hours__time--open">
                            <span class="label">Opening:</span>
                            <div class="field field--select">
                                <select class="select" id="sunday-opening-hour" name="sunday[opening][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('sunday.opening.hour', 9) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="sunday-opening-minute" name="sunday[opening][minute]">
                                    <option value="00" @if (old('sunday.opening.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('sunday.opening.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('sunday.opening.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('sunday.opening.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                        <div class="opening-hours__time opening-hours__time--close">
                            <span class="label">Closing:</span>
                            <div class="field field--select">
                                <select class="select" id="sunday-closing-hour" name="sunday[closing][hour]">
                                    @for ($i = 0; $i <= 23; $i++)
                                        <option value="{{ $i }}"
                                                @if (old('sunday.closing.hour', 17) === $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <span>:</span>
                            <div class="field field--select">
                                <select class="select" id="monday-closing-minute" name="sunday[closing][minute]">
                                    <option value="00" @if (old('sunday.closing.minute') === 00) selected @endif>00</option>
                                    <option value="15" @if (old('sunday.closing.minute') === 15) selected @endif>15</option>
                                    <option value="30" @if (old('sunday.closing.minute') === 30) selected @endif>30</option>
                                    <option value="45" @if (old('sunday.closing.minute') === 45) selected @endif>45</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field field--button col col--lg-12-3 col--m-12-4 col--sm-6-3 col--s-6-6">
                        <button type="submit" class="button button--icon-right button--filled button--filled-carnation">
                            <span class="button__content">Next</span>
                            <span class="icon icon-right">@svg('arrow-right')</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
