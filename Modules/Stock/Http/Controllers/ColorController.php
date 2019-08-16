<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Modules\Stock\Http\Requests\ColorRequest;
use Modules\Stock\Http\Requests\ColorUpdateRequest;
use Modules\Stock\Http\Resources\ColorResource;
use Modules\Stock\Models\Color;
use Modules\Stock\Repositories\ColorRepository;

class ColorController extends ApiController
{
    /**
     * @var \Modules\Stock\Repositories\ColorRepository
     */
    private $repository;

    /**
     * ColorController constructor.
     *
     * @param  \Modules\Stock\Repositories\ColorRepository  $repository
     */
    public function __construct(ColorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ColorResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Stock\Http\Requests\ColorRequest  $request
     *
     * @return \Modules\Stock\Http\Resources\ColorResource
     */
    public function store(ColorRequest $request)
    {
        return ColorResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Stock\Models\Color  $color
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Stock\Http\Resources\ColorResource
     */
    public function show(Color $color)
    {
        if ($this->ETagNotChanged($color)) return $this->notModifiedResponse();

        return ColorResource::make($color);
    }

    /**
     * @param  \Modules\Stock\Http\Requests\ColorUpdateRequest  $request
     * @param  \Modules\Stock\Models\Color                      $color
     *
     * @return \Modules\Stock\Http\Resources\ColorResource
     */
    public function update(ColorUpdateRequest $request, Color $color): ColorResource
    {
        return ColorResource::make($this->repository->update($request->validated(), $color));
    }

    /**
     * @param  \Modules\Stock\Models\Color  $color
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Color $color): JsonResponse
    {
        $this->repository->delete($color);

        return $this->noContentResponse();
    }
}
