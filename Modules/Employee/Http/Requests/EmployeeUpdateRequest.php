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
            'name'     => 'bail|required|string',
            'email'    => $this->getEmailRules($employee),
            'document' => $this->getDocumentRules('cpf', $employee),
            'type'     => $this->getTypeRules(),
        ];

        return $this->mergeRules(
            $rules,
            $this->getAddressRules(),
            $this->getPhoneRules()
        );
    }
}
