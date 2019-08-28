<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Modules\Employee\Http\Requests\EmployeeRequest;
use Modules\Employee\Http\Requests\EmployeeUpdateRequest;
use Modules\Employee\Http\Resources\EmployeeResource;
use Modules\Employee\Repositories\EmployeeRepository;
use Modules\User\Models\User;

class EmployeeController extends ApiController
{
    /**
     * @var \Modules\Employee\Repositories\EmployeeRepository
     */
    protected $repository;

    /**
     * EmployeeController constructor.
     *
     * @param  \Modules\Employee\Repositories\EmployeeRepository  $repository
     */
    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
        $this->authorizeResource(User::class, 'employee');
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return EmployeeResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Employee\Http\Requests\EmployeeRequest  $request
     *
     * @return \Modules\Employee\Http\Resources\EmployeeResource
     */
    public function store(EmployeeRequest $request): EmployeeResource
    {
        return EmployeeResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\User\Models\User  $employee
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Employee\Http\Resources\EmployeeResource
     */
    public function show(User $employee)
    {
        if ($this->ETagNotChanged($employee)) return $this->notModifiedResponse();

        return EmployeeResource::make($employee);
    }

    /**
     * @param  \Modules\Employee\Http\Requests\EmployeeUpdateRequest  $request
     * @param  \Modules\User\Models\User                              $employee
     *
     * @return \Modules\Employee\Http\Resources\EmployeeResource
     */
    public function update(EmployeeUpdateRequest $request, User $employee): EmployeeResource
    {
        return EmployeeResource::make($this->repository->update($request->validated(), $employee));
    }

    /**
     * @param  \Modules\User\Models\User  $employee
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $employee): JsonResponse
    {
        $this->repository->delete($employee);

        return $this->noContentResponse();
    }
}
