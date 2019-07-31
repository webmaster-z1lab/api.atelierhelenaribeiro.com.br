<?php
/**
 * Created by PhpStorm.
 * User: Alisson
 * Date: 02/08/2017
 * Time: 14:17
 */

namespace App\Validator;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator as BaseValidator;
use Respect\Validation\Validator as Respect;

class Validator extends BaseValidator
{
    /**
     * Valida se o campo é booleano ou algum pseudo tipo de bool
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    protected function validateBoolCustom($attribute, $value)
    {
        $array = ['true', 'false', 0, 1, true, false];

        return in_array($value, $array);
    }

    /**
     * Valida o formato do celular junto com o ddd
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    protected function validateCellPhone($attribute, $value)
    {
        return preg_match('/^\d{10,11}$/', $value) > 0;
    }

    /**
     * Valida se o CPF é válido
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    protected function validateCpf($attribute, $value)
    {
        return Respect::cpf()->validate($value);
    }

    /**
     * Valida se o CNPJ é válido
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    protected function validateCnpj($attribute, $value)
    {
        return Respect::cnpj()->validate($value);
    }

    /**
     * Valida se o CPF é válido
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    protected function validateDocument($attribute, $value)
    {
        $doc = strlen($value);

        if ($doc === 11) return Respect::cpf()->validate($value);

        if ($doc === 14) return Respect::cnpj()->validate($value);

        return false;
    }

    /**
     * Valida se é um Nome Completo válido
     * @param $atrribute
     * @param $value
     * @return bool
     */
    protected function validateFullName($atrribute, $value)
    {
        $names = Arr::where(explode(' ', $value), function ($value, $key) {
            return filled($value);
        });
        return count($names) > 1;
    }
}
