<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\ChangePasswordRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;

/**
 * Class UserController
 *
 * @package Modules\User\Http\Controllers\Api
 *
 * @property-read \Modules\User\Repositories\UserRepository $repository
 */
class UserController extends ApiController
{
    protected $repository;

    /**
     * UserController constructor.
     *
     * @param  \Modules\User\Repositories\UserRepository  $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     */
    public function index() { }

    /**
     * @param  \Modules\User\Models\User  $user
     *
     * @return \Modules\User\Http\Resources\UserResource
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) { }

    /**
     * @param  \Illuminate\Http\Request   $request
     * @param  \Modules\User\Models\User  $user
     */
    public function update(Request $request, User $user) { }

    /**
     * @param  \Modules\User\Models\User  $user
     */
    public function destroy(User $user) { }

    /**
     * @param  \Modules\User\Http\Requests\ChangePasswordRequest  $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return UserResource::make($this->repository->changePassword($request->get('password')));
    }
}
