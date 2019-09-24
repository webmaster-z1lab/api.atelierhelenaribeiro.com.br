<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\PayrollSaleRequest;
use Modules\Sales\Http\Requests\PayrollSaleUpdateRequest;
use Modules\Sales\Http\Resources\ProductResource;
use Modules\Sales\Http\Resources\VisitResource;
use Modules\Sales\Models\Visit;
use Modules\Sales\Repositories\PayrollSaleRepository;

class PayrollSaleController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\PayrollSaleRepository
     */
    private $repository;

    /**
     * PayrollController constructor.
     *
     * @param  \Modules\Sales\Repositories\PayrollSaleRepository  $repository
     */
    public function __construct(PayrollSaleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PayrollSaleRequest  $request
     * @param  \Modules\Sales\Models\Visit                      $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function store(PayrollSaleRequest $request, Visit $visit): VisitResource
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
     * @param  \Modules\Sales\Http\Requests\PayrollSaleUpdateRequest  $request
     * @param  \Modules\Sales\Models\Visit                            $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function update(PayrollSaleUpdateRequest $request, Visit $visit): VisitResource
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
