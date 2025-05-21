<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Validations\AuthValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\Company;
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
                $user = DBHelpers::query_filter_first(Company::class, ['email' => $request->email]);
                if (!$user || !Hash::check($request->password, $user->password)) {

                    return ResponseHelper::response_data(true, 401, 'Invalid login credentials', null, null, []);
                } else {
                    $token =  $user->createToken(env('TOKEN_KEY'))->accessToken;

                    return ResponseHelper::response_data(true, 200, 'Login successful', $user, $token, []);
                }
            } else {
                $errors = json_decode($validate->errors());
                $props = ['email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);

                return ResponseHelper::response_data(false, 401, 'validation error', $error_res, null, []);
            }
        } else {
            return ResponseHelper::response_data(false, 401, 'HTTP Request not allowed', null, null, []);
        }
    }


    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidator::validate_rules($request, 'register_company');
            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ];


                try {

                    /// Save  /////
                    DBHelpers::create_query(Company::class, $data);

                    return ResponseHelper::response_data(true, 200, 'Register was successful', null, null, []);
                } catch (\Throwable $th) {

                    return ResponseHelper::response_data(false, 401, 'Server Error, Registration was not successfuly', null, null, [
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
            return ResponseHelper::response_data(false, 401, 'HTTP Request not allowed', null, null, []);
        }
    }
}
