<?php

namespace App\Application\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FormValidationException extends ValidationException
{
    public $status = ResponseAlias::HTTP_OK;

    /**
     * @param Validator $validator
     * @param ResponseAlias $response
     * @param string $errorBag
     */
    public function __construct(public $validator, $response = null, string $errorBag = 'default')
    {
        parent::__construct($validator);

        $this->response = $response;
        $this->errorBag = $errorBag;
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
