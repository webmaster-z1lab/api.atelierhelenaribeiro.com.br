<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 28/06/2019
 * Time: 15:52
 */

namespace Modules\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\User\Http\Resources\NotificationResource;
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
        if (!empty(\Request::query()) && NULL !== \Request::query()['filter']) return $this->latest();

        return NotificationResource::collection($this->user->notifications);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function latest()
    {
        return NotificationResource::collection($this->user->latestNotifications);
    }

    /**
     * @param  \Modules\User\Models\DatabaseNotification  $notification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DatabaseNotification $notification): JsonResponse
    {
        if($notification->notifiable_id !== $this->user->id) abort(403);

        $notification->markAsRead();

        return new JsonResponse(NULL, 204);
    }
}
