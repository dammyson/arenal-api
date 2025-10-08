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

        return Campaign::where('title', '!=', 'rmc world campaign')
            ->where('title', 'LIKE', "%{$filter}%")
            ->get();
    }
}