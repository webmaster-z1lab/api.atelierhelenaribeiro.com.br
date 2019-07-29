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
use Optimus\Heimdal\Formatters\BaseFormatter;
use App\Exceptions\ErrorObject;

class ExceptionFormatter extends BaseFormatter
{
    /**
     * @param JsonResponse $response
     * @param Exception    $e
     * @param array        $reporterResponses
     */
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        $response->setStatusCode(500);
        $meta = [];
        if ($this->debug) {
            $meta = [
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace(),
            ];
        }
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $e->getCode();
        $json = new ErrorObject($e->getMessage(), $code, $meta);
        $response->setData($json->toArray());
    }
}
