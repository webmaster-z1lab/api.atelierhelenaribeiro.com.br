<?php

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class OwnerRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'bail|required|string|min:3',
            'document'   => 'bail|required|cpf',
            'email'      => 'bail|required|email',
            'birth_date' => 'bail|required|date|before_or_equal:today',
            $this->getPhonesRules(),
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

    public function attributes()
    {
        return [
            'name'       => 'nome',
            'document'   => 'CNPJ',
            'email'      => 'e-mail',
            'birth_date' => 'data de nascimento',
            $this->getPhonesAttributes(),
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
}
