<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Services\BaseServiceInterface;

class IndexBrandService implements BaseServiceInterface{
    public function __construct()
    {
    }

    public function run() {
        return  Brand::with('details')->get();
    }
}
