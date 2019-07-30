<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Notification
 *
 * @package App\Http\Resources
 *
 * @property-read \Modules\User\Models\DatabaseNotification resource
 */
class NotificationResource extends JsonResource
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
        return array_merge($this->resource->data, ['new' => $this->resource->read_at === NULL]);
    }
}
