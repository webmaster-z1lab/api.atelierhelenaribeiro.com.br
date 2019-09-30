<?php

namespace Modules\Paycheck\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class PaycheckResource
 *
 * @package Modules\Paycheck\Http\Resources
 *
 * @property-read \Modules\Paycheck\Models\Paycheck $resource
 */
class PaycheckResource extends Resource
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
            'id'          => $this->resource->id,
            'holder'      => $this->resource->holder,
            'document'    => $this->resource->document,
            'bank'        => $this->resource->bank,
            'number'      => $this->resource->number,
            'pay_date'    => $this->resource->pay_date->format('d/m/Y'),
            'value'       => $this->resource->value_float,
            'output'      => $this->mergeWhen(!is_null($this->resource->output), $this->resource->output),
            'received_at' => $this->resource->received_at,
            'received_by' => $this->resource->received_by,
        ];
    }
}
