<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Customer\Models\CustomerInterface;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'company_name'           => 'bail|required|string|between:3,60',
            'trading_name'           => 'bail|nullable|string|between:3,60',
            'state_registration'     => 'bail|nullable|string|between:3,30',
            'municipal_registration' => 'bail|nullable|string|between:3,30',
            'annotation'             => 'bail|nullable|string',
            'contact'                => 'bail|required|string|min:3',
            'document'               => $this->getDocumentRules(),
            'email'                  => $this->getEmailRules(),
            'seller'                 => $this->getSellerRules(),
            'status'                 => $this->getStatusRules(),
        ];

        $address = $this->getAddressRules();
        $phones = $this->getPhonesRules();
        $owners = $this->getOwnersRules();

        return $rules + $address + $phones + $owners;
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
        $rules = [
            'company_name'           => 'razão social',
            'trading_name'           => 'nome fantasia',
            'document'               => 'CNPJ',
            'state_registration'     => 'inscrição estadual',
            'municipal_registration' => 'inscrição municipal',
            'email'                  => 'e-mail',
            'contact'                => 'contato',
            'annotation'             => 'observações',
            'status'                 => 'situação',
            'seller'                 => 'representante',
        ];

        $address = $this->getAddressAttributes();
        $phones = $this->getPhonesAttributes();
        $owners = $this->getOwnersAttributes();

        return $rules + $address + $phones + $owners;
    }

    /**
     * @return string
     */
    protected function getStatusRules(): string
    {
        $status = [
            CustomerInterface::STATUS_ACTIVE,
            CustomerInterface::STATUS_INACTIVE,
            CustomerInterface::STATUS_STANDBY,
        ];

        return 'bail|required|string|in:'.implode(',', $status);
    }

    /**
     * @return array
     */
    protected function getSellerRules(): array
    {
        return [
            'bail',
            'required',
            'string',
            Rule::exists('users', '_id')->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
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
            'document',
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
            'phones.*.number'      => 'bail|required|cell_phone',
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
            'phones.*.number'      => 'número',
            'phones.*.is_whatsapp' => 'whatsapp',
        ];
    }

    protected function getOwnersRules(): array
    {
        return [
            'owners'                     => 'bail|required|array|min:1',
            'owners.*.name'              => 'bail|required|string|min:3',
            'owners.*.document'          => 'bail|required|cpf',
            'owners.*.email'             => 'bail|required|email',
            'owners.*.birth_date'        => 'bail|required|date_format:"d/m/Y"|before:today',
            'owners.*.phone'             => 'bail|array|required',
            'owners.*.phone.number'      => 'bail|required|cell_phone',
            'owners.*.phone.is_whatsapp' => 'bail|required|bool_custom',
        ];
    }

    public function getOwnersAttributes(): array
    {
        return [
            'owners'               => 'proprietários',
            'owners.*.name'        => 'nome do proprietário',
            'owners.*.document'    => 'CPF do proprietário',
            'owners.*.email'       => 'e-mail do proprietário',
            'owners.*.birth_date'  => 'data de nascimento do proprietário',
            'owners.*.phone'       => 'telefone do proprietário',
            'owners.*.is_whatsapp' => 'whatsapp do proprietário',
        ];
    }
}
