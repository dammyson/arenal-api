<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelAdsRequest;
use App\Models\SpinTheWheel;
use App\Models\CustomGameText;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Models\SpinTheWheelCustomGameText;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelCustomGameTextRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelParticipationRequest;
use App\Models\SpinTheWheelAds;
use App\Models\SpinTheWheelParticipationDetails;

class StoreSpinTheWheelAdsService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelAdsRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelAds::create(
                $this->request->validated()
            );
    
            DB::commit();
    
            return $spinTheWheel;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store segments',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
