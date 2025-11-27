<?php

namespace App\Services\CampaignGameRule;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CampaignGameRule;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Campaign\CampaignGameRuleRequest;

class StoreCampaignGameRuleService implements BaseServiceInterface {
    protected $request;
    protected $campaignId;
    protected $gameId;
    
    public function __construct(CampaignGameRuleRequest $request, $campaignId, $gameId) {
        $this->request = $request;
        $this->campaignId = $campaignId;
        $this->gameId = $gameId;
    }

    public function run() {
        $data = [];
        // dd("i ran");
        
        foreach ($this->request['rules_descriptions'] as $rules_description) {
            $campGameRule = CampaignGameRule::create([
                'campaign_id' => $this->campaignId,
                'game_id' => $this->gameId,
                'rule_description' => $rules_description
            ]);

            $data[] = $campGameRule;
        }

        return $data;



    }
}