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
            'size'              => $this->getSizeRules(),
            'color'             => 'bail|required|string',
            'price'             => 'bail|nullable|numeric|min:0.1',
            'template_images'   => 'bail|sometimes|array|min:1',
            'template_images.*' => 'bail|required|exists:images,_id',
        ];

        return $this->mergeRules($rules, $this->getImagesRules());
    }
}
