<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 30/07/2019
 * Time: 17:33
 */

namespace Modules\User\Observers;

use Modules\User\Models\User;

class UserObserver
{
    /**
     * @param  \Modules\User\Models\User  $user
     */
    public function saving(User $user)
    {
        $user = json_encode($user->toArray());

        \Log::info($user);
    }

}
