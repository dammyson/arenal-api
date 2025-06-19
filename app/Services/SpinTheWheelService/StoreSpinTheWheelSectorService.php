<?php

namespace App\Services\SpinTheWheelService;

use App\Models\SpinTheWheel;
use App\Models\SpinTheWheelSector;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSectorRequest;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelSectorRequest;

class StoreSpinTheWheelSectorService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelSectorRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $sectors = $this->request->validated()["sectors"];
            $spinTheWheelId = $this->request->validated()["spin_the_wheel_id"];

            $spinTheWheelSectors = [];

            foreach($sectors as $sector) {
             
                $spinTheWheelSector = SpinTheWheelSector::create([
                    "spin_the_wheel_id" => $spinTheWheelId, 
                    "text" => $sector['text']?? null, 
                    "color"  => $sector['color'] ?? null, 
                    "value"  => $sector['value'] ?? null, 
                    "image_url" => $sector['image_url'] ?? null, 
                    "user_id" => $this->request->user()->id    
                ]);

                $spinTheWheelSectors[] = $spinTheWheelSector;
            }
           
    
            DB::commit();
    
            return $spinTheWheelSectors;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
    
            return [
                'message' => 'Failed to store spin sector questions',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
