<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 05/08/2019
 * Time: 15:34
 */

namespace Modules\Customer\Models;


interface CustomerInterface
{
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_STANDBY  = 'stand_by';
}
