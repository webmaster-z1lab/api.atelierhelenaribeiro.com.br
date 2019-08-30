<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $size = $packing->products()->distinct()->get(['reference'])->count();

        return [
            'checked'             => "bail|required|array|size:$size",
            'checked.*.amount'    => 'bail|required|integer|min:0',
            'checked.*.reference' => [
                'bail',
                'required',
                'distinct',
                Rule::exists('packings', 'products.reference')->where('_id', $packing->id),
            ],
        ];
    }
}
