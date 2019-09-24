<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\PayrollRequest;
use Modules\Sales\Http\Requests\PayrollUpdateRequest;
use Modules\Sales\Http\Resources\PayrollResource;
use Modules\Sales\Http\Resources\ProductResource;
use Modules\Sales\Http\Resources\VisitResource;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Visit;
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
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Visit $visit)
    {
        return ProductResource::collection($this->repository->all($visit));
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PayrollRequest  $request
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function store(PayrollRequest $request, Visit $visit): VisitResource
    {
        return VisitResource::make($this->repository->create($request->validated(), $visit));
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Visit $visit)
    {
        return ProductResource::collection($this->repository->all($visit));
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PayrollUpdateRequest  $request
     * @param  \Modules\Sales\Models\Visit                        $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function update(PayrollUpdateRequest $request, Visit $visit): VisitResource
    {
        return VisitResource::make($this->repository->update($request->validated(), $visit));
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Visit $visit)
    {
        $this->repository->delete($visit);

        return $this->noContentResponse();
    }
}
