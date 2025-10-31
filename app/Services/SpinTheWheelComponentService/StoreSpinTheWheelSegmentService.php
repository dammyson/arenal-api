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

            $segments = $this->request->validated()["segments"];
            $spinTheWheelId = $this->request->validated()["spin_the_wheel_id"];
            $spinTheWheelSegments = [];

            foreach($segments as $segment) {

                $spinTheWheel = SpinTheWheelSegment::create(
                    [
                        "spin_the_wheel_id" => $spinTheWheelId, 
                        "label_text" => $segment["label_text"], 
                        "label_color"=> $segment["label_color"], 
                        "background_color" => $segment["background_color"], 
                        "icon" => $segment["icon"], 
                        "probability" => $segment["probability"]
                        
                    ]);

                $spinTheWheelSegments[] = $spinTheWheel;
                
            }
    
            DB::commit();
    
            return $spinTheWheelSegments;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store segments',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
