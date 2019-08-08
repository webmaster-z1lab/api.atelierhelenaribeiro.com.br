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
        return [
            'price'    => 'bail|required|integer|min:1',
            'is_active' => 'bail|required|bool_custom',
            'images'   => 'bail|sometimes|required|array|min:1',
            'images.*' => 'bail|sometimes|required|array',
        ];
    }
}
