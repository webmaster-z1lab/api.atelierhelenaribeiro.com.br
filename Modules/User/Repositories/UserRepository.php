<?php


namespace Modules\User\Repositories;

/**
 * Class UserRepository
 *
 * @package Modules\User\Repositories
 *
 * @property-read \Modules\User\Models\User $model
 */
class UserRepository
{
    /**
     * @var \Modules\User\Models\User
     */
    protected $model;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->model = \Auth::guard('api')->user();
    }

    /**
     * @param  string  $password
     *
     * @return \Modules\User\Models\User|null
     */
    public function changePassword(string $password)
    {
        $password = \Hash::make($password);

        $this->model->update(compact('password'));

        return $this->model->fresh();
    }
}
