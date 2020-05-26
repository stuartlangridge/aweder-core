@extends('global.admin')
@section('content')
    <header class="dashboard__header col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="dashboard__title col col--lg-12-8 col--sm-6-6">
            <h1 class="header header--three color--carnation">Order display categories</h1>
        </div>
        <div class="dashboard__intro col col--lg-12-5 col--sm-6-5">
            <p>Edit your order categories here.</p>
        </div>
    </header>
    <section class="categories col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <form
            class="col col--lg-12-6 col--lg-offset-12-3 col--m-12-8 col--m-offset-12-2 col--sm-6-6 col--sm-offset-6-1 form form--background"
            id="categoryForm"
            name="categoryForm"
            autocomplete="off"
            action="{{ route('admin.categories.post') }}"
            method="POST">
            @csrf
            <?php $i = 1; ?>
            @foreach ($categories as $category)
                <div class="field">
                    <label for="category_{{$category->id}}">Category {{ $i }}</label>
                    <input type="text"
                           name="categories[{{$category->id}}]"
                           id="category_{{$category->id}}"
                           placeholder="{{ $defaultCategories[$i] }}"
                           value="{{ old('categories.' . $category->id, $category->title) }}"/>
                </div>
                <?php $i++; ?>
            @endforeach
            @error('categories')
                <p class="form__general-error">{{ $message }}</p>
            @enderror
            <div class="field field--button">
                <button type="submit" class="button button--icon-right button--filled button--filled-carnation button--end">
                    <span class="button__content">Save</span>
                    <span class="icon icon-right">@svg('arrow-right')</span>
                </button>
            </div>
        </form>
    </section>
@endsection
