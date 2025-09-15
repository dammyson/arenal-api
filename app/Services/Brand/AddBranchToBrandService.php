<?php

namespace App\Services\Brand;

use Exception;
use App\Models\Brand;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\BrandUpdateRequest;
use App\Models\Branch;
use Illuminate\Auth\Access\AuthorizationException;

class AddBranchToBrandService implements BaseServiceInterface{
    protected $request;
    protected $brandId;

    public function __construct($request, $brandId)
    {
        $this->brandId = $brandId;
        $this->request = $request;
    }

    public function run() {
        $branches = $this->request->validated()["branches"];

        $data = [];

        foreach ($branches as $branch) {
           $created = Branch::create([
                "name" => $branch["branch_name"],
                "brand_id" => $this->brandId
            ]);
            $data[] = $created;
        }

        return $data;

    }
}