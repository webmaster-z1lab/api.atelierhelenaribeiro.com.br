<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipRequest extends FormRequest
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
            'tracking_code' => 'bail|nullable|string',
            'freight'       => 'bail|required|numeric|min:0',
            'shipped_at'    => 'bail|required|date_format:d/m/Y|before_or_equal:today',
        ];
    }
}
