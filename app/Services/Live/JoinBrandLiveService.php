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

            $currentTime = now();

            // $startTime = Carbon::createFromFormat('H:i:s', $live->start_time);
            // $endTime   = Carbon::createFromFormat('H:i:s', $live->end_time);
            
            // Attach today's date to the stored start & end times
            $startTime = Carbon::createFromFormat('H:i:s', $live->start_time)->setDate(
                $currentTime->year,
                $currentTime->month,
                $currentTime->day
            );

            $endTime = Carbon::createFromFormat('H:i:s', $live->end_time)->setDate(
                $currentTime->year,
                $currentTime->month,
                $currentTime->day
            );
            
            if ($currentTime->lt($startTime) || $currentTime->gt($endTime)) {

                    throw new Exception("You cannot join live at this time");
                    // return ["message" => "You cannot join live at this time"];
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