<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Auth\RegisterUserRequest;

use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use Exception;

class CreateUserService implements BaseServiceInterface
{
  protected $request;

  public function __construct($request)
  {

    $this->request = $request;
  }

  public function run()
  {
    

    
	try {
		
		DB::beginTransaction();
		 
		$user = User::create($this->request);


		$userWallet = Wallet::create([
			'user_id' => $user->id,
			'revenue_share_group' => 'audience'
		]);

		DB::commit();

		return $user;
    } catch (Exception $e) {
		DB::rollBack();

		// Optionally log the error
		// Log::error('User creation failed: ' . $e->getMessage());

		throw new Exception($e->getMessage());
	}   
   
  }
}
