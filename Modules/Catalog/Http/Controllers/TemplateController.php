<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Modules\Catalog\Http\Requests\TemplateRequest;
use Modules\Catalog\Http\Requests\TemplateUpdateRequest;
use Modules\Catalog\Http\Resources\TemplateResource;
use Modules\Catalog\Models\Template;
use Modules\Catalog\Repositories\TemplateRepository;

class TemplateController extends ApiController
{
    /**
     * @var \Modules\Catalog\Repositories\TemplateRepository
     */
    protected $repository;

    /**
     * TemplateController constructor.
     *
     * @param  \Modules\Catalog\Repositories\TemplateRepository  $repository
     */
    public function __construct(TemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return TemplateResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Catalog\Http\Requests\TemplateRequest  $request
     *
     * @return \Modules\Catalog\Http\Resources\TemplateResource
     */
    public function store(TemplateRequest $request): TemplateResource
    {
        return TemplateResource::make($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Catalog\Http\Resources\TemplateResource
     */
    public function show(Template $template)
    {
        if ($this->ETagNotChanged($template)) return $this->notModifiedResponse();

        return TemplateResource::make($template);
    }

    /**
     * @param  \Modules\Catalog\Http\Requests\TemplateUpdateRequest  $request
     * @param  \Modules\Catalog\Models\Template                      $template
     *
     * @return \Modules\Catalog\Http\Resources\TemplateResource
     */
    public function update(TemplateUpdateRequest $request, Template $template): TemplateResource
    {
        return TemplateResource::make($this->repository->update($request->validated(), $template));
    }

    /**
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Template $template): JsonResponse
    {
        $this->repository->delete($template);

        return $this->noContentResponse();
    }

    /**
     * @param  \App\Models\Image                 $image
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroyImage(Image $image, Template $template): JsonResponse
    {
        $image->delete();

        return $this->noContentResponse();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function reference(): JsonResponse
    {
        return new JsonResponse(['reference' => $this->repository->getNewReference()], 200);
    }
}
