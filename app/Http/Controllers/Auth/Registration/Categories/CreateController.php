<?php

namespace App\Http\Controllers\Auth\Registration\Categories;

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
        $categories = $request->get('categories', []);

        $existingCategories = $request->get('existing_categories');

        $merchant = $request->user()->merchants->first();

        if (!empty($categories)) {
            if ($categoryRepo->createCategories($categories, $merchant->id)) {
                $request->session()->flash('success', 'Your categories have been created');

                $request->session()->put('signup_route', true);

                return redirect()->route('admin.inventory');
            }
        }

        if (!empty($existingCategories)) {
            $categoryRepo->updateCategories($existingCategories, $merchant->id);

            $request->session()->flash('success', 'Your categories have been created');

            return redirect()->route('admin.inventory');
        }

        $request->session()->flash('error', 'There was an error setting your categories');

        return redirect()->back();
    }
}
