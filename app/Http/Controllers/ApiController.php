<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 22:40
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function noContentResponse(): JsonResponse
    {
        return new JsonResponse(NULL, 204);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notModifiedResponse(): JsonResponse
    {
        return new JsonResponse(NULL, 304);
    }

    /**
     * @param $model
     *
     * @return bool
     */
    protected function ETagNotChanged($model): bool
    {
        $ETags = Request::getETags();

        if (!empty($ETags)) return md5($model->updated_at) === $ETags[0];

        return FALSE;
    }
}
