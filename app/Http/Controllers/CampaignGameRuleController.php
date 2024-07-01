<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\CampaignGameRuleRequest;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\CampaignGameRule;

class CampaignGameRuleController extends Controller
{
    
    public function store(CampaignGameRuleRequest $request, $campaign_id, $game_id) {
        try {
            $user = $request->user();

            if ($user->is_audience) {
                return response()->json([
                    'error' => true, 
                    'message' => "unauthorized"
                ], 401);
            }

            foreach ($request['rules_descriptions'] as $rules_description) {
                CampaignGameRule::create([
                    'campaign_id' => $campaign_id,
                    'game_id' => $game_id,
                    'rule_description' => $rules_description["rule"]
                ]);
            }

        } catch (\Throwable $throwable) {
            
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => $throwable->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => "Campaign game rule create successfully"
        ]);

    }

    public function showCampaignGameRules(Request $request, $campaign_id, $game_id) {

        try {
            $rules = CampaignGameRule::where('campaign_id', $campaign_id)
                ->where('game_id', $game_id)
                ->get();

            $game = Game::find($game_id); 

        } catch (\Throwable $throwable) {
            
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => "Game with rules",
            'campaign_game_rules' => $rules,
            'game' => $game
        ]);
        
    }
}
