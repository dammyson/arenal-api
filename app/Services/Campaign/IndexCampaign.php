<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;

class IndexCampaign implements BaseServiceInterface{
  

    public function __construct()
    {  
    }

    public function run() {
        return Campaign::where('title', '!=', 'rmc world campaign')->get();
    }
}