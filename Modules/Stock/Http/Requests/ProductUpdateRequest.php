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
        $rules = [
            'size'  => 'bail|required|string',
            'color' => 'bail|required|string',
            'price' => 'bail|nullable|numeric|min:0.1',
        ];

        return $this->mergeRules($rules, $this->getImagesRules());
    }
}
