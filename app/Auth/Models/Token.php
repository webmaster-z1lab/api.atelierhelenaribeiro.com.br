<?php

namespace App\Auth\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Query\Builder;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Token extends BaseModel
{
    protected $fillable = ['user_id', 'ip', 'user_agent', 'asked_by', 'expires_at', 'revoked_at'];

    protected $dates = ['expires_at', 'revoked_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('not_expired', function (Builder $builder) {
            $builder->where(function ($query) {
                /** @var \Jenssegers\Mongodb\Query\Builder $query */
                $query->where('expires_at', 'exists', FALSE)
                    ->orWhereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
        });

        static::addGlobalScope('not_revoked', function (Builder $builder) {
            $builder->where(function ($query) {
                /** @var \Jenssegers\Mongodb\Query\Builder $query */
                $query->where('revoked_at', 'exists', FALSE)
                    ->orWhereNull('revoked_at');
            });
        });
    }


}
