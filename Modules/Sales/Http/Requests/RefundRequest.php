<?php

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Size;

class RefundRequest extends FormRequest
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

    /**
     * @return array
     */
    public function getReferenceRules(): array
    {
        return [
            'bail',
            'required',
            'distinct',
            function (string $attribute, string $value, \Closure $fail) {
                $references = explode('-', $value);

                if (!Template::where('reference', $references[0])->exists() ||
                    !Color::where('reference', $references[1])->exists() ||
                    !Size::where('reference', $references[2])->exists()) {
                    $fail(trans('validation.exists', ['attribute' => $this->attributes()[$attribute]]));
                }
            },
        ];
    }
}
