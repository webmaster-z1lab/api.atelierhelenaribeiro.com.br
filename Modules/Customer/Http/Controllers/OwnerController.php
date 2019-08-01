<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Customer\Http\Requests\OwnerRequest;
use Modules\Customer\Http\Requests\OwnerUpdateRequest;
use Modules\Customer\Http\Resources\OwnerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\Owner;
use Modules\Customer\Repositories\OwnerRepository;

class OwnerController extends ApiController
{
    /**
     * @var \Modules\Customer\Repositories\OwnerRepository
     */
    private $repository;

    /**
     * OwnerController constructor.
     *
     * @param  \Modules\Customer\Repositories\OwnerRepository  $repository
     */
    public function __construct(OwnerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Customer $customer)
    {
        return OwnerResource::collection($this->repository->all($customer));
    }

    /**
     * @param  \Modules\Customer\Http\Requests\OwnerRequest  $request
     * @param  \Modules\Customer\Models\Customer             $customer
     *
     * @return \Modules\Customer\Http\Resources\OwnerResource
     */
    public function store(OwnerRequest $request, Customer $customer)
    {
        return OwnerResource::make($this->repository->create($request->validated(), $customer));
    }

    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     * @param  \Modules\Customer\Models\Owner     $owner
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Customer\Http\Resources\OwnerResource
     */
    public function show(Customer $customer, Owner $owner)
    {
        if ($owner->customer_id !== $customer->id) abort(404);

        if ($this->ETagNotChanged($customer)) return $this->notModifiedResponse();

        return OwnerResource::make($owner);
    }

    /**
     * @param  \Modules\Customer\Http\Requests\OwnerUpdateRequest  $request
     * @param  \Modules\Customer\Models\Customer                   $customer
     * @param  \Modules\Customer\Models\Owner                      $owner
     *
     * @return \Modules\Customer\Http\Resources\OwnerResource
     */
    public function update(OwnerUpdateRequest $request, Customer $customer, Owner $owner)
    {
        if ($owner->customer_id !== $customer->id) abort(404);

        return OwnerResource::make($this->repository->update($request->validated(), $owner));
    }

    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     * @param  \Modules\Customer\Models\Owner     $owner
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Customer $customer, Owner $owner)
    {
        if ($owner->customer_id !== $customer->id) abort(404);

        $this->repository->delete($owner);

        return $this->noContentResponse();
    }
}
