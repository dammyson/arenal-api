<?php

namespace App\Services\Live;

use App\Models\Live;
use App\Models\Brand;
use App\Models\BrandPoint;
use App\Models\LiveTicket;
use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\Live\StoreJoinLiveRequest;

class JoinBrandLiveService implements BaseServiceInterface{
    protected $request;

    public function __construct(StoreJoinLiveRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
       try {
       
        $user = $this->request->user();

            $liveTicket = LiveTicket::create([
                ...$this->request->validated(),
                'audience_id' => $user->id
            ]);
            
            $brandPoint = BrandPoint::where("brand_id", $this->request->brand_id)
                ->where("audience_id", $user->id)->first();

            if (!$brandPoint) {

                $brandPoint = BrandPoint::create([
                    "brand_id" => $this->request->brand_id,
                    "audience_id" => $user->id,
                    "points" => $this->request->coined_earned
                ]);             

            } else{

                $brandPoint->points += $this->request->coined_earned;

            }

            $brandPoint->save();
            return ["ticket" => $liveTicket , "brand_points" => $brandPoint];
       
        } catch(\Throwable $e) {
            
            throw $e;
        }
       

    }
}