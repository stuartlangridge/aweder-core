<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Contract\Repositories\InventoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class DeleteController
 * @package App\Http\Controllers\Admin\Inventory
 */
class DeleteController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param InventoryContract $inventoryRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, int $id, InventoryContract $inventoryRepository): RedirectResponse
    {
        $inventoryRepository->deleteInventoryItemById($id);

        return redirect()->to(route('admin.inventory'));
    }
}
