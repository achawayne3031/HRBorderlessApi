<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    protected $fillable = [
        'job_id',
        'candidate_id',
        'company_id',
        'resume_url',
        'cover_letter_url',
        'status',
    ];



    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function job()
    {
        return $this->hasOne(CompanyJobs::class, 'id', 'job_id');
    }
}
