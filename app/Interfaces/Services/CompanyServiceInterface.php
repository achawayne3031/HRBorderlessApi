<?php

namespace App\Interfaces\Services;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyServiceInterface
{
    public function fetchAllCompanies(): Collection;
    public function findCompanyById(int $userId): ?Company;
    public function createNewCompany(array $data): Company;
    public function updateExistingCompany(int $companyId, array $data): bool;
    public function deleteExistingCompany(int $companyId): bool;
    // Business logic methods
    public function registerCompany(array $companyData): Company;
    public function deactivateCompany(int $companyId): bool;
}
