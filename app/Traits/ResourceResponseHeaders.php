<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 02/08/2019
 * Time: 18:41
 */

namespace App\Traits;

trait ResourceResponseHeaders
{
    /**
     * @param  \Illuminate\Http\Request       $request
     * @param  \Illuminate\Http\JsonResponse  $response
     */
    public function withResponse($request, $response)
    {
        $response->header('ETag', md5($this->resource->updated_at));
        //$response->header('Content-Length' , mb_strlen($response->getOriginalContent(), '8bit'));
        $response->header('Cache-Control', 'private, must-revalidate, max-age:3600');
    }

}
