<?php

namespace Modules\Stock\Http\Requests;

use App\Traits\CommonRulesValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'amount'            => 'bail|required|integer|min:1',
            'size'              => $this->getSizeRules(),
            'color'             => 'bail|required|string',
            'template'          => $this->getTemplateRules(),
            'price'             => 'bail|nullable|numeric|min:0.1',
            'template_images'   => 'bail|sometimes|array|min:1',
            'template_images.*' => 'bail|required|exists:images,_id',

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

    /**
     * @return array
     */
    protected function getSizeRules(): array
    {
        return [
            'bail',
            'required',
            Rule::exists('sizes', 'name')->where(function ($query) {
                $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    protected function getTemplateRules(): array
    {
        return [
            'bail',
            'required',
            Rule::exists('templates', '_id')->where(function ($query) {
                $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
