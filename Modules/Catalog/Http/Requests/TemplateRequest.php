<?php

namespace Modules\Catalog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reference' => $this->getReferenceRules(),
            'price'     => 'bail|required|integer|min:1',
            'images'    => 'bail|required|array|min:1',
            'images.*' => 'bail|required'
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
            'reference' => 'referência',
            'images'    => 'imagens',
            'images.*'  => 'imagem',
            'price'     => 'preço',
        ];
    }

    /**
     * @param  $ignore
     *
     * @return array
     */
    protected function getReferenceRules($ignore = NULL): array
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
