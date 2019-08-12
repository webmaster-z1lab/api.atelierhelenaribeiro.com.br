<?php

namespace Modules\Stock\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'   => 'bail|required|integer|min:1',
            'size'     => 'bail|required|string',
            'color'    => 'bail|required|string',
            'template' => 'bail|required|exists:templates,_id',
            'images'   => 'bail|required|array|min:1',
            'images.*' => 'bail|required|array',
            'price'    => 'bail|nullable|integer|min:1',
        ];
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
        return [
            'size'     => 'tamanho',
            'color'    => 'cor',
            'template' => 'modelo',
            'images'   => 'imagens',
            'images.*' => 'imagem',
            'price'    => 'preço',
        ];
    }
}
