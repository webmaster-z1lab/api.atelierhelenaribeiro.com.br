<?php

namespace App\Traits;

use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Product;
use Modules\Stock\Models\ProductStatus;

trait PrepareProducts
{
    use UpdateProductsStatus;
    /**
     * @var int
     */
    protected $total_price;

    protected function prepareProducts(Packing $packing, array $items): array
    {
        abort_if($packing->checked_out_at !== NULL, 400, 'Não existe romaneio em aberto para o vendedor.');

        $products = [];
        $this->total_price = 0;
        foreach ($items as $item) {
            $item['amount'] = intval($item['amount']);
            if ($packing->products()
                    ->where('reference', $item['reference'])
                    ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])
                    ->count() < $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }

            $merchandises = $packing->products()
                ->where('reference', $item['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])
                ->take($item['amount']);
            foreach ($merchandises as $merchandise) {
                /** @var \Modules\Sales\Models\Product $merchandise */
                $products[] = new Product([
                    'product_id' => $merchandise->product_id,
                    'reference'  => $merchandise->reference,
                    'thumbnail'  => $merchandise->thumbnail,
                    'size'       => $merchandise->size,
                    'color'      => $merchandise->color,
                    'price'      => $merchandise->price,
                ]);

                $this->total_price += $merchandise->price;
            }
        }

        return $products;
    }

    /**
     * @param  \Modules\Sales\Models\Sale|\Modules\Sales\Models\Payroll  $reserved
     * @param  array                                                     $items
     *
     * @return array
     */
    protected function updateProducts(&$reserved, array $items): array
    {
        $packing = $reserved->visit->packing;

        foreach ($items as $item) {
            $packing_amount = $packing->products()->where('reference', $item['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])->count();
            $reserved_amount = $reserved->products()->where('reference', $item['reference'])->count();
            if ($packing_amount + $reserved_amount < (int) $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }
        }

        $this->updateStatus($packing, $reserved->products->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);
        $reserved->products()->dissociate($reserved->products->modelKeys());

        return  $this->prepareProducts($packing->fresh(), $items);
    }
}
