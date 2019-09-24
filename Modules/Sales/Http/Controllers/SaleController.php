<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\SaleRequest;
use Modules\Sales\Http\Requests\SaleUpdateRequest;
use Modules\Sales\Http\Resources\ProductResource;
use Modules\Sales\Http\Resources\SaleResource;
use Modules\Sales\Http\Resources\VisitResource;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;
use Modules\Sales\Repositories\SaleRepository;

class SaleController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\SaleRepository
     */
    private $repository;

    public function __construct(SaleRepository $repository)
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
     * @param  \Modules\Sales\Http\Requests\SaleRequest  $request
     * @param  \Modules\Sales\Models\Visit               $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function store(SaleRequest $request, Visit $visit): VisitResource
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
     * @param  \Modules\Sales\Http\Requests\SaleUpdateRequest  $request
     * @param  \Modules\Sales\Models\Visit                     $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function update(SaleUpdateRequest $request, Visit $visit): VisitResource
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
