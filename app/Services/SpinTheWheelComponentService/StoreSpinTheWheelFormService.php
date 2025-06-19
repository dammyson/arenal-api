<?php

namespace App\Services\SpinTheWheelComponentService;

use App\Models\SpinTheWheelForm;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelFormRequest;

class StoreSpinTheWheelFormService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelFormRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelForm::create(
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
