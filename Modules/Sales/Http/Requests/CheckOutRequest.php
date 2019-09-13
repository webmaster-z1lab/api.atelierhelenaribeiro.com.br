<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Sales\Models\PaymentMethods;
use Modules\Stock\Models\ProductStatus;

class CheckOutRequest extends FormRequest
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
        /** @var \Modules\Sales\Models\Packing $packing */
        $packing = $this->route('packing');
        $size = $packing->products()
            ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])->pluck('reference')->unique()->count();

        return [
            'checked'             => "bail|required|array|size:$size",
            'checked.*.amount'    => 'bail|required|integer|min:0',
            'checked.*.reference' => [
                'bail',
                'required',
                'distinct',
                Rule::exists('packings', 'products.reference')->where('_id', $packing->id),
            ],
            PaymentMethods::MONEY => 'bail|required|numeric|min:0',
            PaymentMethods::CHECK => 'bail|required|numeric|min:0',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'checked'             => 'itens',
            'checked.*.amount'    => 'quantidade do item',
            'checked.*.reference' => 'referência do item',
            PaymentMethods::MONEY => 'valor recebido em dinheiro',
            PaymentMethods::CHECK => 'valor recebido em cheque',
        ];
    }
}
