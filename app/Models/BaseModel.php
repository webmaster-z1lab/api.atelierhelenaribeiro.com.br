<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 21:26
 */

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Jenssegers\Mongodb\Eloquent\Model;

class BaseModel extends Model
{
    use Cachable;
}
