<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 28/06/2019
 * Time: 15:52
 */

namespace Modules\User\Http\Controllers\Api;


use Modules\User\Jobs\MarkNotificationsAsRead;
use Modules\User\Http\Resources\v1\Notification;
use Modules\User\Models\User;

class NotificationController
{
    /**
     * @param  \Modules\User\Models\User  $user
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(User $user)
    {
        $notifications = $user->latestNotifications;

        if($user->hasUnreadNotifications()) MarkNotificationsAsRead::dispatch($user);

        return Notification::collection($notifications);
    }
}
