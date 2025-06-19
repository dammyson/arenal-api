<?php

namespace App\Services\SpinTheWheelComponentService;

use Illuminate\Support\Facades\DB;
use App\Models\SpinTheWheelUserForm;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelUserFormRequest;

class StoreSpinTheWheelUserFormService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelUserFormRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {

            $spinTheWheel = SpinTheWheelUserForm::create(
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
