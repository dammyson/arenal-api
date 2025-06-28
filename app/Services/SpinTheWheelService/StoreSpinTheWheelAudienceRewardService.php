<?php

namespace App\Services\SpinTheWheelService;

use App\Models\SpinTheWheel;
use App\Models\SpinTheWheelSector;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSectorRequest;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelSectorRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelAudienceRewardRequest;
use App\Models\AudienceSpinTheWheelReward;

class StoreSpinTheWheelAudienceRewardService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelAudienceRewardRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {             
                $audienceSpinTheWheelReward = AudienceSpinTheWheelReward::create([
                    "spin_the_wheel_id" => $this->request["spin_the_wheel_id"], 
                    "audience_id" => $this->request->user()->id, 
                    "prize"  => $this->request['prize']
                ]);

            DB::commit();          
            return $audienceSpinTheWheelReward;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
    
            return [
                'message' => 'Failed to store audience prize',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
