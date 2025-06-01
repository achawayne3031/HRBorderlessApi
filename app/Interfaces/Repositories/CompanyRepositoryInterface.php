<?php

namespace App\Interfaces\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface
{
    public function getAllCompanies(): Collection;
    public function getCompanyById(int $CompanyId): ?Company;
    public function createCompany(array $data): Company;
    public function updateCompany(int $CompanyId, array $data): bool;
    public function deleteCompany(int $CompanyId): bool;
}
