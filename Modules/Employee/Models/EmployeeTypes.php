<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 30/07/2019
 * Time: 15:51
 */

namespace Modules\Employee\Models;


interface EmployeeTypes
{
    public const TYPE_ADMIN = 'admin';
    public const TYPE_SELLER = 'seller';
    public const TYPE_DRESSMAKER = 'dressmaker';
    public const TYPE_EMBROIDERER = 'embroiderer';
    public const TYPE_EMBROIDERER_ASSISTANT = 'embroiderer_assistant';
    public const TYPE_DRESSMAKER_ASSISTANT = 'dressmaker_assistant';
    public const TYPE_MODELIST = 'modelist';
    public const TYPE_OFFICE_ASSISTANT = 'office_assistant';
}
