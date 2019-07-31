<?php

namespace Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Employee\Models\EmployeeTypes;

class EmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'     => 'bail|required|string',
            'email'    => $this->getEmailRules(),
            'document' => $this->getDocumentRules(),
            'type'     => $this->getTypeRules(),
        ];

        $address = $this->getAddressRules();
        $phone = $this->getPhoneRules();

        return $rules + $address + $phone;
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

    public function attributes()
    {
        return [
            'name'                => 'nome',
            'email'               => 'email',
            'document'            => 'CPF',
            'type'                => 'tipo',
            'address'             => 'endereço',
            'address.street'      => 'rua',
            'address.number'      => 'número',
            'address.district'    => 'bairro',
            'address.postal_code' => 'CEP',
            'address.city'        => 'cidade',
            'address.state'       => 'estado',
            'phone'               => 'telefone',
            'is_whatsapp'         => 'whatsapp',
        ];
    }

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
     * @return string
     */
    protected function getTypeRules(): string
    {
        $types = [
            EmployeeTypes::TYPE_ADMIN,
            EmployeeTypes::TYPE_DRESSMAKER,
            EmployeeTypes::TYPE_SELLER,
        ];

        return 'bail|required|string|in:'.implode(',', $types);
    }

    protected function getPhoneRules(): array
    {
        return [
            'phone'       => 'bail|required|cell_phone',
            'is_whatsapp' => 'bail|required|bool_custom',
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
            Rule::unique('users', 'email')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @param  string  $type  [cpf|cnpj|document]
     * @param          $ignore
     *
     * @return array
     */
    protected function getDocumentRules(string $type = 'cpf', $ignore = NULL): array
    {
        return [
            'bail',
            'required',
            $type,
            Rule::unique('users', 'document')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @param  string|NULL  $ignore
     *
     * @return array
     */
    protected function getIdentityRules($ignore = NULL): array
    {
        return [
            'bail',
            'nullable',
            'string',
            Rule::unique('users', 'identity')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
