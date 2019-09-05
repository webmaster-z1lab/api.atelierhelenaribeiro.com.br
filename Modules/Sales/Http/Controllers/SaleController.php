<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\SaleRequest;
use Modules\Sales\Http\Resources\SaleResource;
use Modules\Sales\Models\Sale;
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SaleResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Sales\Http\Requests\SaleRequest  $request
     *
     * @return \Modules\Sales\Http\Resources\SaleResource
     */
    public function store(SaleRequest $request): SaleResource
    {
        return SaleResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Sales\Models\Sale  $sale
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Sales\Http\Resources\SaleResource
     */
    public function show(Sale $sale)
    {
        if ($this->ETagNotChanged($sale)) return $this->notModifiedResponse();

        return SaleResource::make($sale);
    }

    /**
     * @param  \Modules\Sales\Models\Sale  $sale
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Sale $sale)
    {
        $this->repository->delete($sale);

        return $this->noContentResponse();
    }
}
