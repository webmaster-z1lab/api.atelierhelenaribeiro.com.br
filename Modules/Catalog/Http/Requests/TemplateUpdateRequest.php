<?php

namespace Modules\Catalog\Http\Requests;

class TemplateUpdateRequest extends TemplateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'price'     => 'bail|required|numeric|min:0.1',
            'is_active' => 'bail|required|bool_custom',
        ];

        return $this->mergeRules($rules, $this->getImagesRules());
    }
}
