<?php

namespace App\Services\SpinTheWheelService;

use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;
use App\Models\SpinTheWheel;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use Illuminate\Http\Request;

class IndexSpinTheWheelService implements BaseServiceInterface
{

    public function __construct()
    {
    }

    public function run()
    {    
        try {
            return SpinTheWheel::with([
                    'spinTheWheelSectors.sectorForms',
                    'spinTheWheelSectors.sectorSegments',
                    'spinTheWheelSectors.sectorUserForms',
                    'spinTheWheelSectors.sectorRewardSetups',
                    'spinTheWheelSectors.customGameTexts'
                
                ])->whereHas('spinTheWheelSectors')
                ->get();
            
        } catch (\Throwable $e) {
            
           throw $e;
        }
    }
    
}
