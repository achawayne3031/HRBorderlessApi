<?php



namespace App\Validations;

use App\Helpers\Func;

class CompanyJobValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'title' => 'required',
                'description' => 'required',
                'location' => 'required',
                'salary_range' => 'required',
                'is_remote' => 'required',
            ],

            'apply' => [
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:2048',

            ],

        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
