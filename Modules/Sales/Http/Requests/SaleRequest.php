<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'products'                 => 'bail|required|array|min:1',
            'products.*.reference'     => 'bail|required|distinct|exists:products,reference',
            'products.*.amount'        => 'bail|required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'products'                 => 'produtos',
            'products.*.reference'     => 'referência do produto',
            'products.*.amount'        => 'quantidade do produto',
        ];
    }
}
