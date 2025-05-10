<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\User\BrandStoreRequest;
use App\Services\Brand\IndexBrandService;
use App\Services\Brand\StoreBrandService;

class BrandController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $data = (new IndexBrandService())->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    
    
    }

    public function storeBrand(BrandStoreRequest $request)
    {
        try{
            $data = (new StoreBrandService($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }
}
