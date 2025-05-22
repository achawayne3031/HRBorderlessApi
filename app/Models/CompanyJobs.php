<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CompanyJobs extends Model
{
    use HasFactory, SoftDeletes;

    use HasFactory;

    protected $table = 'company_jobs';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'salary_range',
        'is_remote',
        'published_at',
        'total_applied'
    ];


    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
