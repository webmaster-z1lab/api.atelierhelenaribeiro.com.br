<?php

namespace Modules\Stock\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'serial'   => $this->getSerialRules(),
            'size'     => 'bail|required|string',
            'color'    => 'bail|required|string',
            'template' => 'bail|required|exists:templates,_id',
            'images'   => 'bail|required|array|min:1',
            'images.*' => 'bail|required|image',
            'price'    => 'bail|nullable|integer|min:0',
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
            'serial'   => 'número de série',
            'size'     => 'tamanho',
            'color'    => 'cor',
            'template' => 'modelo',
            'images'   => 'imagens',
            'images.*' => 'imagem',
            'price'    => 'preço',
        ];
    }

    /**
     * @param  $ignore
     *
     * @return array
     */
    protected function getSerialRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'string',
            Rule::unique('templates', 'reference')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
