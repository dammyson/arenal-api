<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Services\BaseServiceInterface;

class IndexBrandService implements BaseServiceInterface{
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function run() {
        $filter = $this->request->query('filter');

        return  Brand::where('name', 'LIKE', "%{$filter}%")
            ->with('details')->get();
    }
}
