<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\DemographyRequest;
use App\Services\Brand\IndexBrandService;
use App\Services\Brand\StoreBrandService;
use App\Services\Brand\DeleteBrandService;
use App\Services\Brand\UpdateBrandService;
use App\Http\Requests\Brand\GetBrandRequest;
use App\Http\Requests\User\StoreBrandBadges;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\BrandUpdateRequest;
use App\Services\Brand\AddBranchToBrandService;
use App\Services\Brand\StoreBrandBadgesService;
use App\Http\Requests\User\AddBranchToBrandRequest;
use App\Services\Point\GetArenaAudienceDemoService;
use App\Services\Point\GetAudienceBrandPointService;
use App\Services\Point\StoreArenaAudienceDemoService;
use App\Services\Point\GetArenaAudienceProfileService;

class BrandController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $data = (new IndexBrandService($request))->run();
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

    public function getProfile(Request $request, Brand $brand) {
   
        try {

            $brandLive = (new GetAudienceBrandPointService($request, $brand->id))->run();

            return $this->sendResponse($brandLive, "audience points", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }

    public function arenaProfile(Request $request, Brand $brand) {
   
        try {

            $brandLive = (new GetArenaAudienceProfileService($request))->run();

            return $this->sendResponse($brandLive, "audience points", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }

    public function storeAudienceDemo(DemographyRequest $request) {
   
        try {

            $demo = (new StoreArenaAudienceDemoService($request))->run();

            return $this->sendResponse($demo, "audience points", 201);
        } catch (\Exception $e) {

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }

    public function getAudienceDemo(Request $request) {
   
        try {

            $demo = (new GetArenaAudienceDemoService())->run();

            return $this->sendResponse($demo, "audience points", 201);
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
            // dd($url);
            // "http://127.0.0.1:8000/api/audiences/world-game?data=OWY3YjdmY2ItYTRmMy00MWExLTg4NTktMzM0MDNmZTU0Y2Q1fDlmMWViNjQ3LWU5NjEtNGYyYS04OTU2LTc2MmY1OWM3OGJmYw%3D%3D&expires=1756377530&signature=82bda65adf2506c2c14204444fdb2a7fba7fbb99176cdb27adddd305a4a8cae6"
            $urlComponents = parse_url($url);
            // dd($urlComponents);
            // data=OWY3YjdmY2ItYTRmMy00MWExLTg4NTktMzM0MDNmZTU0Y2Q1fDlmMWViNjQ3LWU5NjEtNGYyYS04OTU2LTc2MmY1OWM3OGJmYw%3D%3D&expires=1756377616&signature=e43ec6fa2534f8299bc42583c35952096dfe30a8241b3cb5ef53e6307ba3b25a
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
            $data = Brand::with('details', 'branches')->find($brand_id);
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Brand retrieved succcessfully");
    }

    public function addBranchToBrand(AddBranchToBrandRequest $request, Brand $brand) {
        try {

            $data = (new AddBranchToBrandService($request, $brand->id))->run();

            return $this->sendResponse($data, "branches added to brand succcessfully");
        }

         catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
    }
}
