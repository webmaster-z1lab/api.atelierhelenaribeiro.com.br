<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Modules\Stock\Http\Requests\ProductRequest;
use Modules\Stock\Http\Requests\ProductUpdateRequest;
use Modules\Stock\Http\Resources\ProductResource;
use Modules\Stock\Models\Product;
use Modules\Stock\Repositories\ProductRepository;

class ProductController extends ApiController
{
    /**
     * @var \Modules\Stock\Repositories\ProductRepository
     */
    protected $repository;

    /**
     * TemplateController constructor.
     *
     * @param  \Modules\Stock\Repositories\ProductRepository  $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ProductResource::collection($this->repository->all());
    }

    /**
     * @param  \Modules\Stock\Http\Requests\ProductRequest  $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(ProductRequest $request)
    {
        return ProductResource::collection($this->repository->create($request->validated()));
    }

    /**
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return \Illuminate\Http\JsonResponse|\Modules\Stock\Http\Resources\ProductResource
     */
    public function show(Product $product)
    {
        if ($this->ETagNotChanged($product)) return $this->notModifiedResponse();

        return ProductResource::make($product);
    }

    /**
     * @param  \Modules\Stock\Http\Requests\ProductUpdateRequest  $request
     * @param  \Modules\Stock\Models\Product                      $product
     *
     * @return \Modules\Stock\Http\Resources\ProductResource
     */
    public function update(ProductUpdateRequest $request, Product $product): ProductResource
    {
        return ProductResource::make($this->repository->update($request->validated(), $product));
    }

    /**
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->repository->delete($product);

        return $this->noContentResponse();
    }

    /**
     * @param  \App\Models\Image              $image
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroyImage(Image $image, Product $product)
    {
        $image->delete();

        return $this->noContentResponse();
    }
}
