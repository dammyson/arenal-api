<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use App\Http\Requests\User\CompanyStoreRequest;
use App\Services\Company\CreateCompanyService;
use App\Services\Company\IndexCompanyService;

class CompanyController extends BaseController
{
    public function index(Request $request)
    {
        try {
           
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
            $data = (new CreateCompanyService($request))->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "company info retrieved succcessfully", 201);

    }
}
