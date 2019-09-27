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

        return [
            'discount'                      => 'bail|required|numeric|min:0',
            'payment_methods'               => $this->getPaymentMethodsRules(),
            'payment_methods.*.method'      => "bail|required|distinct|in:$methods",
            'payment_methods.*.instalments' => $this->getInstalmentsRules(),
            'payment_methods.*.value'       => 'bail|required|numeric|min:0.01',
            'paychecks'                     => $this->getPaychecksRules(),
            'paychecks.*.holder'            => 'bail|required|string|min:3',
            'paychecks.*.document'          => 'bail|nullable|document',
            'paychecks.*.bank'              => 'bail|required|string',
            'paychecks.*.number'            => 'bail|required|digits_between:1,99',
            'paychecks.*.pay_date'          => 'bail|required|date_format:d/m/Y',
            'paychecks.*.value'             => 'bail|required|numeric|min:0.01',
        ];
    }

    public function attributes(): array
    {
        return [
            'discount'                       => 'desconto',
            'payment_methods'                => 'métodos de pagamento',
            'payment_methods.*.method'       => 'método de pagamento',
            'payment_methods.*.installments' => 'número de parcelas',
            'payment_methods.*.value'        => 'valor pago',
            'paychecks'                      => 'cheques',
            'paychecks.*.holder'             => 'titular do cheque',
            'paychecks.*.document'           => 'documento do titular do cheque',
            'paychecks.*.bank'               => 'banco do cheque',
            'paychecks.*.number'             => 'número do cheque',
            'paychecks.*.pay_date'           => 'data do cheque',
            'paychecks.*.value'              => 'valor do cheque',
        ];
    }

    /**
     * @return array
     */
    protected function getPaymentMethodsRules(): array
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

    /**
     * @return array
     */
    protected function getInstalmentsRules(): array
    {
        return [
            'bail',
            function (string $attribute, $value, \Closure $fail) {
                $method = str_replace('installments', 'method', $attribute);
                if ($this->get($method) !== PaymentMethods::MONEY && !filled($value)) {
                    $fail(trans('validation.required', ['attribute' => $this->attributes()['payment_methods.*.installments']]));
                }
            },
            'integer',
            'min:1',
        ];
    }

    /**
     * @return array
     */
    protected function getPaychecksRules(): array
    {
        return [
            'bail',
            function (string $attribute, $value, \Closure $fail) {
                $methods = \data_get($this->get('payment_methods', []), '*.method');
                if (in_array(PaymentMethods::PAYCHECK, $methods) && !filled($value)) {
                    $fail(trans('validation.required', ['attribute' => $this->attributes()[$attribute]]));
                }
            },
            'array',
        ];
    }
}
