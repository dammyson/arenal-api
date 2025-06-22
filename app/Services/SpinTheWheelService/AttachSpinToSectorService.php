<?php

namespace App\Services\SpinTheWheelService;

use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;
use App\Models\SpinTheWheel;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;

class AttachSpinToSectorService implements BaseServiceInterface
{
    protected $spinId;
    protected $spinSectorId;

    public function __construct($spinId, $spinSectorId)
    {
        $this->spinId = $spinId;
        $this->spinSectorId = $spinSectorId;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {
            $spinTheWheel = SpinTheWheel::findOrFail($this->spinId);
            $spinTheWheelSector = SpinTheWheel::findOrFail($this->spinSectorId);
            $spinTheWheelSector->spin_id = $spinTheWheel->id;
            $spinTheWheelSector->save();
    
            DB::commit();
    
            return $spinTheWheel;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store trivia questions',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
