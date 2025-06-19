<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheel;
use Illuminate\Support\Facades\DB;
use App\Models\SpinTheWheelSegment;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSegmentRequest;
use App\Http\Requests\SpinTheWheel\UpdateSpinTheWheelSegmentRequest;

class UpdateSpinTheWheelSegmentService implements BaseServiceInterface
{
    protected $request;
    protected $id;

    public function __construct(UpdateSpinTheWheelSegmentRequest $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function run()
    {
        
        $spinTheWheelSegment = SpinTheWheelSegment::findOrFail($this->id);
        try {

            $spinTheWheelSegment->update($this->request->validated());    
         
            return $spinTheWheelSegment;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
    
}
