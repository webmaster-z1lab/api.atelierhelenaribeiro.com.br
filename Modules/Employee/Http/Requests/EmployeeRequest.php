<?php

namespace Modules\Employee\Http\Requests;

use App\Traits\CommonRulesValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Employee\Models\EmployeeTypes;

class EmployeeRequest extends FormRequest
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
            'name'           => 'bail|required|string',
            'birth_date'     => 'bail|required|date_format:d/m/Y|before_or_equal:today - 18 years',
            'admission_date' => 'bail|required|date_format:d/m/Y|before_or_equal:today',
            'email'          => $this->getEmailRules(NULL, FALSE),
            'document'       => $this->getDocumentRules(),
            'type'           => $this->getTypeRules(),
            'identity'       => $this->getIdentityRules(),
            'work_card'      => $this->getWorkCardRules(),
            'remuneration'   => 'bail|nullable|numeric|min:0.01',
        ];

        return $this->mergeRules(
            $rules,
            $this->getAddressRules(),
            $this->getPhoneRules()
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
     * @return array|mixed|null
     */
    public function attributes()
    {
        $attr = [
            'name'           => 'nome',
            'email'          => 'email',
            'document'       => 'CPF',
            'type'           => 'tipo',
            'birth_date'     => 'data de nascimento',
            'admission_date' => 'data de admissão',
            'identity'       => 'identidade',
            'work_card'      => 'carteira de trabalho',
            'remuneration'   => 'remuneração',
        ];

        return $this->mergeAttributes(
            $attr,
            $this->getAddressAttributes(),
            $this->getPhoneAttributes()
        );
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
            EmployeeTypes::TYPE_EMBROIDERER,
            EmployeeTypes::TYPE_EMBROIDERER_ASSISTANT,
            EmployeeTypes::TYPE_DRESSMAKER_ASSISTANT,
            EmployeeTypes::TYPE_MODELIST,
            EmployeeTypes::TYPE_OFFICE_ASSISTANT,
        ];

        return 'bail|required|string|in:'.implode(',', $types);
    }

    /**
     * @param  string|NULL  $ignore
     *
     * @return array
     */
    public function getWorkCardRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'string',
            Rule::unique('users', 'work_card')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }
}
