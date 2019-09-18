<?php

namespace Modules\Stock\Http\Resources;

use App\Traits\FileUrl;
use Illuminate\Http\Resources\Json\Resource;

class BasicProductResource extends Resource
{
    use FileUrl;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        $price = collect($this->resource->prices->bsonSerialize())->sortByDesc('started_at')->first();

        return [
            'id'          => (string) $this->resource->_id,
            'barcode'     => $this->resource->barcode,
            'thumbnail'   => $this->getFileUrl(),
            'size'        => $this->resource->size,
            'color'       => $this->resource->color,
            'template_id' => $this->resource->template_id,
            'price'       => floatval($price->price / 100),
            'created_at'  => $this->resource->created_at->toDateTime()->format(\DateTime::W3C),
            'updated_at'  => $this->resource->updated_at->toDateTime()->format(\DateTime::W3C),
        ];
    }

    /**
     * @return \Illuminate\Config\Repository|mixed|string
     */
    private function getFileUrl(): string
    {
        return (isset($this->resource->thumbnail))
            ? $this->fileUrl($this->resource->thumbnail)
            : config('image.sizes.thumbnail.placeholder');
    }
}
