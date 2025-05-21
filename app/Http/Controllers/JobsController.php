<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Validations\CompanyJobValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\CompanyJobs;
use App\Helpers\DBHelpers;
use Carbon\Carbon;


class JobsController extends Controller
{
    //



    public function jobs(Request $request)
    {

        $location = $request->query('location') != '' ? $request->query('location') : false;
        $is_remote = $request->query('is_remote') != '' ? $request->query('is_remote') : false;
        $search = $request->query('search') != '' ? $request->query('search') : false;

        try {
            //code...

            $all_jobs = DBHelpers::query_paginate(CompanyJobs::class, ['company'], $location, $is_remote, $search, 400);

            return ResponseHelper::response_data(true, 200, 'Job fetch was successful', $all_jobs, null, []);
        } catch (\Throwable $th) {
            //throw $th;

            return ResponseHelper::response_data(false, 401, 'Something went wrong, Jobs not fetched', null, null, [
                'debug_message' =>  $th->getMessage(),
                'error_line' =>  $th->getLine()
            ]);
        }
    }
}
