<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheelSector;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSectorRequest;

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

            $spinTheWheel = SpinTheWheelSector::create([
                ...$this->request->validated(),
                'user_id' => $this->request->user()->id    
            ]);
    
            DB::commit();
    
            return $spinTheWheel;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store item',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
