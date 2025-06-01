<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CandidateRepositoryInterface;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;

class CandidateRepository implements CandidateRepositoryInterface
{
    public function getAllCandidate(): Collection
    {
        return Candidate::all();
    }



    public function getCandidateById(int $candidateId): ?Candidate
    {
        return Candidate::find($candidateId);
    }

    public function createCandidate(array $data): Candidate
    {
        return Candidate::create($data);
    }

    public function updateCandidate(int $candidateId, array $data): bool
    {
        $user = Candidate::find($candidateId);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    public function deleteCandidate(int $candidateId): bool
    {
        $user = Candidate::find($candidateId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
