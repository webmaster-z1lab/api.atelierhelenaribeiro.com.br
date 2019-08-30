<?php

namespace Modules\Stock\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => $this->getNameRules(),
        ];
    }

    protected function getNameRules(string $ignore = NULL): array
    {
        return [
            'bail',
            'required',
            Rule::unique('sizes', 'name')->ignore($ignore),
        ];
    }
}
