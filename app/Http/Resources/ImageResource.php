<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ImageResource
 *
 * @package App\Http\Resources
 *
 * @property-read \App\Models\Image $resource
 */
class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
