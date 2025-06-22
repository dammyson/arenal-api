<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheel;
use Illuminate\Support\Facades\DB;
use App\Models\SpinTheWheelSegment;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSegmentRequest;
use App\Http\Requests\SpinTheWheel\UpdateSpinTheWheelSegmentRequest;

class DeleteSpinTheWheelSegmentService implements BaseServiceInterface
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {
        
        $spinTheWheelSegment = SpinTheWheelSegment::findOrFail($this->id);
        try {

            $spinTheWheelSegment->delete();   
         
            return "spin the wheel deleted";
        } catch (\Throwable $e) {
            throw $e;
        }
    }
    
}
