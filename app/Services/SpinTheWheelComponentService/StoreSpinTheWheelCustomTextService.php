<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheel;
use App\Models\CustomGameText;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Models\SpinTheWheelCustomGameText;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelCustomGameTextRequest;

class StoreSpinTheWheelCustomTextService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelCustomGameTextRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelCustomGameText::create(
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
