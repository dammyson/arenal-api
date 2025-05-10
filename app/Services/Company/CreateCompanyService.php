<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\CompanyStoreRequest;

class CreateCompanyService implements BaseServiceInterface{
    protected $request;

    public function __construct(CompanyStoreRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
      $company = Company::create($this->request->validated());
      $companyUser =  CompanyUser::create([
                    'company_id' => $company->id,
                    'user_id' =>  $this->request->user()->id
                ]);

      return ["company" => $company, "company_user" => $companyUser];
    }
}