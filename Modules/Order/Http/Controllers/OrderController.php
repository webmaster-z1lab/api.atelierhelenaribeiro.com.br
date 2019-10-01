<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Http\Requests\OrderUpdateRequest;
use Modules\Order\Http\Requests\ShipRequest;
use Modules\Order\Http\Resources\OrderResource;
use Modules\Order\Models\Order;
use Modules\Order\Repositories\OrderRepository;

class OrderController extends ApiController
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    private $repository;

    /**
     * OrderController constructor.
     *
     * @param  \Modules\Order\Repositories\OrderRepository  $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return OrderResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Order\Http\Requests\OrderRequest  $request
     *
     * @return \Modules\Order\Http\Resources\OrderResource
     */
    public function store(OrderRequest $request): OrderResource
    {
        return OrderResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Order\Http\Resources\OrderResource
     */
    public function show(Order $order)
    {
        if ($this->ETagNotChanged($order)) return $this->notModifiedResponse();

        return OrderResource::make($order);
    }

    /**
     * @param  \Modules\Order\Http\Requests\OrderUpdateRequest  $request
     * @param  \Modules\Order\Models\Order                      $order
     *
     * @return \Modules\Order\Http\Resources\OrderResource
     */
    public function update(OrderUpdateRequest $request, Order $order): OrderResource
    {
        return OrderResource::make($this->repository->update($request->validated(), $order));
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Order $order): JsonResponse
    {
        $this->repository->delete($order);

        return $this->noContentResponse();
    }

    /**
     * @param  \Modules\Order\Http\Requests\ShipRequest  $request
     * @param  \Modules\Order\Models\Order               $order
     *
     * @return \Modules\Order\Http\Resources\OrderResource
     */
    public function ship(ShipRequest $request, Order $order): OrderResource
    {
        return OrderResource::make($this->repository->ship($request->validated(), $order));
    }
}
