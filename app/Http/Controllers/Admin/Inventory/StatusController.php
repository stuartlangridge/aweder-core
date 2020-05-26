<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Contract\Repositories\InventoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class StatusController
 * @package App\Http\Controllers\Admin\Inventory
 */
class StatusController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @param \App\Contract\Repositories\InventoryContract $inventoryRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, int $id, InventoryContract $inventoryRepository): RedirectResponse
    {
        $inventoryRepository->toggleInventoryItemStatusById($id);

        return redirect()->to(route('admin.inventory'));
    }
}
