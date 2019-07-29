<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 16:07
 */

namespace App\Exceptions\Formatters;


use Exception;
use Illuminate\Http\JsonResponse;

class HttpExceptionFormatter extends ExceptionFormatter
{
    /**
     * @param  \Illuminate\Http\JsonResponse  $response
     * @param  \Exception                     $e
     * @param  array                          $reporterResponses
     */
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        parent::format($response, $e, $reporterResponses);
        if (count($headers = $e->getHeaders())) {
            $response->headers->add($headers);
        }
        $response->setStatusCode($e->getStatusCode());
    }

}
