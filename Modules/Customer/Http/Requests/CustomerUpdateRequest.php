<?php

namespace Modules\Customer\Http\Requests;

class CustomerUpdateRequest extends CustomerRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $customer = \Route::current()->parameter('customer');

        return [
            'company_name'           => 'bail|required|string|between:3,60',
            'trading_name'           => 'bail|required|string|between:3,60',
            'document'               => $this->getDocumentRules($customer),
            'state_registration'     => 'bail|required|string|between:3,30',
            'municipal_registration' => 'bail|required|string|between:3,30',
            'email'                  => $this->getEmailRules($customer),
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
    protected function getPhonesRules(): array
    {
        return [
            'phones'               => 'bail|nullable|array',
            'phones.*.phone'       => 'bail|required|cell_phone',
            'phones.*.is_whatsapp' => 'bail|required|bool_custom',
        ];
    }

    /**
     * @return array
     */
    protected function getContactsRules(): array
    {
        return [
            'contacts'   => 'bail|nullable|array',
            'contacts.*' => 'bail|required|string|min:3',
        ];
    }
}
