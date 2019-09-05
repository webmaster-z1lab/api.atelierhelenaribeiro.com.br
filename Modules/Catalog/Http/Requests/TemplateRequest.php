<?php

namespace Modules\Catalog\Http\Requests;

use App\Traits\CommonRulesValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
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
            'reference' => $this->getReferenceRules(),
            'price'     => 'bail|required|numeric|min:0.1',
            'is_active' => 'bail|required|bool_custom',
        ];

        return $this->mergeRules($rules, $this->getImagesRules(TRUE));
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
            'reference' => 'referência',
            'is_active' => 'ativo',
            'price'     => 'preço',
        ];

        return $this->mergeAttributes($attr, $this->getImageAttributes());
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
            Rule::unique('templates', 'reference')->ignore($ignore, '_id')->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
