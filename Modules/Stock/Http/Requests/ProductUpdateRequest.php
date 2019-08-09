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
            'size'     => 'bail|required|string',
            'color'    => 'bail|required|string',
            'images'   => 'bail|sometimes|required|array|min:1',
            'images.*' => 'bail|sometimes|required|array',
            'price'    => 'bail|nullable|integer|min:1',
        ];
    }
}
