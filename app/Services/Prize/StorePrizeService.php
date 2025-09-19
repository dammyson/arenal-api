<?php

namespace App\Services\Prize;

use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\PrizeStoreRequest;
use App\Models\Prize;

class StorePrizeService implements BaseServiceInterface{
    protected $request;

    public function __construct(PrizeStoreRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        $user = $this->request->user();

        $gameId = $this->request->game_id;
        $campaignId = $this->request->campaign_id;
        $brandId = $this->request->brand_id;
        $prizes = $this->request->validated()['prizes'];

        $prizesList = [];
        
        foreach ($prizes as $prize) {

            $createdPrize = Prize::create([
                "campaign_id" => $campaignId, 
                "game_id" => $gameId, 
                "brand_id" => $brandId,
                "name" => $prize["name"],
                "description" => $prize["description"],
                "points" => $prize["points"],
                "amount" => $prize["amount"],
                "quantity" => $prize["quantity"],
                "image_url" => $prize["image_url"],
                "is_arena" => $prize["is_arena"]
            ]);

            $prizeList[] = $createdPrize;
        }

        return $prizeList;

    }
}