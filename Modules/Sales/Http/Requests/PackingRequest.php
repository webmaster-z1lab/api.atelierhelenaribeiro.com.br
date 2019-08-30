<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Stock\Models\ProductStatus;

class PackingRequest extends FormRequest
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
            'seller'     => [
                'bail',
                'required',
                Rule::exists('users', '_id')->whereIn('type', [EmployeeTypes::TYPE_ADMIN, EmployeeTypes::TYPE_SELLER]),
            ],
            'products'   => 'bail|required|array|min:1',
            'products.*.reference' => [
                'bail',
                'required',
                'distinct',
                Rule::exists('products', 'reference')->where('status', ProductStatus::AVAILABLE_STATUS),
            ],
            'products.*.amount' => 'bail|required|integer|min:1'
        ];
    }
}
