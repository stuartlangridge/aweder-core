<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Contract\Repositories\InventoryContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInventoryRequest;
use App\Inventory;
use Illuminate\Http\RedirectResponse;
use App\Contract\Service\InventoryContract as InventoryService;

class UpdateController extends Controller
{
    public function __invoke(
        UpdateInventoryRequest $request,
        InventoryContract $inventoryRepo,
        InventoryService $inventoryService,
        Inventory $inventoryItem
    ): RedirectResponse {
        if ($request->hasFile('image')) {
            if (!$inventoryService->uploadImageForInventory($request->file('image'), $inventoryItem)) {
                $request->session()->flash('error', 'There was a problem uploading your image');
            }
        }

        if ($inventoryRepo->updateInventoryItem($inventoryItem, $request->except('_token'))) {
            $request->session()->flash('success', 'Your inventory item has been updated');
        }

        return redirect()->to(route('admin.inventory'));
    }
}
