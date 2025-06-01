<?php

namespace App\Interfaces\Repositories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;

interface CandidateRepositoryInterface
{
    public function getAllCandidate(): Collection;
    public function getCandidateById(int $CandidateId): ?Candidate;
    public function createCandidate(array $data): Candidate;
    public function updateCandidate(int $CandidateId, array $data): bool;
    public function deleteCandidate(int $CandidateId): bool;
}
