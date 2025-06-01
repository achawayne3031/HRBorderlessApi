<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Company //
use App\Interfaces\Repositories\CompanyRepositoryInterface;
use App\Interfaces\Services\CompanyServiceInterface;
use App\Repositories\Eloquent\CompanyRepository;
use App\Services\CompanyService;

// Candidate //
use App\Services\CandidateService;
use App\Interfaces\Services\CandidateServiceInterface;
use App\Repositories\Eloquent\CandidateRepository;
use App\Interfaces\Repositories\CandidateRepositoryInterface;









class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //

        // Company //
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);


        // Candidate //
        $this->app->bind(CandidateServiceInterface::class, CandidateService::class);
        $this->app->bind(CandidateRepositoryInterface::class, CandidateRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
