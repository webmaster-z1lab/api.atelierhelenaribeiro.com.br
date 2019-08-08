<?php

namespace Modules\Catalog\Http\Requests;

class TemplateUpdateRequest extends TemplateRequest
{
    public function rules()
    {
        return [
            'price'    => 'bail|required|integer|min:1',
            'images'   => 'bail|required|array|min:1',
            'images.*' => 'bail|required|array',
        ];
    }
}
