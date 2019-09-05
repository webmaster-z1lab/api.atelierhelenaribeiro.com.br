<?php

namespace App\Traits;

use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Product;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

trait PrepareProducts
{
    /**
     * @var int
     */
    protected $total_price;

    /**
     * @var \Modules\Sales\Models\Packing
     */
    protected $packing;

    protected function prepareProducts(Visit $visit, array $items): array
    {
        /** @var \Modules\Sales\Models\Packing $packing */
        $this->packing = Packing::where('seller_id', $visit->seller_id)->where(function ($query) {
            $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
        })->first();

        abort_if(is_null($this->packing), 400, 'Não existe romaneio em aberto para o vendedor.');

        $products = [];
        $this->total_price = 0;
        foreach ($items as $item) {
            $item['amount'] = intval($item['amount']);
            if ($this->packing->products()
                    ->where('reference', $item['reference'])
                    ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])
                    ->count() < $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }

            $merchandises = $this->packing->products()
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
}
