<?php

namespace Modules\Sales\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ProductResource
 *
 * @package Modules\Sales\Http\Resources
 */
class ProductResource extends Resource
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
        if (!\Str::endsWith($request->route()->getName(), 'status')) {
            return [
                'reference' => $this->resource->_id,
                'thumbnail' => $this->resource->thumbnail,
                'size'      => $this->resource->size,
                'color'     => $this->resource->color,
                'price'     => floatval($this->resource->price / 100.0),
                'amount'    => $this->resource->amount,
            ];
        } else {
            return [
                'reference' => $this->resource->_id->reference,
                'date'      => Carbon::createFromTimestamp($this->resource->_id->date->toDateTime()->getTimestamp())->toW3cString(),
                'thumbnail' => $this->resource->thumbnail,
                'size'      => $this->resource->size,
                'color'     => $this->resource->color,
                'price'     => floatval($this->resource->price / 100.0),
                'amount'    => $this->resource->amount,
            ];
        }
    }
}
