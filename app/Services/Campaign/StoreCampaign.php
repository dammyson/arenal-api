<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;

class StoreCampaign implements BaseServiceInterface{
    protected $request;

    public function __construct(StoreCampaignRequest $request)
    {
        $this->request = $request;   
    }

    public function run() {
       return Campaign::create([...$this->request->validated(), 'created_by' => $this->request->user()->id]);
    }
}