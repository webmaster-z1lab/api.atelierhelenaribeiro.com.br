<?php

namespace App\Traits;

use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

trait PrepareProducts
{
    use UpdateProductsStatus;
    /**
     * @var int
     */
    protected $total_price;

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $items
     * @param  bool                           $test_amount
     *
     * @return array
     */
    protected function prepareProducts(Packing $packing, array $items, bool $test_amount = TRUE): array
    {
        $products = [];
        $this->total_price = 0;
        foreach ($items as $item) {
            $item['amount'] = (int) $item['amount'];
            if ($test_amount && $packing->products()
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

        return  $this->prepareProducts($packing->fresh(), $items, FALSE);
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  array                        $items
     * @param  bool                         $test_amount
     *
     * @return array
     */
    protected function prepareProductsFromPayroll(Visit $visit, array $items, bool $test_amount = TRUE): array
    {
        $products = [];
        foreach ($items as $item) {
            $item['amount'] = (int) $item['amount'];
            if ($test_amount && Payroll::where('customer_id', $visit->customer_id)
                                    ->where('reference', $item['reference'])
                                    ->where('status', ProductStatus::ON_CONSIGNMENT_STATUS)
                                    ->count() < $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }

            /** @var \Illuminate\Database\Eloquent\Collection $merchandises */
            $merchandises = Payroll::where('customer_id', $visit->customer_id)
                ->where('reference', $item['reference'])
                ->where('status', ProductStatus::ON_CONSIGNMENT_STATUS)
                ->orderBy('date')
                ->take($item['amount']);

            $products = array_merge($products, $merchandises->modelKeys());
        }

        return $products;
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  array                        $items
     * @param  string                       $status
     *
     * @return array
     */
    protected function updateProductsFromPayroll(Visit $visit, array $items, string $status): array
    {
        foreach ($items as $item) {
            $reserved_amount = Payroll::where('completion_visit_id', $visit->id)
                ->where('reference', $item['reference'])
                ->where('status', $status)->count();
            $available_amount = Payroll::where('reference', $item['reference'])
                ->where('status', ProductStatus::ON_CONSIGNMENT_STATUS)->count();;
            if ($available_amount + $reserved_amount < (int) $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }
        }

        $payrolls = Payroll::where('completion_visit_id', $visit->id)
            ->where('status', $status)->get();

        $payrolls->each(function (Payroll $payroll) {
            $payroll->unset('completion_date');
            $payroll->completion_visit()->dissociate();
            $payroll->update(['status' => ProductStatus::ON_CONSIGNMENT_STATUS]);
        });

        $this->updateStatus($visit->packing, $payrolls->pluck('product_id')->all(), ProductStatus::ON_CONSIGNMENT_STATUS);

        return  $this->prepareProductsFromPayroll($visit, $items, FALSE);
    }
}
