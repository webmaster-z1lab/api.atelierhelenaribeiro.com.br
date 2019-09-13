<?php

namespace Modules\Sales\Http\Requests;

class PayrollUpdateRequest extends PayrollRequest
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
