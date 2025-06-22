<?php

namespace App\Services\SpinTheWheelService;

use App\Models\SpinTheWheel;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelRequest;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;

class StoreSpinTheWheelService implements BaseServiceInterface
{
    protected $request;

    public function __construct(StoreSpinTheWheelRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        DB::beginTransaction();
    
        try {
            $spinTheWheel = SpinTheWheel::create([
                ...$this->request->validated(),
                'user_id' => $this->request->user()->id    
            ]);
    
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
