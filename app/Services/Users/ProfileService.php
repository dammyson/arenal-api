<?php

namespace App\Services\users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileEditRequest;

class ProfileService   {
    protected $user;
    
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getProfile() {
      return User::with('wallet')->find($this->user->id);

    }

	public function editProfile(ProfileEditRequest $req) {
		return $this->user->update($req->validated());
	}

	public function userInfo() {	
            
		$userInfo = [];
	  
		// $userTotalPoint = DB::table('campaign_leaderboards')
		//     ->where('audience_id', $user->id)
		//     ->select('total_points')
		//     ->sum('total_points');

		
		$userTotalPoint = DB::table('campaign_game_plays')
			->where('user_id', $this->user->id)
			->select('score')
			->sum('score');
			

		array_push($userInfo, $this->user->first_name, $this->user->profile_image, $userTotalPoint);
		
		return $userInfo;
	}
}