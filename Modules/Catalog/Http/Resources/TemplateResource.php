<?php

namespace Modules\Catalog\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TemplateResource
 *
 * @package Modules\Catalog\Http\Resources
 *
 * @property-read \App\Models\File resource
 */
class TemplateResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * @param  \Illuminate\Http\Request       $request
     * @param  \Illuminate\Http\JsonResponse  $response
     */
    public function withResponse($request, $response)
    {
        $response->header('ETag', md5($this->resource->updated_at));
    }
}
