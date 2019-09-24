<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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

        return [
            'payment_methods'          => 'bail|nullable|array',
            'payment_methods.*.method' => "bail|required|in:$methods",
            'payment_methods.*.value'  => 'bail|required|numeric|min:0.01',
        ];
    }

    public function attributes(): array
    {
        return [
            'payment_methods'          => 'métodos de pagamento',
            'payment_methods.*.method' => 'método de pagamento',
            'payment_methods.*.value'  => 'valor pago',
        ];
    }
}
