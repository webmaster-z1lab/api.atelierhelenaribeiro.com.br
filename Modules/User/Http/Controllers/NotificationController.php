<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 28/06/2019
 * Time: 15:52
 */

namespace Modules\User\Http\Controllers;


use Modules\User\Http\Resources\Notification;
use Modules\User\Models\DatabaseNotification;

class NotificationController
{
    /**
     * @var \Modules\User\Models\User|null
     */
    protected $user;

    /**
     * NotificationController constructor.
     */
    public function __construct()
    {
        $this->user = \Auth::user();
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return Notification::collection($this->user->notifications);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function latest()
    {
        return Notification::collection($this->user->latestNotifications);
    }

    /**
     * @param  \Modules\User\Models\DatabaseNotification  $notification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return response()->json([], 204);
    }
}
