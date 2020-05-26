<?php

namespace App\Http\Controllers\Admin\Details;

use App\Contract\Repositories\MerchantContract;
use App\Contract\Service\MediaServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BusinessDetailsEditRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class PostEditDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param BusinessDetailsEditRequest $request
     * @param AuthManager $auth
     * @param MerchantContract $merchantRepo
     * @param MediaServiceContract $mediaService
     * @return RedirectResponse
     */
    public function __invoke(
        BusinessDetailsEditRequest $request,
        AuthManager $auth,
        MerchantContract $merchantRepo,
        MediaServiceContract $mediaService
    ): RedirectResponse {
        $merchant = $auth->user()->merchants->first();

        $merchantDetails = $request->except('_token');

        $merchantDetails['registration_stage'] = 0;

        if ($merchantRepo->updateMerchant($merchant, $merchantDetails)) {
            if ($request->hasFile('logo')) {
                $uploadedFileWithPath = $mediaService->uploadMediaItemToCloud($request->file('logo'), 'merchants');
                if ($uploadedFileWithPath) {
                    if ($merchant->logo !== null) {
                        $mediaService->removeMediaItemFromCloud($merchant->logo);
                    }

                    $merchant->update(['logo' => $uploadedFileWithPath]);
                }
            }

            $request->session()->flash('success', 'Your business details have been updated');

            return redirect()->route('admin.dashboard');
        }

        $request->session()->flash('error', 'There was an issue updating your details');

        return redirect()->back();
    }
}
