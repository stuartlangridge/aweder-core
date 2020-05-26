<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Contract\Repositories\CategoryContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use Illuminate\Http\RedirectResponse;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param CategoryCreateRequest $request
     * @param CategoryContract $categoryRepo
     * @return RedirectResponse
     */
    public function __invoke(CategoryCreateRequest $request, CategoryContract $categoryRepo): RedirectResponse
    {
        $categories = $request->get('categories');

        $merchant = $request->user()->merchants;

        if ($categoryRepo->updateCategories($categories, $merchant->first()->id)) {
            $request->session()->flash('success', 'Your categories have been updated');

            return redirect()->route('admin.dashboard');
        }

        $request->session()->flash('error', 'There was an error setting your categories');

        return redirect()->back();
    }
}
