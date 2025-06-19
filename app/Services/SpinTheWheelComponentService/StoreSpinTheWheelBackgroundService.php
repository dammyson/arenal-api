<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\Button;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelButtonRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelBackgroundRequest;
use App\Models\SpinTheWheelBackground;

class StoreSpinTheWheelBackgroundService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelBackgroundRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelBackground::create(
                $this->request->validated()
            );
    
            DB::commit();
    
            return $spinTheWheel;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store button',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
