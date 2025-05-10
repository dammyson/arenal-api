<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\ClientStoreRequest;

class StoreClientService implements BaseServiceInterface{
    protected $request;

    public function __construct(ClientStoreRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        $user = $this->request->user();
           
        

        return Client::create([
             ...$this->request->validated(),
             'created_by' => $user->id
         ]);

    }
}