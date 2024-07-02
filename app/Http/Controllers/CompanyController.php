<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use App\Http\Requests\User\CompanyStoreRequest;

class CompanyController extends Controller
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
           
            $companies = Company::all();

            $companyUser = CompanyUser::all();

        }  catch (\Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['error' => false, 'companyUser' => $companyUser, 'companies' => $companies, ], 200);

    }

    public function storeCompany(CompanyStoreRequest $request)
    {   
        try {
           $user = $request->user();

           if ($user->is_audience) {
            return response()->json([
                'error' => true, 
                'message' => "unauthorized"
            ], 401);

           }
           $company = Company::create($request->validated());

            if ( isset($company->id) ) {
                $companyUser = CompanyUser::create([
                    'company_id' => $company->id,
                    'user_id' =>  $request->user()->id
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Company not found"

                ], 404);
            }
     

        } catch (\Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['error' => false, 'message' => 'Company created successfully', 
                'data1' => $companyUser, 'data' => $company], 
                201);
    }
}
