<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name'           => 'bail|required|string|between:3,60',
            'trading_name'           => 'bail|required|string|between:3,60',
            'document'               => $this->getDocumentRules(),
            'state_registration'     => 'bail|required|string|between:3,30',
            'municipal_registration' => 'bail|required|string|between:3,30',
            'email'                  => $this->getEmailRules(),
            $this->getAddressRules(),
            $this->getPhonesRules(),
            $this->getContactsRules(),
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
            'company_name'           => 'razão social',
            'trading_name'           => 'nome fantasia',
            'document'               => 'CNPJ',
            'state_registration'     => 'inscrição estadual',
            'municipal_registration' => 'inscrição municipal',
            'email'                  => 'e-mail',
            $this->getAddressAttributes(),
            $this->getPhonesAttributes(),
            $this->getContactsAttributes(),
        ];
    }

    /**
     * @param  $ignore
     *
     * @return array
     */
    protected function getDocumentRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'cnpj',
            Rule::unique('customers', 'document')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @param  $ignore
     *
     * @return array
     */
    protected function getEmailRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'email',
            Rule::unique('customers', 'email')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @return array
     */
    protected function getAddressRules()
    {
        return [
            'address'             => 'bail|required|array',
            'address.street'      => 'bail|required|string|min:3',
            'address.number'      => 'bail|required|integer|min:1',
            'address.complement'  => 'bail|nullable|string',
            'address.district'    => 'bail|required|string|min:3',
            'address.postal_code' => 'bail|required|digits:8',
            'address.city'        => 'bail|required|string|min:3',
            'address.state'       => 'bail|required|string|size:2',
        ];
    }

    /**
     * @return array
     */
    protected function getAddressAttributes()
    {
        return [
            'address'             => 'endereço',
            'address.street'      => 'logradouro',
            'address.number'      => 'número',
            'address.complement'  => 'complemento',
            'address.district'    => 'bairro',
            'address.postal_code' => 'CEP',
            'address.city'        => 'cidade',
            'address.state'       => 'estado',
        ];
    }

    /**
     * @return array
     */
    protected function getPhonesRules(): array
    {
        return [
            'phones'               => 'bail|required|array|min:1',
            'phones.*.phone'       => 'bail|required|cell_phone',
            'phones.*.is_whatsapp' => 'bail|required|bool_custom',
        ];
    }

    /**
     * @return array
     */
    protected function getPhonesAttributes(): array
    {
        return [
            'phones'               => 'telefones',
            'phones.*.phone'       => 'telefone',
            'phones.*.is_whatsapp' => 'whatsapp',
        ];
    }

    /**
     * @return array
     */
    protected function getContactsRules(): array
    {
        return [
            'contacts'   => 'bail|required|array|min:1',
            'contacts.*' => 'bail|required|string|min:3',
        ];
    }

    /**
     * @return array
     */
    protected function getContactsAttributes(): array
    {
        return [
            'contacts'   => 'contatos',
            'contacts.*' => 'contato',
        ];
    }
}
