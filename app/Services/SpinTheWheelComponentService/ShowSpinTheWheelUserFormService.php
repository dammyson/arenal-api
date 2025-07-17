<?php

namespace App\Services\SpinTheWheelComponentService;

use Illuminate\Support\Facades\DB;
use App\Models\SpinTheWheelUserForm;
use App\Services\BaseServiceInterface;
use App\Models\SpinTheWheelSetUserForm;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelUserFormRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSetUserFormRequest;

class ShowSpinTheWheelUserFormService implements BaseServiceInterface
{
    protected $spinTheWheelId;

    public function __construct($spinTheWheelId)
    {
        $this->spinTheWheelId = $spinTheWheelId;
    }

    public function run()
    {
        
    
        try {

            $showSpinTheWheelUserForm = SpinTheWheelSetUserForm::where("spin_the_wheel_id", $this->spinTheWheelId)->get();
    
          
    
            return $showSpinTheWheelUserForm;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store user form',
                'error' => $e->getMessage()
            ];
        }
    }
    
}