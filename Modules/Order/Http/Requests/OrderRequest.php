<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
            'annotations'                 => 'bail|nullable|string',
            'event_date'                  => 'bail|required|date_format:d/m/Y|after:today',
            'ship_until'                  => 'bail|required|date_format:d/m/Y|after:today|before:event_date',
            'customer'                    => $this->getCustomerRules(),
            'products'                    => 'bail|required|array|min:1',
            'products.*.template'         => $this->getTemplateRules(),
            'products.*.color'            => 'bail|required|string',
            'products.*.mannequin'        => $this->getMannequinRules(),
            'products.*.removable_sleeve' => 'bail|required|bool_custom',
            'products.*.has_lace'         => 'bail|required|bool_custom',
            'products.*.bust'             => 'bail|required_with:armhole,hip,waist,slit,body,skirt,tail,shoulders,cleavage|integer|min:0',
            'products.*.armhole'          => 'bail|required_with:bust,hip,waist,slit,body,skirt,tail,shoulders,cleavage|integer|min:0',
            'products.*.hip'              => 'bail|required_with:bust,armhole,waist,slit,body,skirt,tail,shoulders,cleavage|integer|min:0',
            'products.*.waist'            => 'bail|required_with:bust,armhole,hip,slit,body,skirt,tail,shoulders,cleavage|integer|min:0',
            'products.*.slit'             => 'bail|required_with:bust,armhole,hip,waist,body,skirt,tail,shoulders,cleavage|integer|min:0',
            'products.*.body'             => 'bail|required_with:bust,armhole,hip,waist,slit,skirt,tail,shoulders,cleavage|integer|min:0',
            'products.*.skirt'            => 'bail|required_with:bust,armhole,hip,waist,slit,body,tail,shoulders,cleavage|integer|min:0',
            'products.*.tail'             => 'bail|required_with:bust,armhole,hip,waist,slit,body,skirt,shoulders,cleavage|integer|min:0',
            'products.*.shoulders'        => 'bail|required_with:bust,armhole,hip,waist,slit,body,skirt,tail,cleavage|integer|min:0',
            'products.*.cleavage'         => 'bail|required_with:bust,armhole,hip,waist,slit,body,skirt,tail,shoulders|integer|min:0',
            'products.*.skirt_type'       => 'bail|nullable|string',
            'products.*.sleeve_model'     => 'bail|nullable|string',
            'products.*.annotations'      => 'bail|nullable|string',
        ];
    }

    /**
     * @return array
     */
    protected function getCustomerRules(): array
    {
        return [
            'bail',
            'required',
            Rule::exists('customers', '_id')->where(function ($query) {
                $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    protected function getMannequinRules(): array
    {
        return [
            'bail',
            'required',
            'integer',
            function (string $attribute, int $value, \Closure $fail) {
                if (($value % 2) !== 0) {
                    $fail(trans('validation.in', ['attribute' => 'manequim do produto']));
                }
            },
        ];
    }
}
