<?php

namespace Modules\Employee\Http\Requests;

use App\Traits\CommonRulesValidation;
use Illuminate\Foundation\Http\FormRequest;
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
            'name'     => 'bail|required|string',
            'email'    => $this->getEmailRules(),
            'document' => $this->getDocumentRules(),
            'type'     => $this->getTypeRules(),
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
            'name'     => 'nome',
            'email'    => 'email',
            'document' => 'CPF',
            'type'     => 'tipo',
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
        ];

        return 'bail|required|string|in:'.implode(',', $types);
    }
}
