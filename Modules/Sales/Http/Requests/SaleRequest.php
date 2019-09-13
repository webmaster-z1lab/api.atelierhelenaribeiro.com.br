<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Sales\Models\PaymentMethods;

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
        $methods = implode(',', [PaymentMethods::MONEY, PaymentMethods::CHECK, PaymentMethods::CREDIT_CARD]);

        return [
            'visit'                    => $this->getVisitRules(),
            'discount'                 => 'bail|required|numeric|min:0',
            'products'                 => 'bail|required|array|min:1',
            'products.*.reference'     => 'bail|required|distinct|exists:products,reference',
            'products.*.amount'        => 'bail|required|integer|min:1',
            'payment_methods'          => 'bail|required|array|min:1',
            'payment_methods.*.method' => "bail|required|in:$methods",
            'payment_methods.*.value'  => 'bail|required|numeric|min:0.01',
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
