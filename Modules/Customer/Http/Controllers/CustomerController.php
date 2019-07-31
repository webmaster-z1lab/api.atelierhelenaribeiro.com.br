<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Customer\Http\Requests\CustomerRequest;
use Modules\Customer\Http\Requests\CustomerUpdateRequest;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;

class CustomerController extends ApiController
{
    /**
     * @var \Modules\Customer\Repositories\CustomerRepository
     */
    private $repository;

    /**
     * CustomerController constructor.
     *
     * @param  \Modules\Customer\Repositories\CustomerRepository  $repository
     */
    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CustomerResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Customer\Http\Requests\CustomerRequest  $request
     *
     * @return \Modules\Customer\Http\Resources\CustomerResource
     */
    public function store(CustomerRequest $request)
    {
        return CustomerResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Customer\Http\Resources\CustomerResource
     */
    public function show(Customer $customer)
    {
        if ($this->ETagNotChanged($customer)) return $this->notModifiedResponse();

        return CustomerResource::make($customer);
    }

    /**
     * @param  \Modules\Customer\Http\Requests\CustomerUpdateRequest  $request
     * @param  \Modules\Customer\Models\Customer                      $customer
     *
     * @return \Modules\Customer\Http\Resources\CustomerResource
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        return CustomerResource::make($this->repository->update($request->validated(), $customer));
    }

    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Customer $customer)
    {
        $this->repository->delete($customer);

        return $this->noContentResponse();
    }
}
