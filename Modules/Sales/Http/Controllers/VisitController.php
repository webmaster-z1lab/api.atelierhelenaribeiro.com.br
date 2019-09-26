<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\ApiController;
use Modules\Sales\Http\Requests\CloseVisitRequest;
use Modules\Sales\Http\Requests\VisitRequest;
use Modules\Sales\Http\Requests\VisitUpdateRequest;
use Modules\Sales\Http\Resources\VisitResource;
use Modules\Sales\Models\Visit;
use Modules\Sales\Repositories\VisitRepository;

class VisitController extends ApiController
{
    /**
     * @var \Modules\Sales\Repositories\VisitRepository
     */
    private $repository;

    /**
     * VisitController constructor.
     *
     * @param  \Modules\Sales\Repositories\VisitRepository  $repository
     */
    public function __construct(VisitRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return VisitResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Sales\Http\Requests\VisitRequest  $request
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function store(VisitRequest $request): VisitResource
    {
        return VisitResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Sales\Http\Resources\VisitResource
     */
    public function show(Visit $visit)
    {
        if ($this->ETagNotChanged($visit)) return $this->notModifiedResponse();

        return VisitResource::make($visit);
    }

    /**
     * @param  \Modules\Sales\Http\Requests\VisitUpdateRequest  $request
     * @param  \Modules\Sales\Models\Visit                      $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function update(VisitUpdateRequest $request, Visit $visit): VisitResource
    {
        return VisitResource::make($this->repository->update($request->validated(), $visit));
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Visit $visit)
    {
        $this->repository->delete($visit);

        return $this->noContentResponse();
    }

    /**
     * @param  \Modules\Sales\Http\Requests\CloseVisitRequest  $request
     * @param  \Modules\Sales\Models\Visit                     $visit
     *
     * @return \Modules\Sales\Http\Resources\VisitResource
     */
    public function close(CloseVisitRequest $request, Visit $visit): VisitResource
    {
        return VisitResource::make($this->repository->close($request->validated(), $visit));
    }
}
