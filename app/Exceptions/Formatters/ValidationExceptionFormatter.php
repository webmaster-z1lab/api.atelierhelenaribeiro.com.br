<?php


namespace App\Exceptions\Formatters;

use App\Exceptions\ErrorObject;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Optimus\Heimdal\Formatters\BaseFormatter;

class ValidationExceptionFormatter extends BaseFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        if ($e instanceof ValidationException)
        {
            $errors = $e->errors();
            $error = new ErrorObject($errors, 422, $errors);
            $response->setStatusCode(422);
            $response->setData($error->toArray());
        }
    }
}
