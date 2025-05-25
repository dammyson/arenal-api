<?php

namespace App\Services\CampaignGame;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\CampaignGame;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\Game\UpdateGameRequest;

class FilterCampaignGame implements BaseServiceInterface{
    protected $categoryName;

    public function __construct($type)
    {
        $this->categoryName = $type;
      
    }

    public function run() {
            
        $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');
        

        if ($this->categoryName == "all") {
            return CampaignGame::whereHas('game', function ($query) use($start_week, $end_week) {
                $query->whereDate('created_at', '>=', $start_week)
                ->whereDate('created_at', '<=', $end_week); 
               
            })->with('game')->get();
            
    
        
        } else {
            
           return CampaignGame::whereHas('game', function ($query) use($start_week, $end_week) {
                $query
                ->where('type', $this->categoryName)
                ->whereDate('created_at', '>=', $start_week)
                ->whereDate('created_at', '<=', $end_week); 
                
            })->with('game')->get();
            
        }
    }
}