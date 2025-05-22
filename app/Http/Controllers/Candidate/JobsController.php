<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Validations\CompanyJobValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\CompanyJobs;
use App\Models\JobApplication;

use App\Helpers\DBHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\ProcessJobApplicationFileUpload;



class JobsController extends Controller
{
    //



    public function apply(Request $request, $id)
    {


        if ($request->isMethod('post')) {
            $validate = CompanyJobValidator::validate_rules($request, 'apply');
            if (!$validate->fails() && $validate->validated()) {

                $user = Auth::user('candidate');
                $current_job = CompanyJobs::find($id);

                if (!$current_job) {
                    return ResponseHelper::response_data(false, 401, 'Job not found', null, null, []);
                }

                if (DBHelpers::exists(JobApplication::class, ['job_id' => $id, 'candidate_id' => $user->id])) {
                    return ResponseHelper::response_data(false, 401, 'You have applied for this job already', null, null, []);
                }

                $full_resume_path = '';
                $full_cover_letter_path = '';

                $data = [
                    'job_id' => $id,
                    'candidate_id' => $user->id,
                    'company_id' => $current_job->company_id,
                ];


                try {
                    //code...

                    $job_application = DBHelpers::create_query(JobApplication::class, $data);
                    $temp_resume_path = null;
                    $temp_cover_letter_path = null;


                    if ($request->hasFile('resume')) {
                        $temp_resume_path = $request->file('resume')->store('temp');
                    }
                    if ($request->hasFile('cover_letter')) {
                        $temp_cover_letter_path = $request->file('cover_letter')->store('temp');
                    }

                    // Dispatch the job to process files in the background
                    ProcessJobApplicationFileUpload::dispatch($job_application, $temp_resume_path, $temp_cover_letter_path);

                    $current_job->increment('total_applied');

                    return ResponseHelper::response_data(true, 200, 'Job applied successfully', null, null, []);
                } catch (\Throwable $th) {
                    //throw $th;
                    return ResponseHelper::response_data(false, 401, 'Something went wrong, job not applied', null, null, [
                        'debug_message' =>  $th->getMessage(),
                        'error_line' =>  $th->getLine()
                    ]);
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['resume', 'cover_letter'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::response_data(false, 401, 'Validation error', $error_res, null, []);
            }
        } else {
            return ResponseHelper::response_data(false, 401, 'HTTP Request not allowed', null, null, []);
        }
    }
}
