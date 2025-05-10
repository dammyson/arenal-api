<?php

namespace App\Services\users;

use App\Models\User;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Auth\RegisterUserRequest;

class CreateUserService implements BaseServiceInterface {
    protected $request;
    protected $isAudience;
    
    public function __construct(RegisterUserRequest $request) {
   
        $this->request = $request;
    }

    public function run() {
      // dd($this->request);
      
      return User::create($this->request->validated());
    }
}