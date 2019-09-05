<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitRequest extends FormRequest
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
            'customer'    => $this->getCustomerRules(),
            'date'        => 'bail|required|date_format:d/m/Y|before_or_equal:today',
            'annotations' => 'bail|nullable|string|min:3',
        ];

    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'customer'    => 'cliente',
            'date'        => 'data',
            'annotations' => 'anotações',
        ];
    }

    /**
     * @return array
     */
    protected function getCustomerRules(): array
    {
        return [
            'bail',
            'required',
            Rule::exists('customers', '_id')->where(function ($query) {
                $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
