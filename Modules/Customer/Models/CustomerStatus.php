<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 05/08/2019
 * Time: 15:34
 */

namespace Modules\Customer\Models;


abstract class CustomerStatus
{
    public const ACTIVE   = 'active';
    public const INACTIVE = 'inactive';
    public const STANDBY  = 'stand_by';
}
