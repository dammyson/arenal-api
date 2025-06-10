<?php

namespace App\Services\Company;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\CompanyStoreRequest;
use App\Http\Requests\User\CompanyUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateCompanyService implements BaseServiceInterface
{
    protected $request;
    protected $companyId;

    public function __construct(CompanyUpdateRequest $request, $companyId)
    {
        $this->request = $request;
        $this->companyId = $companyId;
    }

    public function run()
    {

        $company = Company::findOrFail($this->companyId);

        $companyUser = CompanyUser::where('company_id', $this->companyId)
            ->where('user_id', $this->request->user()->id)
            ->first();
        

        if (!$companyUser) {
            throw new AuthorizationException("You are not permitted to update this company");
        }

        $company->update($this->request->validated());
        

        

        return ["company" => $company, "companyUser" => $companyUser];
    }
}
