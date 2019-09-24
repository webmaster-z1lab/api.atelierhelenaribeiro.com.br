<?php

namespace App\Traits;

use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Product;
use Modules\Sales\Models\Visit;
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
                $products[] = [
                    'product_id' => $merchandise->product_id,
                    'reference'  => $merchandise->reference,
                    'thumbnail'  => $merchandise->thumbnail,
                    'size'       => $merchandise->size,
                    'color'      => $merchandise->color,
                    'price'      => $merchandise->price,
                ];

                $this->total_price += $merchandise->price;
            }
        }

        return $products;
    }

    /**
     * @param  string                       $class
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  array                        $items
     *
     * @return array
     */
    protected function updateProducts(string $class, Visit $visit, array $items): array
    {
        $packing = $visit->packing;

        foreach ($items as $item) {
            $packing_amount = $packing->products()->where('reference', $item['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])->count();
            $reserved_amount = $class::where('visit_id', $visit->id)->where('reference', $item['reference'])->count();
            if ($packing_amount + $reserved_amount < (int) $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }
        }

        $this->updateStatus($packing, $class::where('visit_id', $visit->id)->get()->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);
        $class::where('visit_id', $visit->id)->delete();

        return  $this->prepareProducts($packing->fresh(), $items);
    }
}
