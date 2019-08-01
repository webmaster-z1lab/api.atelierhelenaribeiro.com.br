<?php

namespace Modules\Customer\Http\Requests;

class OwnerUpdateRequest extends OwnerRequest
{
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
}
