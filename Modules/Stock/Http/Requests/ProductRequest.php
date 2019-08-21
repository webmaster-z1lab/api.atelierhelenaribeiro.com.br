<?php

namespace Modules\Stock\Http\Requests;

use App\Traits\CommonRulesValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    use CommonRulesValidation;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'amount'                 => 'bail|required|integer|min:1',
            'size'                   => 'bail|required|string',
            'color'                  => 'bail|required|string',
            'template'               => 'bail|required|exists:templates,_id',
            'price'                  => 'bail|nullable|numeric|min:0.1',
        ];

        return $this->mergeRules($rules, $this->getImagesRules());
    }

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
     * @return array
     */
    public function attributes()
    {
        $attr = [
            'size'     => 'tamanho',
            'color'    => 'cor',
            'template' => 'modelo',
            'price'    => 'preço',
        ];

        return $this->mergeAttributes($attr, $this->getImageAttributes());
    }
}
