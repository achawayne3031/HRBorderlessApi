<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Validations\CompanyJobValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\CompanyJobs;
use App\Helpers\DBHelpers;
use Carbon\Carbon;

class CompanyJobsController extends Controller
{
    //

    public function update(Request $request, $id)
    {
        if ($request->isMethod('put')) {

            if (!CompanyJobs::find($id)) {
                return ResponseHelper::response_data(false, 404, 'Job not found', null, null, []);
            }


            try {
                $job = DBHelpers::query_filter_first(CompanyJobs::class, ['id' => $id]);

                $update_data = [
                    'title' => $request->title ? $request->title : $job->title,
                    'description' => $request->description ? $request->description : $job->description,
                    'location' => $request->location ? $request->location : $job->location,
                    'salary_range' => $request->salary_range ? $request->salary_range : $job->salary_range,
                    'is_remote' => $request->is_remote ? $request->is_remote : $job->is_remote,
                ];

                DBHelpers::update_query_v3(CompanyJobs::class, $update_data, ['id' => $id]);

                return ResponseHelper::response_data(true, 200, 'Updated successfully', null, null, []);
            } catch (\Throwable $th) {
                return ResponseHelper::response_data(false, 401, 'Server Error, Update was not successfuly', null, null, [
                    'debug_message' =>  $th->getMessage(),
                    'error_line' =>  $th->getLine()
                ]);
            }
        } else {
            return ResponseHelper::response_data(false, 401, 'HTTP Request not allowed', null, null, []);
        }
    }

    public function delete($id)
    {
        if (!CompanyJobs::find($id)) {
            return ResponseHelper::response_data(false, 401, 'Job not found', null, null, []);
        }

        $user = Auth::user('company');

        try {
            //code...
            if (!DBHelpers::exists(CompanyJobs::class, ['company_id' => $user->id, 'id' => $id])) {
                return ResponseHelper::response_data(false, 401, 'Job not in your job collection', null, null, []);
            }

            DBHelpers::soft_delete(CompanyJobs::class, $id);

            return ResponseHelper::response_data(true, 200, 'Job data deleted successfully', null, null, []);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseHelper::response_data(false, 401, 'Something went wrong, Job not deleted successfully', null, null, [
                'debug_message' =>  $th->getMessage(),
                'error_line' =>  $th->getLine()
            ]);
        }
    }


    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $validate = CompanyJobValidator::validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {

                $user = Auth::user('company');

                $data = [
                    'company_id' => $user->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'location' => $request->location,
                    'salary_range' => $request->salary_range,
                    'is_remote' => $request->is_remote,
                    'published_at' => Carbon::now()
                ];


                try {
                    //code...
                    /// Save  /////
                    DBHelpers::create_query(CompanyJobs::class, $data);

                    return ResponseHelper::response_data(true, 200, 'Job Creation was successful', null, null, []);
                } catch (\Throwable $th) {

                    return ResponseHelper::response_data(false, 401, 'Server Error, Job creation was not successfully', null, null, [
                        'debug_message' =>  $th->getMessage(),
                        'error_line' =>  $th->getLine()
                    ]);
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['title', 'description', 'location', 'salary_range', 'is_remote'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::response_data(false, 401, 'Validation error', $error_res, null, []);
            }
        } else {
            return ResponseHelper::response_data(false, 401, 'HTTP Request not allowed', null, null, []);
        }
    }
}
