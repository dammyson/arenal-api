<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheel;
use App\Models\CustomGameText;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Models\SpinTheWheelCustomGameText;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelCustomGameTextRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelParticipationRequest;
use App\Models\SpinTheWheelParticipationDetails;

class StoreSpinTheWheelParticipationService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelParticipationRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelParticipationDetails::create(
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
