<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Employee\Models\EmployeeTypes;

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
        if (\Auth::user()->type === EmployeeTypes::TYPE_ADMIN) {
            return [
                'seller'      => [
                    'bail',
                    'required',
                    Rule::exists('users', '_id')->whereIn('type', [EmployeeTypes::TYPE_ADMIN, EmployeeTypes::TYPE_SELLER]),
                ],
                'customer'    => $this->getCustomerRules(),
                'date'        => 'bail|required|date_format:d/m/Y|before_or_equal:today',
                'annotations' => 'bail|nullable|string|min:3',
            ];
        }

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
            'seller'      => 'vendedor',
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
