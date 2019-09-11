<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'visit'                => $this->getVisitRules(),
            'discount'             => 'bail|required|numeric|min:0',
            'products'             => 'bail|required|array|min:1',
            'products.*.reference' => 'bail|required|distinct|exists:products,reference',
            'products.*.amount'    => 'bail|required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'visit'                => 'visita',
            'discount'             => 'desconto',
            'products'             => 'produtos',
            'products.*.reference' => 'referência do produto',
            'products.*.amount'    => 'quantidade do produto',
        ];
    }

    /**
     * @return array
     */
    protected function getVisitRules(): array
    {
        return [
            'bail',
            'required',
            Rule::exists('visits', '_id')->where(function ($query) {
                $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}