<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\User\ClientStoreRequest;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
           
            if ($user->is_audience) {
             return response()->json([
                 'error' => true, 
                 'message' => "unauthorized"
             ], 401);
 
            }

            $clients = Client::all();

        }  catch (\Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['error' => false,  'data' => $clients], 200);
    }

    public function storeClient(ClientStoreRequest $request)
    {
        try {
           $user = $request->user();
           
           if ($user->is_audience) {
            return response()->json([
                'error' => true, 
                'message' => "unauthorized"
            ], 401);

           }

            $client = Client::create([
                ...$request->validated(),
                'created_by' => $user->id
            ]);

        } catch (\Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['error' => false,  'message' => 'Client created successfully', 'data' => $client], 201);
    }
}
