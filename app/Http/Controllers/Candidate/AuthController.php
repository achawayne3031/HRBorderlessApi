<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use App\Validations\AuthValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\Candidate;
use App\Helpers\DBHelpers;
use App\Helpers\Func;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidator::validate_rules($request, 'login');
            if (!$validate->fails() && $validate->validated()) {

                try {
                    //code...
                    $user = DBHelpers::query_filter_first(Candidate::class, ['email' => $request->email]);
                    if (!$user || !Hash::check($request->password, $user->password)) {
                        return ResponseHelper::response_data(false, 200, 'Invalid login credentials', null, null, []);
                    } else {
                        $token =  $user->createToken(env('TOKEN_KEY'))->accessToken;
                        return ResponseHelper::response_data(true, 200, 'Login Successful', $user, $token, []);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    return ResponseHelper::response_data(false, 401, 'Server Error, Login was not successfully', null, null, [
                        'debug_message' =>  $th->getMessage(),
                        'error_line' =>  $th->getLine()
                    ]);
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::response_data(true, 401, 'Validation error', $error_res, null, []);
            }
        } else {
            return ResponseHelper::response_data(false, 401, 'Server Error, HTTP Request not allowed', null, null, []);
        }
    }


    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidator::validate_rules($request, 'register_candidate');
            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ];


                try {
                    //code...
                    /// Save  /////
                    DBHelpers::create_query(Candidate::class, $data);

                    return ResponseHelper::response_data(true, 200, 'Register was successful', null, null, []);
                } catch (\Throwable $th) {
                    //throw $th;
                    return ResponseHelper::response_data(false, 401, 'Something went wrong, registration not successfully', null, null, [
                        'debug_message' =>  $th->getMessage(),
                        'error_line' =>  $th->getLine()
                    ]);
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['name', 'email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);


                return ResponseHelper::response_data(false, 401, 'validation error', $error_res, null, []);
            }
        } else {

            return ResponseHelper::response_data(false, 401, 'Server Error, HTTP Request not allowed', null, null, []);
        }
    }
}
