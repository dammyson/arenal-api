<?php

namespace App\Services\CampaignGamePlay;

use Carbon\Carbon;
use App\Models\ArenaBadges;
use App\Models\CampaignGamePlay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ArenaLeaderboardService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request; 
    }

    public function run()
    {
        try {
            $audience = $this->request->user();
            $filter   = $this->request->query('filter');

            // Base query: aggregate scores per audience
            $leaderboard = CampaignGamePlay::query()
                // ->whereNull('brand_id')
                ->select('audience_id', DB::raw('SUM(score) as total_score'));

            // Apply date filter
            if ($filter === 'daily') {
                $leaderboard->whereDate('updated_at', Carbon::today());
            } elseif ($filter === 'weekly') {
                $leaderboard->whereBetween('updated_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
            } elseif ($filter === 'monthly') {
                $leaderboard->whereBetween('updated_at', [
                    Carbon::now()->firstOfMonth(),
                    Carbon::now()->lastOfMonth(),
                ]);
            }

            // Group and sort
            $leaderboard = $leaderboard
                ->groupBy('audience_id')
                ->orderByDesc('total_score')
                ->with(['audience' => function ($q) {
                    $q->with(['arenaAudienceBadges' => function ($q2) {
                        $q2->with(['arenaBadge' => function ($b) {
                            $b->select('id', 'name', 'points');
                        }])
                        ->orderBy(
                            ArenaBadges::select('points')
                                ->whereColumn('id', 'arena_audience_badges.arena_badge_id'),
                            'desc'
                        )
                        ->orderBy('arena_audience_badges.created_at', 'desc');
                    }]);
                }])
                ->get();

            return [
                'audience_id' => $audience->id,
                'leaderboard' => $leaderboard,
            ];

        } catch (Exception $e) {
            throw $e; // keep stack trace
        }
    }
}
