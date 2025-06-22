<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheel;
use App\Models\SectorRewardSetup;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Models\SpinTheWheelRewardSetup;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelRewardSetupRequest;

class StoreSpinTheWheelRewardSetupService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelRewardSetupRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelRewardSetup::create(
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
