@extends('global.admin')
@section('content')
    <header class="dashboard__header col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="dashboard__title col col--lg-12-6 col--sm-6-6">
            <h1 class="header header--three color--carnation">{{ $merchant->name }}</h1>
        </div>
        <div class="dashboard__intro col col--lg-12-5 col--sm-6-5">
            @if(isset($signUpRoute) && $signUpRoute === true)
                <p>Create your menu / order form below by adding each inventory item and clicking ‘add’ (the plus symbol) below. These can be amended at any point from your dashboard.</p>
            @else
                <p>Edit your menu / order form below:</p>
            @endif
        </div>
    </header>
    <section class="inventory col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        @foreach ($fullInventory as $category)
            @if ($category->title !== null && !empty($category->title))
            <div class="inventory__item col col--lg-12-10 col--sm-6-6">
                <header class="inventory__header">
                    <h3 class="header header--five">{{ $category->title }}</h3>
                </header>
                <div class="inventory__listing">
                    <table>
                        <thead>
                            <tr>
                                <th>Item Image</th>
                                <th>Item Title</th>
                                <th>Item Description</th>
                                <th>Price (&pound;)</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form
                                id="inventoryForm"
                                name="inventoryForm"
                                autocomplete="off"
                                action="{{ route('admin.inventory.post') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="category-id" value="{{ $category->id }}">
                                <tr>
                                    <td>
                                        <div class="choose_file">
                                            <button type="button" class="button">
                                                        <span class="field--upload icon">
                                                            @svg('upload')
                                                        </span>
                                                <input type="file" name="image" id="image" accept="image/*"/>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field @error('title') input-error @enderror">
                                            <input type="text" name="title" id="title" placeholder="Item title" />
                                            @error('title')
                                            <p class="form__validation-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field @error('description') input-error @enderror">
                                            <textarea name="description" id="description" placeholder="Item description" rows="1"></textarea>
                                            @error('description')
                                            <p class="form__validation-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field @error('amount') input-error @enderror">
                                            <input type="text" name="amount" id="amount" placeholder="Item price (&pound;)"/>
                                            @error('amount')
                                            <p class="form__validation-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <div class="actions__wrapper">
                                            <button type="submit" class="button button--icon button--add" title="Add item">
                                                <span class="icon icon--add">@svg('add')</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                            @foreach ($category->inventories as $item)
                                <form
                                    id="updateInventoryForm"
                                    name="updateInventoryForm"
                                    autocomplete="off"
                                    action="{{ route('admin.inventory.update', $item->id) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="category-id" value="{{ $category->id }}">
                                    <tr class="inventory__current">
                                        <td>
                                            @if(!$item->image)
                                                <div class="choose_file">
                                                    <button type="button" class="button">
                                                            <span class="field--upload icon">
                                                                @svg('upload')
                                                            </span>
                                                        <input type="file" name="image" id="image" accept="image/*"/>
                                                    </button>
                                                </div>
                                            @else
                                                <img src="{{ $item->getTemporaryInventoryImageLink() }}"
                                            @endif
                                        </td>
                                        <td class="inventory__title">
                                            <input type="text" name="title" id="title" value="{{ $item->title }}" />
                                            @error('title')
                                            <p class="form__validation-error">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="inventory__desc">
                                            <input type="text" name="description" id="description" value="{{ $item->description }}" />
                                            @error('description')
                                            <p class="form__validation-error">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="inventory__price">
                                            £<input type="text" name="price" id="price" value="{{ $item->getFormattedUKPriceAttribute($item->price) }}" />
                                            @error('price')
                                            <p class="form__validation-error">{{ $message }}</p>
                                            @enderror
                                            </td>
                                        <td class="actions">
                                            <div class="actions__wrapper">
                                                <a href="{{ route('admin.inventory.status', ['id' => $item->id]) }}" class="button button--icon button--visibility" title="Show or hide item from customers’">
                                                    @if ($item->available)
                                                    <span class="icon icon--visibility">
                                                        @svg('visible')
                                                    </span>
                                                    @else
                                                    <span class="icon icon--visibility icon--visibility-hidden">
                                                        @svg('hide-visibility')
                                                    </span>
                                                    @endif
                                                </a>
                                                <a href="{{ route('admin.inventory.delete', ['id' => $item->id]) }}" class="button button--icon button--outline button--remove" title="Remove item">
                                                   <span class="icon icon--remove">
                                                       @svg('remove')
                                                   </span>
                                                </a>
                                                <button type="submit" class="button button--icon button--add" title="Save item">
                                                    <span class="icon icon--add">@svg('tick')</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @endforeach
        <div class="inventory__process col col--lg-12-10 col--sm-6-6">
            <a href="{{ route('admin.dashboard') }}" class="button button--icon-right button--filled button--filled-carnation button--end">
                <span class="button__content">Continue to Dashboard</span>
                <span class="icon icon-right">@svg('arrow-right')</span>
            </a>
        </div>
    </section>


@endsection
