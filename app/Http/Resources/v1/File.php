<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class File
 *
 * @package App\Http\Resources\v1
 *
 * @property-read \App\Models\File resource
 */
class File extends JsonResource
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
            'id'         => $this->resource->id,
            'type'       => 'files',
            'attributes' => [
                'name'          => $this->resource->name,
                'extension'     => $this->resource->extension,
                'size_in_bytes' => $this->resource->size_in_bytes,
                'size'          => $this->resource->size,
                'url'           => $this->resource->url,
                'icon'          => $this->resource->icon,
            ],
        ];
    }
}
