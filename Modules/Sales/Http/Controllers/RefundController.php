<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\RefundRequest;
use Modules\Sales\Http\Requests\RefundUpdateRequest;
use Modules\Sales\Http\Resources\ProductResource;
use Modules\Sales\Http\Resources\VisitResource;
use Modules\Sales\Models\Visit;
use Modules\Sales\Repositories\RefundRepository;

class RefundController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\RefundRepository
     */
    private $repository;

    public function __construct(RefundRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  \Modules\Sales\Http\Requests\RefundRequest  $request
     * @param  \Modules\Sales\Models\Visit                 $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function store(RefundRequest $request, Visit $visit): VisitResource
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
     * @param  \Modules\Sales\Http\Requests\RefundUpdateRequest  $request
     * @param  \Modules\Sales\Models\Visit                       $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function update(RefundUpdateRequest $request, Visit $visit): VisitResource
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
