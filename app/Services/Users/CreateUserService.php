<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Wallet;

class CreateUserService implements BaseServiceInterface
{
  protected $request;

  public function __construct($request)
  {

    $this->request = $request;
  }

  public function run()
  {
    $user = User::create($this->request);


    $userWallet = Wallet::create([
      'user_id' => $user->id,
      'revenue_share_group' => 'audience'
    ]);

    return $user;
  }
}
