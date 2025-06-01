<?php

namespace App\Services;

use App\Interfaces\Repositories\CandidateRepositoryInterface;
use App\Interfaces\Services\CandidateServiceInterface;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;


class CandidateService implements CandidateServiceInterface
{
    protected $candidateRepository;

    public function __construct(CandidateRepositoryInterface $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function fetchAllCandidate(): Collection
    {
        return $this->candidateRepository->getAllCandidate();
    }

    public function findCandidateById(int $userId): ?Candidate
    {
        return $this->candidateRepository->getCandidateById($userId);
    }

    public function createNewCandidate(array $data): Candidate
    {
        // Example of business logic in service
        $data['password'] = Hash::make($data['password']);
        return $this->candidateRepository->createCandidate($data);
    }

    public function updateExistingCandidate(int $userId, array $data): bool
    {
        // Example of business logic in service
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->candidateRepository->updateCandidate($userId, $data);
    }

    public function deleteExistingCandidate(int $userId): bool
    {
        return $this->candidateRepository->deleteCandidate($userId);
    }

    public function registerCandidate(array $userData): Candidate
    {
        // More complex registration logic could go here
        return $this->createNewCandidate($userData);
    }

    public function deactivateCandidate(int $userId): bool
    {
        // Specific business logic for deactivating a user
        $user = $this->findCandidateById($userId);
        if ($user) {
            // For example, set a 'status' field to inactive
            return $this->updateExistingCandidate($userId, ['status' => 'inactive']);
        }
        return false;
    }
}
