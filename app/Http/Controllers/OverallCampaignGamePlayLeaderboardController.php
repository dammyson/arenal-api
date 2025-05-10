<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use App\Services\CampaignGamePlay\OverallCampaignGamePlayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OverallCampaignGamePlayLeaderboardController extends BaseController
{
    protected $overallGamePlayService;

    public function __construct() {
        $this->overallGamePlayService = new OverallCampaignGamePlayService();
    }
  
    public function overallLeaderboard()
    {
        try {

            $data = $this->overallGamePlayService->overallLeaderboard();
            
        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Overleaderboard data retrieved succcessfully");
    
    }

    public function overallGamePlayTopThree() 
    {
        try {
            $data = $this->overallGamePlayService->overallGamePlayTopThree();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, " top three Overleaderboard data retrieved succcessfully");
    
    }

    public function overallLeaderboardDaily()
    {
         try {

            $data = $this->overallGamePlayService->overallLeaderboardDaily();


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, " Daily Overleaderboard data retrieved succcessfully");
    
    }

    public function overallLeaderboardWeekly()
    {
        try { 
            // dd("i ran");
            $data = $this->overallGamePlayService->overallLeaderboardWeekly();


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "weekly Overleaderboard data retrieved succcessfully");
    
    }

    public function overallLeaderboardMonthly()
    {
      
        try { 
            $data = $this->overallGamePlayService->overallLeaderboardMonthly();


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "monthly Overleaderboard data retrieved succcessfully");
    
    }

}