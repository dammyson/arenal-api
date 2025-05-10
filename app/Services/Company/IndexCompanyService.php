<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Auth\RegisterUserRequest;

class IndexCompanyService implements BaseServiceInterface{

    public function __construct()
    {

    }

    public function run() {
        
         $companies = Company::all();

         $companyUser = CompanyUser::all();
        
        //  dd([$companies, $companyUser]);
         return [$companies, $companyUser];

    }
}