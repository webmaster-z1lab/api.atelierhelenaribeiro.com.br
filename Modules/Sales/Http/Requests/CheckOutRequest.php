<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Sales\Models\Packing;

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
        $packing = Packing::find($this->route('packing'));

        return [
            'checked'             => 'bail|required|array|size:'.$packing->products()->count(),
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
