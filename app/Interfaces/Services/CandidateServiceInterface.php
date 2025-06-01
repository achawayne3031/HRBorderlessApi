<?php

namespace App\Interfaces\Services;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;

interface CandidateServiceInterface
{
    public function fetchAllCandidate(): Collection;
    public function findCandidateById(int $userId): ?Candidate;
    public function createNewCandidate(array $data): Candidate;
    public function updateExistingCandidate(int $candidateId, array $data): bool;
    public function deleteExistingCandidate(int $candidateId): bool;
    // Business logic methods
    public function registerCandidate(array $candidateData): Candidate;
    public function deactivateCandidate(int $candidateId): bool;
}
