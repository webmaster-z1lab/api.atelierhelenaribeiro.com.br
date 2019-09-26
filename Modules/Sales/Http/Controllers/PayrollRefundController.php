<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\PayrollRefundRequest;
use Modules\Sales\Http\Requests\PayrollRefundUpdateRequest;
use Modules\Sales\Http\Resources\ProductResource;
use Modules\Sales\Http\Resources\VisitResource;
use Modules\Sales\Models\Visit;
use Modules\Sales\Repositories\PayrollRefundRepository;

class PayrollRefundController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\PayrollRefundRepository
     */
    private $repository;

    public function __construct(PayrollRefundRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PayrollRefundRequest  $request
     * @param  \Modules\Sales\Models\Visit                        $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function store(PayrollRefundRequest $request, Visit $visit): VisitResource
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
     * @param  \Modules\Sales\Http\Requests\PayrollRefundUpdateRequest  $request
     * @param  \Modules\Sales\Models\Visit                              $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function update(PayrollRefundUpdateRequest $request, Visit $visit): VisitResource
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
