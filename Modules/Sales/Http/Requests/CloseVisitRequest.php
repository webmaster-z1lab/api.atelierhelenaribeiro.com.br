<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Sales\Models\PaymentMethods;

class CloseVisitRequest extends FormRequest
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
        $methods = implode(',', [PaymentMethods::MONEY, PaymentMethods::PAYCHECK, PaymentMethods::CREDIT_CARD, PaymentMethods::BOLETO]);

        $visit = $this->route('visit');

        return [
            'discount'                 => 'bail|required|numeric|min:0',
            'payment_methods'          => $this->getPaymentMethodsRules(),
            'payment_methods.*.method' => "bail|required|in:$methods",
            'payment_methods.*.value'  => 'bail|required|numeric|min:0.01',
        ];
    }

    public function attributes(): array
    {
        return [
            'discount'                 => 'desconto',
            'payment_methods'          => 'métodos de pagamento',
            'payment_methods.*.method' => 'método de pagamento',
            'payment_methods.*.value'  => 'valor pago',
        ];
    }

    public function getPaymentMethodsRules(): array
    {
        /** @var \Modules\Sales\Models\Visit $visit */
        $visit = $this->route('visit');

        $discount = (int) ($this->get('discount') * 100);

        return [
            'bail',
            Rule::requiredIf(($visit->total_price - $discount - ($visit->customer->credit ?? 0)) > 0),
            'array',
        ];
    }
}
