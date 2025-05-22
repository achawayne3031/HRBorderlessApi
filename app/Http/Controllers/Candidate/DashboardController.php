<?php

namespace App\Http\Controllers\Candidate;

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

            $user = Auth::user('candidate');

            $jobs_applied = DBHelpers::with_where_query_filter(JobApplication::class, ['company', 'job'], ['candidate_id' => $user->id]);

            return ResponseHelper::response_data(true, 200, 'Candidate Dashboard data', $jobs_applied, null, []);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseHelper::response_data(false, 401, 'Something went wrong, cant fetch candidate dashboard data', null, null, [
                'debug_message' =>  $th->getMessage(),
                'error_line' =>  $th->getLine()
            ]);
        }
    }
}
