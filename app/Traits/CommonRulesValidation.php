<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 06/08/2019
 * Time: 18:31
 */

namespace App\Traits;


use Illuminate\Validation\Rule;

trait CommonRulesValidation
{
    /**
     * @param  array  ...$args
     *
     * @return array
     */
    public function mergeRules(...$args)
    {
        $rules = NULL;

        foreach ($args as $rule) {
            $rules = (NULL !== $rules) ? $rules + $rule : $rule;
        }

        return $rules;
    }

    /**
     * @param  mixed  ...$args
     *
     * @return mixed|null
     */
    public function mergeAttributes(...$args)
    {
        $attributes = NULL;

        foreach ($args as $rule) {
            $attributes = (NULL !== $attributes) ? $attributes + $rule : $rule;
        }

        return $attributes;
    }

    /**
     * @param  bool  $single
     *
     * @return array
     */
    public function getPhoneRules($single = TRUE): array
    {
        if (!$single) {
            return [
                'phones'               => 'bail|required|array',
                'phones.*.number'      => 'bail|required|cell_phone',
                'phones.*.is_whatsapp' => 'bail|required|bool_custom',
            ];
        }

        return [
            'phone'             => 'bail|required|array',
            'phone.number'      => 'bail|required|cell_phone',
            'phone.is_whatsapp' => 'bail|required|bool_custom',
        ];
    }

    /**
     * @param  bool  $required
     *
     * @return array
     */
    public function getImagesRules(bool $required = FALSE)
    {
        return [
            'images'                 => !$required ? 'bail|sometimes|array|min:1' : 'bail|required|array|min:1',
            'images.*.path'          => 'bail|required|string',
            'images.*.name'          => 'bail|required|string',
            'images.*.extension'     => 'bail|required|string',
            'images.*.mime_type'     => 'bail|required|string',
            'images.*.size_in_bytes' => 'bail|required|integer|min:1',
        ];
    }

    /**
     * @param  string|NULL  $ignore
     *
     * @return array
     */
    public function getEmailRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @param  string       $type  cpf|cnpj|document
     * @param  string|NULL  $ignore
     *
     * @return array
     */
    public function getDocumentRules(string $type = 'cpf', $ignore = NULL): array
    {
        return [
            'bail',
            'required',
            $type,
            Rule::unique('users', 'document')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @param  string|NULL  $ignore
     *
     * @return array
     */
    public function getIdentityRules($ignore = NULL): array
    {
        return [
            'bail',
            'required',
            'string',
            Rule::unique('users', 'identity')->ignore($ignore)->where(function ($query) {
                return $query->where('deleted_at', 'exists', FALSE)->orWhereNull('deleted_at');
            }),
        ];
    }

    /**
     * @return array
     */
    public function getAddressRules()
    {
        return [
            'address'             => 'bail|required|array',
            'address.street'      => 'bail|required|string|min:3',
            'address.number'      => 'bail|required|integer|min:1',
            'address.complement'  => 'bail|nullable|string',
            'address.district'    => 'bail|required|string|min:3',
            'address.postal_code' => 'bail|required|digits:8',
            'address.city'        => 'bail|required|string|min:3',
            'address.state'       => 'bail|required|string|size:2',
        ];
    }

    /**
     * @return array
     */
    public function getAddressAttributes()
    {
        return [
            'address'             => 'endereço',
            'address.street'      => 'logradouro',
            'address.number'      => 'número',
            'address.complement'  => 'complemento',
            'address.district'    => 'bairro',
            'address.postal_code' => 'CEP',
            'address.city'        => 'cidade',
            'address.state'       => 'estado',
        ];
    }

    public function getImageAttributes()
    {
        return [
            'images'                 => 'imagens',
            'images.*'               => 'imagem',
            'images.*.path'          => 'caminho da imagem',
            'images.*.name'          => 'nome da imagem',
            'images.*.extension'     => 'extensão da imagem',
            'images.*.size_in_bytes' => 'tamanho da imagem',
            'images.*.mime_type'     => 'tipo da imagem',
        ];
    }

    /**
     * @param  bool  $single
     *
     * @return array
     */
    protected function getPhoneAttributes(bool $single = TRUE): array
    {
        if (!$single) {
            return [
                'phones'               => 'telefones',
                'phones.*.number'      => 'número',
                'phones.*.is_whatsapp' => 'whatsapp',
            ];
        }

        return [
            'phone'             => 'telefones',
            'phone.number'      => 'número',
            'phone.is_whatsapp' => 'whatsapp',
        ];
    }
}
