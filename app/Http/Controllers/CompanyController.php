<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\Company\IndexCompanyService;
use App\Services\Company\CreateCompanyService;
use App\Http\Requests\User\CompanyStoreRequest;
use App\Services\Company\CreateCompanyUserService;

class CompanyController extends BaseController
{
    public function index(Request $request)
    {
        try {
            Gate::authorize('is-user');
            $companyService = new IndexCompanyService();
            $data = $companyService->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "company info retrieved succcessfully");

    }

    public function storeCompany(CompanyStoreRequest $request)
    {   
        try {            
            Gate::authorize('is-user');
            $user = Auth::user();
            $data = (new CreateCompanyUserService($request, $user->id))->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "company info retrieved succcessfully", 201);

    }
}
