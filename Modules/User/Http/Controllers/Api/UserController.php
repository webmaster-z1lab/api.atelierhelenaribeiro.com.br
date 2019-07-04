<?php

namespace Modules\User\Http\Controllers\Api;

use Modules\User\Http\Requests\ChangePasswordRequest;
use Modules\User\Repositories\UserRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

/**
 * Class UserController
 *
 * @package Modules\User\Http\Controllers\Api
 *
 * @property-read \Modules\User\Repositories\UserRepository $repository
 */
class UserController extends ApiController
{
    /**
     * UserController constructor.
     *
     * @param  \Modules\User\Repositories\UserRepository  $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository, 'User');
    }

    /**
     * @param  \Modules\User\Http\Requests\ChangePasswordRequest  $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->makeResource($this->repository->changePassword($request->get('password')));
    }
}
