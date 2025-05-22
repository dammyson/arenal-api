<?php

namespace App\Services\CampaignGame;

use App\Models\Campaign;
use App\Models\CampaignGame;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;

class IndexFavoriteCampaignGame implements BaseServiceInterface{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;   
    }

    public function run() {
        $audience = $this->request->user('audience');

        $favoriteCampaignGames = CampaignGame::whereHas('game', function($query) use($audience){
            $query->where('audience_id', $audience->id)
            ->where('is_favorite', true);
        })->with('game')->get();
      

        return $favoriteCampaignGames->map(function($campaignGame) {
            return [
                'campaign_id' => $campaignGame->campaign_id,
                'game_id' => $campaignGame->game_id,
                'name' => $campaignGame->game->name,
                'type' => $campaignGame->game->type,
                'image_url' => $campaignGame->game->image_url,
                'is_favorite' => $campaignGame->game->is_favorite
            ];
        });
    
    }
}