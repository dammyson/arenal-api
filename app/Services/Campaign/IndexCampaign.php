<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use GuzzleHttp\Psr7\Request;

class IndexCampaign implements BaseServiceInterface{
  
    protected $request;

    public function __construct($request)
    {  
        $this->request = $request;

    }

    public function run() {
        $filter = $this->request->query('filter');
        $category = $this->request->query('category');

        if (!$category) {
            return Campaign::where('is_arena', true)
                ->where('title', '!=', "spin the wheel")
                ->where('title', 'LIKE', "%{$filter}%")
                ->get();
        }

        return Campaign::whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            })
            ->where('is_arena', true)
            ->where('title', '!=', "spin the wheel")
            ->where('title', 'LIKE', "%{$filter}%")
            // ->where('title', 'LIKE', "%{$filter}%")
            ->get();

         
    }
}