<?php

namespace App\Services\SpinTheWheelComponentService;

use Illuminate\Support\Facades\DB;
use App\Models\SpinTheWheelSegment;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSegmentRequest;

class StoreSpinTheWheelSegmentService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelSegmentRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelSegment::create(
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
