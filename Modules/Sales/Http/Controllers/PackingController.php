<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\CheckOutRequest;
use Modules\Sales\Http\Requests\PackingRequest;
use Modules\Sales\Http\Requests\PackingUpdateRequest;
use Modules\Sales\Http\Resources\PackingResource;
use Modules\Sales\Models\Packing;
use Modules\Sales\Repositories\PackingRepository;

class PackingController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\PackingRepository
     */
    protected $repository;

    /**
     * PackingController constructor.
     *
     * @param  \Modules\Sales\Repositories\PackingRepository  $repository
     */
    public function __construct(PackingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PackingResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PackingRequest  $request
     *
     * @return \Modules\Sales\Http\Resources\PackingResource
     */
    public function store(PackingRequest $request): PackingResource
    {
        return PackingResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Sales\Http\Resources\PackingResource
     */
    public function show(Packing $packing)
    {
        if ($this->ETagNotChanged($packing)) return $this->notModifiedResponse();

        return PackingResource::make($packing);
    }

    /**
     * @param  \Modules\Sales\Http\Requests\PackingUpdateRequest  $request
     * @param  \Modules\Sales\Models\Packing                      $packing
     *
     * @return \Modules\Sales\Http\Resources\PackingResource
     */
    public function update(PackingUpdateRequest $request, Packing $packing): PackingResource
    {
        return PackingResource::make($this->repository->update($request->validated(), $packing));
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Packing $packing)
    {
        $this->repository->delete($packing);

        return $this->noContentResponse();
    }

    /**
     * @param  \Modules\Sales\Http\Requests\CheckOutRequest  $request
     * @param  \Modules\Sales\Models\Packing                 $packing
     *
     * @return \Modules\Sales\Http\Resources\PackingResource
     */
    public function checkOut(CheckOutRequest $request, Packing $packing): PackingResource
    {
        return PackingResource::make($this->repository->checkOut($request->validated(), $packing));
    }

    /**
     * @return \Modules\Sales\Http\Resources\PackingResource
     */
    public function current(): PackingResource
    {
        return PackingResource::make($this->repository->current());
    }
}
