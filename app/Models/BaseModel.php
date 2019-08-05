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

/**
 * App\Models\BaseModel
 *
 * @property-read mixed $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\App\Models\BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = null)
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    use Cachable;

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return $this->find($value) ?? abort(404);
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Jenssegers\Mongodb\Query\Builder  $query
     * @param  string                             $search
     *
     * @return \Jenssegers\Mongodb\Query\Builder
     */
    public function scopeSearch($query, string $search = NULL)
    {
        if (NULL === $search) return $query;

        $query->getQuery()->projections = ['score' => ['$meta' => 'textScore']];
        $query->orderBy('score', ['$meta' => 'textScore']);

        return $query->whereRaw(['$text' => ['$search' => "/^".$search."/"]]);
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Jenssegers\Mongodb\Query\Builder  $query
     * @param  string                             $search
     * @param  int                                $page
     * @param  int                                $limit
     *
     * @return \Jenssegers\Mongodb\Query\Builder
     */
    public function scopeSearchPaginated($query, string $search = NULL, int $page = 1, int $limit = 10)
    {
        $query->getQuery()->projections = ['score' => ['$meta' => 'textScore']];
        $query->orderBy('score', ['$meta' => 'textScore']);
        $query->skip(($page - 1) * $limit);
        $query->take($limit);

        if (NULL === $search) return $query;

        return $query->whereRaw(['$text' => ['$search' => "/^".$search."/"]]);
    }
}
