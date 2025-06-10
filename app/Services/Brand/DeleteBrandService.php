<?php

namespace App\Services\Brand;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\BrandUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class DeleteBrandService implements BaseServiceInterface{
    protected $request;
    protected $id;

    public function __construct(Request $request, $id)
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

        $brand->delete();

        return true;

    }
}