<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\User\ClientStoreRequest;
use App\Services\Client\IndexClientService;
use App\Services\Client\StoreClientService;

class ClientController extends BaseController
{
    public function index()
    {
        try {
           
        
            $data = (new IndexClientService())->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "client info retrieved succcessfully");
    
    }

    public function storeClient(ClientStoreRequest $request)
    {
        try {           

            $data = (new StoreClientService($request))->run();
        
        } catch (\Exception $e){
            return $this->sendError("unable to client store", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "client created succcessfully", 201);
  
        
    }
}
