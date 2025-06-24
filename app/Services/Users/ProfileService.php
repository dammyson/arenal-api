<?php

namespace App\Services\Users;

use App\Models\User;
use App\Models\Audience;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileEditRequest;

class ProfileService   {
    protected $audience;
    
    public function __construct(Audience $audience) {
        $this->audience = $audience;
    }

    public function getProfile() {
      return Audience::with('wallet')->find($this->audience->id);

    }

	public function editProfile(ProfileEditRequest $req) {
		return $this->audience->update($req->validated());
	}

	public function uploadProfilePhoto($url) {
		$this->audience->profile_image = $url;
		$this->audience->save();
		return $this->audience;
	}

	public function userInfo() {	
            
		$userInfo = [];
	  
		// $userTotalPoint = DB::table('campaign_leaderboards')
		//     ->where('audience_id', $user->id)
		//     ->select('total_points')
		//     ->sum('total_points');

		// $userTotalPoint = DB::table('campaign_game_plays')
		
		$audienceTotalPoint = DB::table('campaign_game_plays')
			->where('audience_id', $this->audience->id)
			->select('score')
			->sum('score');
			

		array_push($userInfo, $this->audience->first_name, $this->audience->profile_image, $audienceTotalPoint);
		
		return $userInfo;
	}

	public function changeAudiencePin($pin) {
		$this->audience->pin = $pin;
		$this->audience->save();
		return $this->audience;
	}
}