<?php

namespace App\Auth\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;

/**
 * App\Auth\Models\Token
 *
 * @property-read string          $id
 * @property-read  string         $user_id
 * @property-read string          $ip
 * @property-read string          $user_agent
 * @property-read string          $asked_by
 * @property-read  \Carbon\Carbon $expires_at
 * @property-read \Carbon\Carbon  $revoked_at
 * @property-read \Carbon\Carbon  $created_at
 * @property-read \Carbon\Carbon  $updated_at
 * @method static \App\Models\BaseModel newModelQuery()
 * @method static \App\Models\BaseModel newQuery()
 * @method static \App\Models\BaseModel query()
 * @mixin \Eloquent
 */
class Token extends Model
{
    protected $fillable = ['user_id', 'ip', 'user_agent', 'asked_by', 'expires_at', 'revoked_at'];

    protected $dates = ['expires_at', 'revoked_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('not_expired', function (Builder $builder) {
            $builder->where(function ($query) {
                /** @var \Jenssegers\Mongodb\Eloquent\Builder $query */
                $query->where('expires_at', 'exists', FALSE)
                    ->orWhereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
        });

        static::addGlobalScope('not_revoked', function (Builder $builder) {
            $builder->where(function ($query) {
                /** @var \Jenssegers\Mongodb\Eloquent\Builder $query */
                $query->where('revoked_at', 'exists', FALSE)
                    ->orWhereNull('revoked_at');
            });
        });
    }


}
