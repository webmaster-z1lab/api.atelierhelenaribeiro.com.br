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
        $template = \Route::current()->parameter('template');

        return [
            'reference' => $this->getReferenceRules($template),
            'images'    => 'bail|nullable|array',
            'images.*'  => 'bail|required|image',
            'price'     => 'bail|required|integer|min:0',
        ];
    }
}
