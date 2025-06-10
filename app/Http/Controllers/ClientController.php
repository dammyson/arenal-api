<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Client\IndexClientService;
use App\Services\Client\StoreClientService;
use App\Services\Client\DeleteClientService;
use App\Services\Client\UpdateClientService;
use App\Http\Requests\User\ClientStoreRequest;
use App\Http\Requests\User\ClientUpdateRequest;

class ClientController extends BaseController
{
    public function index()
    {
        try {
            Gate::authorize('is-user');
        
            $data = (new IndexClientService())->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "client info retrieved succcessfully");
    
    }

    public function storeClient(ClientStoreRequest $request)
    {
        try {           
            Gate::authorize('is-user');
            $data = (new StoreClientService($request))->run();
        
        } catch (\Exception $e){
            return $this->sendError("unable to store client", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "client created succcessfully", 201);
  
        
    }

    public function updateClient(ClientUpdateRequest $request, $id) {
          try {
            Gate::authorize('is-user');
            $data = (new UpdateClientService($request, $id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Client updated succcessfully");
    }

    public function deleteClient(Request $request, $id) {
          try {
            Gate::authorize('is-user');
            $data = (new DeleteClientService($request, $id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Client deleted succcessfully");
    }
}
