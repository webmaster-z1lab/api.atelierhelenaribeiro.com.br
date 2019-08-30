<?php

namespace Modules\Stock\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeUpdateRequest extends SizeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => $this->getNameRules($this->route('size'))
        ];
    }
}
