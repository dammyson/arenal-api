<?php

namespace App\Services\CampaignGameRule;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CampaignGameRule;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Auth\RegisterUserRequest;

class ShowCampaignGameRuleService implements BaseServiceInterface {
    protected $request;
    protected $campaignId;
    protected $gameId;
    
    public function __construct($campaignId, $gameId) {
        $this->campaignId = $campaignId;
        $this->gameId = $gameId;
    }

    public function run() {
  
        $rules = CampaignGameRule::where('campaign_id', $this->campaignId)
            ->where('game_id', $this->gameId)
            ->get();

        $game = Game::find($this->gameId); 

        return ["rules" => $rules,"game" => $game];
    }
}