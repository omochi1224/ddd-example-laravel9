<?php

declare(strict_types=1);

namespace Base\Request;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiFormRequestTrait
{
    /**
     * @return never
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [];
        $response['errors'] = [];

        $messages = $validator->errors()->toArray();
        $codes = $validator->errors()->all();

        foreach ($messages as $key => $message) {
            $response['errors'][$key] = $message;
        }

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}
