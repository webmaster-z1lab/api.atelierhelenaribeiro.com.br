<?php

namespace Modules\Stock\Http\Requests;

class ProductUpdateRequest extends ProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'size'                   => 'bail|required|string',
            'color'                  => 'bail|required|string',
            'images'                 => 'bail|sometimes|required|array|min:1',
            'images.*.path'          => 'bail|required|string',
            'images.*.name'          => 'bail|required|string',
            'images.*.extension'     => 'bail|required|string',
            'images.*.size_in_bytes' => 'bail|required|integer|min:1',
            'price'                  => 'bail|nullable|numeric|min:0.1',
        ];
    }
}
