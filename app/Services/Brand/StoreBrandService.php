<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;
use App\Models\BrandDetail;

class StoreBrandService implements BaseServiceInterface{
    protected $request;

    public function __construct(BrandStoreRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        $user = $this->request->user();
        $brandDetails = $this->request['brand_details'];

        $brand = Brand::create([
            ...$this->request->validated(),
            'created_by' => $user->id
        ]);

        if (!empty($brandDetails)) {
            foreach($brandDetails as $brandDetail) {
                BrandDetail::create([
                    "brand_id" => $brand->id,
                    "detail" => $brandDetail['brand_detail'],
                    "user_id" => $this->request->user()->id
                ]);
            }
        }

        return $brand;

    }
}