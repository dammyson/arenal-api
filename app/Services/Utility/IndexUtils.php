<?php
namespace App\Services\Utility;

use App\Models\Campaign;
use Exception;

class IndexUtils
{
    
    // add audience to campaign
    public  function addAudienceToCampaign($campaignId, $audienceId) {
        try {
            $campaign = Campaign::where('id', $campaignId)
                ->lockForUpdate()
                ->firstOrFail();

            // dd($campaign);
            if ($campaign->max_participants !== null) {
                    $currentCount = $campaign->participants()->count();
                    // check if the audience is already part of the campaign
                    if ($campaign->participants()->where('audience_id', $audienceId)->exists()) {
                        return true; // Audience already part of the campaign
                    }
                    if ($currentCount >= $campaign->max_participants) {
                        return false;
                    }
                    $campaign->participants()->syncWithoutDetaching([$audienceId]);
                   
            }
            return true;
        } catch(Exception $e) {
            throw new Exception('Error adding audience to campaign: ' . $e->getMessage());
        }
    }   
    
    public function checkCampaignCapacity($campaignId) {
        $campaign = Campaign::findOrFail($campaignId);
        if ($campaign->max_participants === null) {
            return true; // No limit set
        }
        $currentCount = $campaign->participants()->count();
        return $currentCount < $campaign->max_participants;
    }
}