<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 30/07/2019
 * Time: 19:07
 */

namespace Modules\Employee\Http\Requests;

class EmployeeUpdateRequest extends EmployeeRequest
{
    public function rules()
    {
        $employee = \Route::current()->parameter('employee');

        $rules = [
            'name'           => 'bail|required|string',
            'birth_date'     => 'bail|required|date_format:d/m/Y|before_or_equal:today - 18 years',
            'admission_date' => 'bail|required|date_format:d/m/Y|before_or_equal:today',
            'email'          => $this->getEmailRules($employee. FALSE),
            'document'       => $this->getDocumentRules('cpf', $employee),
            'type'           => $this->getTypeRules(),
            'identity'       => $this->getIdentityRules($employee),
            'work_card'      => $this->getWorkCardRules($employee),
            'remuneration'   => 'bail|nullable|numeric|min:0.01'
        ];

        return $this->mergeRules(
            $rules,
            $this->getAddressRules(),
            $this->getPhoneRules()
        );
    }
}
