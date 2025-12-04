<?php
namespace App\Services\Utility;

use App\Models\AudienceDailyBonus;
use App\Models\Brand;
use App\Models\BrandPoint;
use App\Models\Game;

class CheckDailyBonusService
{
   
    public function checkEligibility($brandId,  $gameId, $audienceId, $isArena) {
         // Get today's record
        $today = now()->toDateString();
        // dd(" i got here");

        $game = Game::findOrFail($gameId);

        if ($game->daily_bonus > 1 && !$isArena) { 
            // dd('Iran');           
            $audienceDailyBonus = AudienceDailyBonus::where('audience_id', $audienceId)
                ->where('game_id', $gameId)
                ->first();

            if (!$audienceDailyBonus) {  
                $newRecord = AudienceDailyBonus::create([
                    'audience_id' => $audienceId,
                    // 'brand_id' => $brandId,
                    'game_id' => $gameId,
                    'is_arena' => false,
                ]);              
                return [true, $newRecord->id];  
            }

        } else {
            // dd("seond");
            $audienceDailyBonus = AudienceDailyBonus::where('audience_id', $audienceId)
                ->when($isArena, fn($q) => ($q->where('is_arena', true)))
                ->when(!$isArena, fn($q) => ($q->where('brand_id', $brandId)))
                ->first();

            if (!$audienceDailyBonus) {  
                $newRecord = AudienceDailyBonus::create([
                    'audience_id' => $audienceId,
                    'brand_id' => $brandId,
                    // 'game_id' => $gameId,
                    'is_arena' => $isArena,
                ]);              
                return [true, $newRecord->id];        
            
            } 
        }

        if($audienceDailyBonus->bonus_date === $today) {
            return [false, null];
        }
                
       // Eligible for today's bonus
        return [true, $audienceDailyBonus->id];
       
    }

    public function allocatedDailyBonus($audienceDailyBonusId, $audienceId, $brandId = null, $gameId, $isArena) {
        
        $brandPoint = BrandPoint::firstOrCreate(
            [
                'audience_id' => $audienceId,
                'is_arena' => $isArena,
                'brand_id' => $brandId
            ], ['points' => 0]);
        
        
        $dailyBonus = 10;
        if (!$isArena) {
            $game = Game::find($gameId);
            $brand =  Brand::findorFail($brandId);

            $dailyBonus = ($game && $game->daily_bonus > 0) ? $game->daily_bonus : $brand->daily_bonus;
            if ($dailyBonus < 1) {
                $dailyBonus = 0; // No bonus configured
            }  
            
        } 

        $brandPoint->increment('points', $dailyBonus);

        // Mark as used today
        $dailyBonusRecord = AudienceDailyBonus::findOrFail($audienceDailyBonusId);
        $dailyBonusRecord->update([
            'bonus_date' => now()->toDateString(),
        ]);

        return $dailyBonus;
    }

   

    public function checkHighScore($audienceId, $points, $brandId, $isArena ) {

        $highScoreBonus = $isArena ? 10 : Brand::findorFail($brandId)->high_score_bonus;  
        
        // dd($highScoreBonus);

        $brandPoint = BrandPoint::where('audience_id', $audienceId)
                ->when($isArena, fn($q) => $q->where('is_arena', true))
                ->when(!$isArena, fn($q) => $q->where('brand_id', $brandId))
                ->first();

        if (!$brandPoint) {
            return [true, $highScoreBonus];
            
        }

        // dd($brandPoint->points);

        return ( $points > $brandPoint?->points) ? [true, $highScoreBonus] :  [false, null];

    }

}