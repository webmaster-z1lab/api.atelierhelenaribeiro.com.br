<?php

namespace Modules\Sales\Repositories;

use App\Models\Price;
use App\Traits\AggregateProducts;
use App\Traits\PrepareProducts;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Catalog\Models\Template;
use Modules\Sales\Jobs\AddProductsToPacking;
use Modules\Sales\Jobs\RemoveProductsFromPacking;
use Modules\Sales\Models\Refund;
use Modules\Sales\Models\Information;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\ProductStatus;
use Modules\Stock\Models\Size;
use Modules\Stock\Repositories\ProductRepository;

class RefundRepository
{
    use PrepareProducts, AggregateProducts, \App\Traits\RemoveProductsFromPacking;

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(Visit $visit)
    {
        return $this->aggregateProductsByVisit(Refund::class, [
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

        $this->createRefunds($data['products'], $visit);

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

        $productsToRemove = Refund::where('visit_id', $visit->id)->get(['product_id'])->pluck('product_id')->all();

        $this->removeProducts($visit->packing, $productsToRemove, FALSE);

        Refund::where('visit_id', $visit->id)->delete();

        $this->createRefunds($data['products'], $visit);

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

        $productsToRemove = Refund::where('visit_id', $visit->id)->get(['product_id'])->pluck('product_id')->all();

        RemoveProductsFromPacking::dispatch($visit->packing, $productsToRemove, FALSE);

        return Refund::where('visit_id', $visit->id)->delete();
    }

    /**
     * @param  array                        $products
     * @param  \Modules\Sales\Models\Visit  $visit
     */
    private function createRefunds(array $products, Visit &$visit): void
    {
        $refunds = collect([]);
        foreach ($products as $product) {
            $sales = Sale::where('customer_id', $visit->customer_id)
                ->where('reference', $product['reference'])
                ->orderBy('date', 'desc')
                ->take((int) $product['amount'])
                ->get();

            $sales->each(function (Sale $sale) use (&$refunds, $visit) {
                $refunds->add([
                    'date'       => $visit->date,
                    'reference'  => $sale->reference,
                    'thumbnail'  => $sale->thumbnail,
                    'size'       => $sale->size,
                    'color'      => $sale->color,
                    'price'      => $sale->price,
                    'product_id' => $sale->product_id,
                ]);
            });

            if ($sales->count() < $product['amount']) {
                $this->createProducts($product['reference'], $product['amount'] - $sales->count(), $refunds, $visit->date);
            }
        }

        $refunds->each(function (array $data) use ($visit) {
            $refund = new Refund($data);

            $refund->visit()->associate($visit);
            $refund->seller()->associate($visit->seller_id);
            $refund->customer()->associate($visit->customer_id);
            $refund->product()->associate($data['product_id']);

            $refund->save();
        });

        $this->updatePrices($visit, $refunds->count(), $refunds->sum('price'));

        AddProductsToPacking::dispatch($visit->packing, $refunds->all());
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  int                          $amount
     * @param  int                          $refund_total
     */
    private function updatePrices(Visit &$visit, int $amount, int $refund_total): void
    {
        $total = $visit->total_price + $visit->refund->price - $refund_total;

        $visit->refund()->associate(new Information([
            'amount' => $amount,
            'price'  => $refund_total,
        ]));

        $data = [
            'total_price' => $total,
        ];

        if ($visit->status === Visit::CLOSED_STATUS) {
            $data['status'] = Visit::OPENED_STATUS;
        }

        $visit->update($data);
    }

    /**
     * @param  string                          $reference
     * @param  int                             $amount
     * @param  \Illuminate\Support\Collection  $refunds
     * @param  \Carbon\Carbon                  $date
     */
    private function createProducts(string $reference, int $amount, Collection &$refunds, Carbon $date): void
    {
        $references = explode('-', $reference);

        /** @var \Modules\Catalog\Models\Template $template */
        $template = Template::where('reference', $references[0])->first();
        /** @var \Modules\Stock\Models\Color $color */
        $color = Color::where('reference', $references[1])->first();
        /** @var \Modules\Stock\Models\Size $size */
        $size = Size::where('reference', $references[2])->first();
        $price = new Price([
            'price'      => (float) ($template->price->price / 100.0),
            'started_at' => $template->price->started_at,
        ]);

        $data = [
            'size'      => $size->name,
            'color'     => $color->name,
            'reference' => $reference,
            'status'    => ProductStatus::SOLD_STATUS,
        ];

        $productRepository = new ProductRepository();

        for ($i = 0; $i < $amount; $i++) {
            $product = $productRepository->createProduct($data, $template, $price);
            $refunds->add([
                'date'       => $date,
                'reference'  => $product->reference,
                'thumbnail'  => $product->thumbnail,
                'size'       => $product->size,
                'color'      => $product->color,
                'price'      => $product->price->price,
                'product_id' => $product->id,
            ]);
        }
    }
}
