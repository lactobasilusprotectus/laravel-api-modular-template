<?php

namespace App\Application\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FormValidationException extends ValidationException
{
    public function __construct(public $validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator);

        $this->response = $response;
        $this->errorBag = $errorBag;
        $this->validator = $validator;
    }

    public function render(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'message' => 'The given data was invalid.',
                'errors'  => $this->validator->errors()->messages(),
            ],
            'meta' => ['timestamp' => intdiv((int)now()->format('Uu'), 1000)],
        ], $this->status);
    }
}
