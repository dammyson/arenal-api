<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\Brand\StoreBrandService;
use App\Services\Prize\StorePrizeService;
use App\Services\Prize\GetBrandPrizeService;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\PrizeStoreRequest;

class PrizeController extends BaseController
{
    //

    public function storePrize(PrizeStoreRequest $request)
    {
        try {
            $data = (new StorePrizeService($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    public function getBrandPrizes(Brand $brand)
    {
        try {
            // dd($brand);
            
            $data = (new GetBrandPrizeService($brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }
}
