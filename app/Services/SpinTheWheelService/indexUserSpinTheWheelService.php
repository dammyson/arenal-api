<?php

namespace App\Services\SpinTheWheelService;

use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;
use App\Models\SpinTheWheel;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use Illuminate\Http\Request;

class IndexUserSpinTheWheelService implements BaseServiceInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run()
    {    
        try {
            return SpinTheWheel::where('user_id', $this->request->user()->id)
                ->with([
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
