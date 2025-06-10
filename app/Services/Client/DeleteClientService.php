<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\ClientStoreRequest;
use App\Http\Requests\User\ClientUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class DeleteClientService implements BaseServiceInterface{
    protected $request;
    protected $id;

    public function __construct(Request $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function run() {
        $user = $this->request->user();
           
        $client = Client::findOrFail($this->id);
        
        if ($client->created_by !== $user->id) {
            throw new AuthorizationException("You are not permitted to edit this brand.");
        }

        $client->delete();

        return true;

    }
}