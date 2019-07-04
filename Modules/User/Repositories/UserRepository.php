<?php


namespace Modules\User\Repositories;

use Modules\User\Models\User;
use Z1lab\JsonApi\Repositories\ApiRepository;

/**
 * Class UserRepository
 *
 * @package Modules\User\Repositories
 *
 * @property-read \Modules\User\Models\User $model
 */
class UserRepository extends ApiRepository
{
    /**
     * UserRepository constructor.
     *
     * @param  \Modules\User\Models\User  $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user, 'user');
    }

    /**
     * @param  string  $password
     *
     * @return \Modules\User\Models\User|null
     */
    public function changePassword(string $password)
    {
        /** @var \Modules\User\Models\User $user */
        $user = \Auth::guard('api')->user();

        $password = \Hash::make($password);

        $user->update(compact('password'));

        $this->setCacheKey($user->id);
        $this->flush()->remember($user);

        return $user->fresh();
    }
}
