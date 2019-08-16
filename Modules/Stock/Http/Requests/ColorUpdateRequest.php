<?php

namespace Modules\Stock\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorUpdateRequest extends ColorRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $color = \Route::current()->parameter('color');

        return [
            'name' => $this->getColorRules($color),
        ];
    }
}
