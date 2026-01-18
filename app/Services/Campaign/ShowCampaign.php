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

        $campaign->games->each(function ($game) {
            if (method_exists($game, $game->type)) {
                $game->load($game->type);
            }
        });

       

        return $campaign;
        

    }
}