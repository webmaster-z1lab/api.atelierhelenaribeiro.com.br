<?php

namespace Modules\Sales\Http\Requests;

class SaleUpdateRequest extends SaleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        unset($rules['visit']);

        return $rules;
    }
}
