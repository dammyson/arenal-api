<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\GetBrandRequest;
use App\Models\Badge;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Brand\IndexBrandService;
use App\Services\Brand\StoreBrandService;
use App\Services\Brand\DeleteBrandService;
use App\Services\Brand\UpdateBrandService;
use App\Http\Requests\User\StoreBrandBadges;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\BrandUpdateRequest;
use App\Services\Brand\StoreBrandBadgesService;
use App\Services\Point\GetAudienceBrandPointService;
use Illuminate\Support\Facades\URL;

class BrandController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $data = (new IndexBrandService())->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    public function storeBrand(BrandStoreRequest $request)
    {
        try {
            $data = (new StoreBrandService($request))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }



    public function updateBrand(BrandUpdateRequest $request, $id)
    {
        try {
            $data = (new UpdateBrandService($request, $id))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Brand updated succcessfully");
    }

    public function getPoints(Request $request, Brand $brand)
    {
        try {

            $brandLive = (new GetAudienceBrandPointService($request, $brand->id))->run();

            return $this->sendResponse($brandLive, "audience points", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }





    public function userPrize(Request $request, Brand $brand)
    {
        try {

            $brandLive = (new GetAudienceBrandPointService($request, $brand->id))->run();

            return $this->sendResponse($brandLive, "live joined", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }


    public function storeBrandBadges(StoreBrandBadges $request)
    {
        try {

            $brandLive = (new StoreBrandBadgesService($request))->run();

            return $this->sendResponse($brandLive, "brand badges created successfully", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }


    public function deleteBrandBadges(Badge $badge)
    {
        try {

            $badge->delete();
            return $this->sendResponse("badge deleted successfully", "live joined", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }
    public function deleteBrand(Request $request, $id)
    {
        try {
            Gate::authorize('is-user');
            $data = (new DeleteBrandService($request, $id))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Brand delete succcessfully");
    }


    public function generateCampaignLink($brandId)
    {
        try {
            $brand = Brand::find($brandId);
            $expired = now()->addHour(24);
            $user = auth()->user();

            $payload = "{$brandId}|{$user->id}";
            $encoded = base64_encode($payload);

            $url =  URL::temporarySignedRoute('world.game',  $expired, ['data' => $encoded]);
            $urlComponents = parse_url($url);
            $front_url = env('FRONT_END_URL', 24) . '?' . $urlComponents['query'];

            return $this->sendResponse($front_url, "World link generated successfully", 200);
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($front_url, "World updated succcessfully", 200);
    }


    public function showBrand(GetBrandRequest $request)
    {
        try {

            $validated = $request->validated();
            if ($validated['is_link']) {
                if (!$request->hasValidSignature()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid/Expired link, contact admin'
                    ], 401);
                }
            }

            $brand_id = $validated['brand_id'];
            $data = Brand::find($brand_id);
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Brand retrieved succcessfully");
    }
}
