<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Hash::check($this->old_password, \Auth::guard('api')->user()->getAuthPassword());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'     => 'bail|required|string|min:8|confirmed',
            'old_password' => 'bail|required|string|min:8',
        ];
    }
}
