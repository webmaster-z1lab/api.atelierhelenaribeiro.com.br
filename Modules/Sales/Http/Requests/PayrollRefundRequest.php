<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Stock\Models\ProductStatus;

class PayrollRefundRequest extends FormRequest
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
            'products'             => 'bail|required|array|min:1',
            'products.*.reference' => $this->getReferenceRules(),
            'products.*.amount'    => 'bail|required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'products'             => 'produtos',
            'products.*.reference' => 'referência do produto',
            'products.*.amount'    => 'quantidade do produto',
        ];
    }

    public function getReferenceRules(): array
    {
        /** @var \Modules\Sales\Models\Visit $visit */
        $visit = $this->route('visit');

        return [
            'bail',
            'required',
            'distinct',
            Rule::exists('payrolls', 'reference')->where('customer_id', $visit->customer_id)
                ->where(function ($query) use ($visit) {
                    $query->where('status', ProductStatus::ON_CONSIGNMENT_STATUS)->orWhere('completion_visit_id', $visit->id);
                })->where(function ($query) use ($visit) {
                    $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
                }),
        ];
    }
}
