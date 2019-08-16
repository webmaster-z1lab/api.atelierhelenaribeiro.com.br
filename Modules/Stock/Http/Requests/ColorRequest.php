<?php

namespace Modules\Stock\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ColorRequest extends FormRequest
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
            'name' => $this->getColorRules(),
        ];
    }

    /**
     * @param  $ignore
     *
     * @return array
     */
    protected function getColorRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'string',
            Rule::unique('colors', 'name')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
