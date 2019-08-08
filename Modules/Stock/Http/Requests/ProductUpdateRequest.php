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
            'template' => 'bail|required|exists:templates,_id',
            'images'   => 'bail|sometimes|array|min:1',
            'images.*' => 'bail|sometimes|array',
            'price'    => 'bail|nullable|integer|min:1',
        ];
    }
}
