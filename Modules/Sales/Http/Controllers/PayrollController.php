<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\PayrollRequest;
use Modules\Sales\Http\Requests\PayrollUpdateRequest;
use Modules\Sales\Http\Resources\PayrollResource;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Repositories\PayrollRepository;

class PayrollController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\PayrollRepository
     */
    private $repository;

    /**
     * PayrollController constructor.
     *
     * @param  \Modules\Sales\Repositories\PayrollRepository  $repository
     */
    public function __construct(PayrollRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PayrollResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PayrollRequest  $request
     *
     * @return \Modules\Sales\Http\Resources\PayrollResource
     */
    public function store(PayrollRequest $request): PayrollResource
    {
        return PayrollResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Sales\Models\Payroll  $payroll
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Sales\Http\Resources\PayrollResource
     */
    public function show(Payroll $payroll)
    {
        if ($this->ETagNotChanged($payroll)) return $this->notModifiedResponse();

        return PayrollResource::make($payroll);
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PayrollUpdateRequest  $request
     * @param  \Modules\Sales\Models\Payroll                      $payroll
     *
     * @return \Modules\Sales\Http\Resources\PayrollResource
     */
    public function update(PayrollUpdateRequest $request, Payroll $payroll): PayrollResource
    {
        return PayrollResource::make($this->repository->update($request->validated(), $payroll));
    }

    /**
     * @param  \Modules\Sales\Models\Payroll  $payroll
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Payroll $payroll)
    {
        $this->repository->delete($payroll);

        return $this->noContentResponse();
    }
}
