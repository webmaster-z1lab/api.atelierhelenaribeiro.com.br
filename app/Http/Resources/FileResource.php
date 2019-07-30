<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class File
 *
 * @package App\Http\Resources
 *
 * @property-read \App\Models\File resource
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'          => $this->resource->name,
            'extension'     => $this->resource->extension,
            'size_in_bytes' => $this->resource->size_in_bytes,
            'size'          => $this->resource->size,
            'url'           => $this->resource->url,
            'icon'          => $this->resource->icon,
        ];
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
