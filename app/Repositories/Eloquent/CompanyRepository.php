<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CompanyRepositoryInterface;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getAllCompanies(): Collection
    {
        return Company::all();
    }



    public function getCompanyById(int $candidateId): ?Company
    {
        return Company::find($candidateId);
    }

    public function createCompany(array $data): Company
    {
        return Company::create($data);
    }

    public function updateCompany(int $candidateId, array $data): bool
    {
        $user = Company::find($candidateId);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    public function deleteCompany(int $candidateId): bool
    {
        $user = Company::find($candidateId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
