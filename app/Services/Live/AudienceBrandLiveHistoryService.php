<?php

namespace App\Services\Live;

use Error;
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
use Exception;

class AudienceBrandLiveHistoryService implements BaseServiceInterface{
    protected $userId;
    protected $brandId;

    public function __construct($userId, $brandId)
    {
        $this->userId = $userId;
        $this->brandId = $brandId;
    }

    public function run() {
       try {
       
            // $user = $this->request->user();

            
            return LiveTicket::where('brand_id', $this->brandId)
                ->where('audience_id', $this->userId)
                ->get();

               
            
       
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