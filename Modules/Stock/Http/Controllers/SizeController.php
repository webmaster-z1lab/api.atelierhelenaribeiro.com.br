<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Stock\Http\Requests\SizeRequest;
use Modules\Stock\Http\Requests\SizeUpdateRequest;
use Modules\Stock\Http\Resources\SizeResource;
use Modules\Stock\Models\Size;
use Modules\Stock\Repositories\SizeRepository;

class SizeController extends ApiController
{
    /**
     * @var \Modules\Stock\Repositories\SizeRepository
     */
    private $repository;

    /**
     * SizeController constructor.
     *
     * @param  \Modules\Stock\Repositories\SizeRepository  $repository
     */
    public function __construct(SizeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SizeResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Stock\Http\Requests\SizeRequest  $request
     *
     * @return \Modules\Stock\Http\Resources\SizeResource
     */
    public function store(SizeRequest $request): SizeResource
    {
        return SizeResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Stock\Models\Size  $size
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Stock\Http\Resources\SizeResource
     */
    public function show(Size $size)
    {
        if ($this->ETagNotChanged($size)) return $this->notModifiedResponse();

        return SizeResource::make($size);
    }

    /**
     * @param  \Modules\Stock\Http\Requests\SizeUpdateRequest  $request
     * @param  \Modules\Stock\Models\Size                      $size
     *
     * @return \Modules\Stock\Http\Resources\SizeResource
     */
    public function update(SizeUpdateRequest $request, Size $size): SizeResource
    {
        return SizeResource::make($this->repository->update($request->validated(), $size));
    }

    /**
     * @param  \Modules\Stock\Models\Size  $size
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Size $size)
    {
        $this->repository->delete($size);

        return $this->noContentResponse();
    }
}
