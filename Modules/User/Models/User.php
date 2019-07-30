<?php

namespace Modules\User\Models;

use App\Models\Address;
use App\Models\Phone;
use Illuminate\Auth\MustVerifyEmail as VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\User\Notifications\ResetPasswordNotification;

/**
 * Class User
 *
 * @package Modules\User\Models
 * @property-read string                              id
 * @property string                                    type
 * @property string                                    name
 * @property string                                    first_name
 * @property string                                    email
 * @property string                                    password
 * @property string                                    api_token
 * @property string                                    document
 * @property string                                    identity
 * @property \Modules\User\Models\DatabaseNotification notifications
 * @property \Modules\User\Models\DatabaseNotification latestNotifications
 * @property \App\Models\Address                       address
 * @property \App\Models\Phone                         phone
 * @property \Carbon\Carbon                            created_at
 * @property \Carbon\Carbon                            updated_at
 * @property \Carbon\Carbon                            deleted_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\User\Models\User                                            newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\User\Models\User                                            newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\User\Models\User                                            query()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, VerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'document',
        'identity',
        'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getFirstNameAttribute()
    {
        return explode(' ', $this->attributes['name'])[0];
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }

    /**
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($this->attributes, $token));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function latestNotifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc')->limit(30);
    }

    /**
     * @return bool
     */
    public function hasUnreadNotifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->count() > 0;
    }

    /**
     * @return int
     */
    public function countUnreadNotifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->count();
    }
}
