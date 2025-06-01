<?php

namespace App\Services;

use App\Interfaces\Repositories\CompanyRepositoryInterface;
use App\Interfaces\Services\CompanyServiceInterface;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash; // Example for password hashing

class CompanyService implements CompanyServiceInterface
{
    protected $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function fetchAllCompanies(): Collection
    {
        return $this->companyRepository->getAllCompanies();
    }

    public function findCompanyById(int $userId): ?Company
    {
        return $this->companyRepository->getCompanyById($userId);
    }

    public function createNewCompany(array $data): Company
    {
        // Example of business logic in service
        $data['password'] = Hash::make($data['password']);
        return $this->companyRepository->createCompany($data);
    }

    public function updateExistingCompany(int $userId, array $data): bool
    {
        // Example of business logic in service
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->companyRepository->updateCompany($userId, $data);
    }

    public function deleteExistingCompany(int $userId): bool
    {
        return $this->companyRepository->deleteCompany($userId);
    }

    public function registerCompany(array $userData): Company
    {
        // More complex registration logic could go here
        return $this->createNewCompany($userData);
    }

    public function deactivateCompany(int $userId): bool
    {
        // Specific business logic for deactivating a user
        $user = $this->findCompanyById($userId);
        if ($user) {
            // For example, set a 'status' field to inactive
            return $this->updateExistingCompany($userId, ['status' => 'inactive']);
        }
        return false;
    }
}
