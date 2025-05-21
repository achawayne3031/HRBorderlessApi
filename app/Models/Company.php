<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Company extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'company';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
    ];


    public function jobs()
    {
        return $this->hasMany(CompanyJobs::class, 'id', 'company_id');
    }
}
