<?php

namespace App\Services\Live;

use Error;
use Exception;
use Carbon\Carbon;
use App\Models\Live;
use App\Models\Brand;
use App\Models\BrandPoint;
use App\Models\LiveTicket;
use App\Models\BrandDetail;
use Illuminate\Support\Facades\DB;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\Live\StoreJoinLiveRequest;
use App\Models\AudienceLiveStreak;

class JoinBrandLiveService implements BaseServiceInterface{
    protected $request;

    public function __construct(StoreJoinLiveRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
       try {
       
            $user = $this->request->user();

            $live = Live::findOrFail($this->request->live_id);

            $currentTime = Carbon::now('Africa/Lagos'); // or your actual local TZ

            $startTime = Carbon::createFromFormat('H:i:s', $live->start_time, 'Africa/Lagos')
                ->setDate($currentTime->year, $currentTime->month, $currentTime->day);

            $endTime = Carbon::createFromFormat('H:i:s', $live->end_time, 'Africa/Lagos')
                ->setDate($currentTime->year, $currentTime->month, $currentTime->day);

            // Handle midnight crossover (e.g. 23:00 → 01:00)
            if ($endTime->lessThan($startTime)) {
                $endTime->addDay();
            }

            if ($currentTime->lt($startTime) || $currentTime->gt($endTime)) {
                throw new Exception("You cannot join live at this time");
            }
            // check if the user has already gone live today
            // dump(now()->toDateString());
            $alreadyJoined = LiveTicket::where('live_id', $this->request->live_id)
                    ->where('audience_id', $user->id)
                    ->whereDate('created_at', now()->toDateString())->exists();

            // dd($alreadyJoined);
            if ($alreadyJoined) {
                throw new Exception("You have already gone live today");

            }                
           
            return DB::transaction(function() use($user, $live) {
                $liveTicket = LiveTicket::create([
                    ...$this->request->validated(),
                    'brand_id' => $live->brand_id,
                    'ticket_id' => $this->generateTicketId($user->email),
                    'audience_id' => $user->id
                ]);

                $brandPoint = BrandPoint::firstOrNew([
                    "brand_id" => $this->request->brand_id,
                    "audience_id" => $user->id
                ]);
                    
                $brandPoint->points =  ($brandPoint->points ?? 0) + $live->coins;
    
                $brandPoint->save();

                $liveStreak = AudienceLiveStreak::where("audience_id", $user->id)
                    ->where("live_id", $live->id)
                    ->first();

                $today = now()->toDateString();

                if (!$liveStreak) {
                    AudienceLiveStreak::create([
                        "audience_id" => $user->id,
                        "live_id" => $live->id,
                        "streak_count" => 1,
                        "last_joined" => $today
                    ]);

                } else {
                    $lastJoined = Carbon::parse($liveStreak->last_joined);
                    
                    if ($lastJoined->isYesterday()) {
                        $liveStreak->streak_count += 1;
                    } else {
                        $liveStreak->streak_count = 1;

                    }
                    $liveStreak->last_joined = $today;
                    $liveStreak->save();
                }

                return ["ticket" => $liveTicket , "brand_points" => $brandPoint];
            });
       
        } catch(\Throwable $e) {
            
            throw $e;
        }
       

    }

    
    private function generateTicketId($email)
    {
          // Get the part before @
        $usernamePart = strstr($email, '@', true); // everything before '@'

        do {
            $ticketId = $usernamePart . '_' . mt_rand(100000, 999999);
        } while (LiveTicket::where('ticket_id', $ticketId)->exists());

        return $ticketId;
    }

}