<?php

namespace App\Services\Point;

use App\Models\BrandPoint;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreJoinLiveRequest;

class GetAudienceBrandPointService implements BaseServiceInterface
{
    protected $request;
    protected $brandId;

    public function __construct(Request $request, $brandId)
    {
        $this->request = $request;
        $this->brandId = $brandId;
    }

    public function run()
    {
        try {

            $user = $this->request->user();
            return ['points' => BrandPoint::where('brand_id', $this->brandId)
                ->where('audience_id', $user->id)
                ->first()
                ?->points ?? 0];
                
        } catch (\Throwable $e) {

            throw $e;
        }
    }
}
