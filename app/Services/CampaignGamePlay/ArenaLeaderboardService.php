<?php

namespace App\Services\CampaignGamePlay;

use Exception;
use Carbon\Carbon;
use App\Models\Badge;
use App\Models\ArenaBadges;
use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;

class ArenaLeaderboardService
{
    protected $request;
    protected $brandId;


    public function __construct(Request $request, $brandId)
    {
        $this->request = $request; 
        $this->brandId = $brandId; 
    }

    public function run()
    {
        try {
            $audience = $this->request->user();
            $filter   = $this->request->query('filter');

            // Base query: aggregate scores per audience
            $leaderboard = CampaignGamePlay::
                whereHas('campaign.brand', function($query) {
                    $query->where('id', $this->brandId);
                })
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
                    $q->with(['audienceBadges' => function ($q2) {
                        $q2->where('brand_id', $this->brandId)
                            ->with(['badge' => function ($b) {
                                $b->select('id', 'name', 'points');
                            }])
                        ->orderBy(
                            Badge::select('points')
                                ->whereColumn('badges.id', 'audience_badges.arena_badge_id'),
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
