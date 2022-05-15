<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Exceptions\FormValidationException;
use Illuminate\Contracts\Validation\Validator;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new FormValidationException($validator);
    }
}
