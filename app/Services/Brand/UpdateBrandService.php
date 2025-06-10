<?php

namespace App\Services\Brand;

use Exception;
use App\Models\Brand;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\BrandUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateBrandService implements BaseServiceInterface{
    protected $request;
    protected $id;

    public function __construct(BrandUpdateRequest $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function run() {
        $user = $this->request->user();

        $brand = Brand::findOrFail($this->id);

         if ($brand->created_by !== $user->id) {
            throw new AuthorizationException("You are not permitted to edit this brand.");
        }

        $brand->update($this->request->validated());

        return $brand;

    }
}