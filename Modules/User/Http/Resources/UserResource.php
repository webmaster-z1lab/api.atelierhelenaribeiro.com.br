<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class User
 *
 * @package Modules\User\Http\Resources
 *
 * @property-read \Modules\User\Models\User $resource
 */
class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'type'       => $this->resource->type,
            'name'       => $this->resource->name,
            'email'      => $this->resource->email,
            'avatar'     => NULL,
            $this->mergeWhen($this->resource->id === \Auth::id(), function () {
                return ['api_token' => $this->resource->api_token];
            }),
            'created_at' => $this->resource->created_at->toW3cString(),
            'updated_at' => $this->resource->updated_at->toW3cString(),
        ];
    }
}
