<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;

class StoreBrandService implements BaseServiceInterface{
    protected $request;

    public function __construct(BrandStoreRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        $user = $this->request->user();
           
        if ($user->is_audience) {
         return response()->json([
             'error' => true, 
             'message' => "unauthorized"
         ], 401);

        }

        return Brand::create([
            ...$this->request->validated(),
            'created_by' => $user->id
        ]);

    }
}