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
        $product = \Route::current()->parameter('product');

        return [
            'serial'   => $this->getSerialRules($product),
            'size'     => 'bail|required|string',
            'color'    => 'bail|required|string',
            'template' => 'bail|required|exists:templates,_id',
            'images'   => 'bail|nullable|array',
            'images.*' => 'bail|required|image',
            'price'    => 'bail|nullable|integer|min:0',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }
}
