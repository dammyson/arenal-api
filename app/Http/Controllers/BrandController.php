<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\User\BrandStoreRequest;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        try {
            $brands = Brand::all();

        }  catch (\Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['error' => false,  'data' => $brands], 200);
    }

    public function storeBrand(BrandStoreRequest $request)
    {
        try{

            $user = $request->user();
           
            if ($user->is_audience) {
             return response()->json([
                 'error' => true, 
                 'message' => "unauthorized"
             ], 401);
 
            }
            
            $brand = Brand::create([
                ...$request->validated(),
                'created_by' => $user->id
            ]);

        } catch (\Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['error' => false,  'message' => 'Brand created successfully', 'data' => $brand], 201);
        }
}
