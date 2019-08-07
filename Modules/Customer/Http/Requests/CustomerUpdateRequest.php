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

        $rules = [
            'company_name'           => 'bail|required|string|between:3,60',
            'trading_name'           => 'bail|required|string|between:3,60',
            'state_registration'     => 'bail|required|string|between:3,30',
            'municipal_registration' => 'bail|required|string|between:3,30',
            'annotation'             => 'bail|nullable|string',
            'contact'                => 'bail|required|string|min:3',
            'document'               => $this->getDocumentRules('document', $customer),
            'email'                  => $this->getEmailRules($customer),
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
}
