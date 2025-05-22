<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use App\Models\CompanyJobs;
use App\Models\JobApplication;
use App\Helpers\DBHelpers;

class DashboardController extends Controller
{
    //


    public function dashboard()
    {

        try {

            $user = Auth::user('company');

            $total_jobs_posted = DBHelpers::count(CompanyJobs::class, ['company_id' => $user->id]);
            $total_application_received = DBHelpers::count(JobApplication::class, ['company_id' => $user->id]);;

            $res = [
                'total_jobs_posted' => $total_jobs_posted,
                'total_application_received' => $total_application_received
            ];


            return ResponseHelper::response_data(true, 200, 'Company Dashboard data', $res, null, []);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseHelper::response_data(false, 401, 'Something went wrong, cant fetch company dashboard data', null, null, [
                'debug_message' =>  $th->getMessage(),
                'error_line' =>  $th->getLine()
            ]);
        }
    }
}
