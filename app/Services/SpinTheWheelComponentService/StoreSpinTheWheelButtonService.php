<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\Button;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSectorButtonRequest;
use App\Http\Requests\SpinTheWheel\StoreSectorSegmentRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelButtonRequest;

class StoreSpinTheWheelButtonService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelButtonRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = Button::create(
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
