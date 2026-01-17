<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Models\CampaignGame;

class ShowCampaign implements BaseServiceInterface{
    protected $campaignId;

    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;   
    }

    public function run() {
        
        $campaign = Campaign::where('id', $this->campaignId)
            ->with([
                'games' => function($query) {
                    $query->with([
                        'rules'
                    ]);
                }
            ])
            ->firstOrFail();

        return $campaign->games->each(function ($game) {
            if (method_exists($game, $game->type)) {
                $game->load($game->type);
            }
        });
        

    //     $campaignGame = CampaignGame::where("campaign_id", $this->campaignId)->first();
    //     // dd($campaignGame);
    //     $game = $campaignGame?->game;
    //     // dd($game);
    //     return CampaignGame::where("campaign_id", $this->campaignId)
    //         ->whereHas('game') // Just check if game relationship exists
    //         ->with(['campaign', 'game.rules', "game.{$game->type}"]) // Eager load both campaign and rules of game
    //         ->get();
    // //    return Campaign::where("id", $this->campaignId)->with('games')->first();
    }
}