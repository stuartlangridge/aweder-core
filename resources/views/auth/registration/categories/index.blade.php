@extends('global.app')
@section('content')
    <section class="intro">
        <div class="row">
            <div class="content">
                <header class="intro__header col col--lg-12-8 col--lg-offset-12-2 col--sm-6-6 col--s-6-6 spacer-bottom--30">
                    <h1 class="header header--three color--carnation spacer-bottom--30">Order display categories</h1>
                </header>
                <div class="intro__copy col col--lg-12-8 col--lg-offset-12-2 col--sm-6-6 col--s-6-6">
                    <p>If you would like your customers to see items grouped under headings, please specify up to 4 categories below. Any category left blank will not be displayed.</p>
                    <p>These can be modified at any time from your Merchant Dashboard after setup is complete.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="categories">
        <div class="row">
            <div class="content">
                <form
                    class="col col--lg-12-6 col--lg-offset-12-4 col--l-12-6 col--l-offset-12-4 col--m-12-11 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6"
                    id="categoryForm"
                    name="categoryForm"
                    autocomplete="off"
                    action="{{ route('registration.categories.post') }}"
                    method="POST">
                    @csrf
                    <?php $i = 1; ?>
                    @if (!$categories->isEmpty())
                        @foreach ($categories as $category)
                            <div class="field">
                                <label for="category_{{ $i }}">Category {{ $i }}</label>
                                <input type="text"
                                       name="existing_categories[{{ $category->id }}]"
                                       id="category_{{ $i }}"
                                       placeholder="{{ $defaultCategories[$i] }}"
                                       value="{{ old('existing_categories.' . $category->id, $category->title) }}"/>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @endif
                    @while($i < 5)
                        <div class="field">
                            <label for="category_{{ $i }}">Category {{ $i }}</label>
                            <input type="text"
                                   name="categories[]"
                                   id="category_{{ $i }}"
                                   placeholder="{{ $defaultCategories[$i] }}"
                                   value="{{ old('categories.' . $i) }}"/>
                        </div>
                        @php
                            $i++;
                        @endphp
                    @endwhile
                    @error('categories')
                    <p class="form__general-error">{{ $message }}</p>
                    @enderror
                    <div class="field field--button">
                        <button type="submit" class="button button--icon-right button--filled button--filled-carnation button--end">
                            <span class="button__content">Next</span>
                            <span class="icon icon-right">@svg('arrow-right')</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
