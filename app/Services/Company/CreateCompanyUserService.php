<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\CompanyStoreRequest;

class CreateCompanyUserService implements BaseServiceInterface{
    protected $request;
    protected $userId;

    public function __construct(CompanyStoreRequest $request, $userId)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    public function run() {
      $company = Company::create($this->request->validated());
     
      $companyUser =  CompanyUser::create([
                    'company_id' => $company->id,
                    'user_id' =>  $this->userId
                ]);

      return ["company" => $company, "companyUser" => $companyUser];
    }
}