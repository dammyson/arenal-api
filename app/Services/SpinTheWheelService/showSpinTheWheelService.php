<?php

namespace App\Services\SpinTheWheelService;

use App\Models\SpinTheWheel;
use App\Services\BaseServiceInterface;

class showSpinTheWheelService implements BaseServiceInterface
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {
        
    
        try {
            $spinTheWheels = SpinTheWheel::with([
                    'spinTheWheelForms',
                    'spinTheWheelSegments',
                    'spinTheWheelUserForms',
                    // 'spinTheWheelRewardSetups',
                    'spinTheWheelcustomGameTexts'
                
                ])
                ->findOrFail($this->id);
                
           
            return $spinTheWheels;
        } catch (\Throwable $e) {
    
            throw $e;
        }
    }
    
}
