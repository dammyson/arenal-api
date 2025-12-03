<?php
namespace App\Services\Utility;

use App\Models\AudienceDailyBonus;
use App\Models\Brand;
use App\Models\BrandPoint;

class CheckDailyBonusService
{
    // protected $brandId;
    // protected $audienceId;
    // protected $isArena;

    // public function __construct()
    // {
    //     $this->brandId = $brandId;
    //     $this->audienceId = $audienceId;
    //     $this->isArena = $isArena;
        
    // }

    public function checkEligibility($brandId, $audienceId, $isArena) {
         // Get today's record
        $today = now()->toDateString();

         
        $audienceDailyBonus = AudienceDailyBonus::where('audience_id', $audienceId)
            ->when($isArena, fn($q) => ($q->where('is_arena', true)))
            ->when(!$isArena, fn($q) => ($q->where('brand_id', $brandId)))
            ->first();

        if (!$audienceDailyBonus) {  
            $newRecord = AudienceDailyBonus::create([
                'audience_id' => $audienceId,
                'brand_id' => $brandId,
                'is_arena' => $isArena,
            ]);              
            return [true, $newRecord->id];        
        
        } 

        if($audienceDailyBonus->bonus_date === $today) {
            return [false, null];
        }
                
       // Eligible for today's bonus
        return [true, $audienceDailyBonus->id];
       
    }

    public function allocatedDailyBonus($audienceDailyBonusId, $audienceId, $brandId = null, $isArena) {
        
        $brandPoint = BrandPoint::firstOrCreate(
            [
                'audience_id' => $audienceId,
                'is_arena' => $isArena,
                'brand_id' => $brandId
            ], ['points' => 0]);
        
        
        
        if (!$isArena) {
            $brand = Brand::findorFail($brandId);

            if ($brand->daily_bonus > 0) {
                $brandPoint->increment('points', $brand->daily_bonus);
                $audienceDailyBonus = AudienceDailyBonus::findOrFail($audienceDailyBonusId);        
                $audienceDailyBonus->update([
                    'bonus_date' => now()->toDateString()
                ]);
                return $brand->daily_bonus;

            }     
            
        } else {
            
            $brandPoint->increment('points', 10);
            $audienceDailyBonus = AudienceDailyBonus::findOrFail($audienceDailyBonusId);        
            $audienceDailyBonus->update([
                'bonus_date' => now()->toDateString()
            ]);
            return 10;
        }


       

      
    }
}