<?php

namespace Modules\Sales\Repositories;

use App\Traits\AggregateProducts;
use App\Traits\PrepareProducts;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class SaleRepository
{
    use PrepareProducts, AggregateProducts;

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(Visit $visit)
    {
        return $this->aggregateProductsByVisit(Sale::class, [
            'visit_id' => $visit->id,
            '$or'      => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
        ]);
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Modules\Sales\Models\Visit
     */
    public function create(array $data, Visit $visit): Visit
    {
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'Essa visita já  foi finalizada.');

        $products = $this->prepareProducts($visit->packing, $data['products']);

        $this->createSales($products, $visit);

        $this->updatePrices($visit, count($products), $this->total_price);

        UpdateProductsStatus::dispatch($visit->packing, collect($products)->pluck('product_id')->all(), ProductStatus::SOLD_STATUS);

        return $visit;
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Modules\Sales\Models\Visit
     */
    public function update(array $data, Visit $visit): Visit
    {
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'Essa visita já  foi finalizada.');

        $products = $this->updateProducts(Sale::class, $visit, $data['products']);

        $this->createSales($products, $visit);

        $this->updatePrices($visit, count($products), $this->total_price);

        UpdateProductsStatus::dispatch($visit->packing, collect($products)->pluck('product_id')->all(), ProductStatus::SOLD_STATUS);

        return $visit;
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return mixed
     */
    public function delete(Visit $visit)
    {
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'Essa visita já  foi finalizada.');

        $this->updatePrices($visit, 0, 0);

        UpdateProductsStatus::dispatch($visit->packing, Sale::where('visit_id', $visit->id)->get()->pluck('product_id')->all(),
            ProductStatus::IN_TRANSIT_STATUS);

        return Sale::where('visit_id', $visit->id)->delete();
    }

    /**
     * @param  array                        $products
     * @param  \Modules\Sales\Models\Visit  $visit
     */
    private function createSales(array $products, Visit $visit): void
    {
        foreach ($products as $product) {
            $product['date'] = $visit->date;

            $sale = new Sale($product);

            $sale->visit()->associate($visit);
            $sale->seller()->associate($visit->seller_id);
            $sale->customer()->associate($visit->customer_id);
            $sale->product()->associate($product['product_id']);

            $sale->save();
        }
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  int                          $amount
     * @param  int                          $sale_total
     */
    private function updatePrices(Visit &$visit, int $amount, int $sale_total): void
    {
        $total = $visit->total_price + $sale_total - $visit->sale->price;

        $total_amount = $visit->total_amount + $amount - $visit->sale->amount;

        $visit->sale->fill([
            'amount' => $amount,
            'price'  => $sale_total,
        ]);

        $visit->sale->save();

        $data = [
            'total_price'  => $total,
            'total_amount' => $total_amount,
        ];

        if ($visit->status === Visit::CLOSED_STATUS) {
            $data['status'] = Visit::OPENED_STATUS;
        }

        $visit->fill($data);

        $visit->save();
    }
}
