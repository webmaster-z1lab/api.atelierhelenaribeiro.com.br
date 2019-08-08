<?php

namespace Modules\Customer\Http\Requests;

use App\Traits\CommonRulesValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Customer\Models\CustomerStatus;

class CustomerRequest extends FormRequest
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
            'company_name'           => 'bail|required|string|between:3,60',
            'trading_name'           => 'bail|nullable|string|between:3,60',
            'state_registration'     => 'bail|nullable|string|between:3,30',
            'municipal_registration' => 'bail|nullable|string|between:3,30',
            'annotation'             => 'bail|nullable|string',
            'contact'                => 'bail|required|string|min:3',
            'document'               => $this->getDocumentRules('document'),
            'email'                  => $this->getEmailRules(),
            'seller'                 => $this->getSellerRules(),
            'status'                 => $this->getStatusRules(),
        ];

        return $this->mergeRules(
            $rules,
            $this->getAddressRules(),
            $this->getPhoneRules(FALSE),
            $this->getOwnersRules()
        );
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

        return $this->mergeAttributes(
            $attr,
            $this->getAddressAttributes(),
            $this->getPhoneAttributes(FALSE),
            $this->getOwnersAttributes()
        );
    }

    /**
     * @return string
     */
    protected function getStatusRules(): string
    {
        $status = [
            CustomerStatus::ACTIVE,
            CustomerStatus::INACTIVE,
            CustomerStatus::STANDBY,
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
     * @return array
     */

    protected function getOwnersRules(): array
    {
        return [
            'owners'                     => 'bail|required|array|min:1',
            'owners.*.name'              => 'bail|required|string|min:3',
            'owners.*.document'          => 'bail|required|cpf',
            'owners.*.email'             => 'bail|required|email',
            'owners.*.birth_date'        => 'bail|required|date_format:"d/m/Y"|before_or_equal:- 18 years',
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
