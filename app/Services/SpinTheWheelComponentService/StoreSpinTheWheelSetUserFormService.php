<?php

namespace App\Services\SpinTheWheelComponentService;

use Illuminate\Support\Facades\DB;
use App\Models\SpinTheWheelUserForm;
use App\Services\BaseServiceInterface;
use App\Models\SpinTheWheelSetUserForm;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelUserFormRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSetUserFormRequest;

class StoreSpinTheWheelSetUserFormService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelSetUserFormRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelSetUserForm::create(
                $this->request->validated()
            );
    
            DB::commit();
    
            return $spinTheWheel;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store user form',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
