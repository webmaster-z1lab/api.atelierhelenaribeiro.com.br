<?php

namespace Modules\Sales\Repositories;

use App\Traits\PrepareProducts;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class PayrollRepository
{
    use PrepareProducts;

    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        return Payroll::orderBy('date', 'desc')->take(30)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Sales\Models\Payroll
     */
    public function create(array $data): Payroll
    {
        $visit = Visit::find($data['visit']);

        abort_if($visit->payroll()->exists(), 400, 'Já existe uma consignação para essa visita.');

        $products = $this->prepareProducts($visit->packing, $data['products']);

        $payroll = new Payroll([
            'date'         => $visit->date,
            'total_amount' => count($products),
            'total_price'  => $this->total_price,
        ]);

        $payroll->visit()->associate($visit);
        $payroll->seller()->associate($visit->seller_id);
        $payroll->customer()->associate($visit->customer_id);
        foreach ($products as $product) {
            $payroll->products()->associate($product);
        }

        $payroll->save();

        UpdateProductsStatus::dispatch($visit->packing, $payroll->products->pluck('product_id')->all(), ProductStatus::ON_CONSIGNMENT_STATUS);

        return $payroll;
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Sales\Models\Payroll  $payroll
     *
     * @return \Modules\Sales\Models\Payroll
     */
    public function update(array $data, Payroll $payroll): Payroll
    {
        $packing = $payroll->visit->packing;

        foreach ($data['products'] as $item) {
            $packing_amount = $packing->products()->where('reference', $item['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])->count();
            $payroll_amount = $payroll->products()->where('reference', $item['reference'])->count();
            if ($packing_amount + $payroll_amount < (int) $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }
        }

        UpdateProductsStatus::dispatchNow($packing, $payroll->products->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);
        $payroll->products()->dissociate();

        $products = $this->prepareProducts($packing->fresh(), $data['products']);

        foreach ($products as $product) {
            $payroll->products()->associate($product);
        }

        $payroll->update([
            'total_amount' => count($products),
            'total_price'  => $this->total_price,
        ]);

        UpdateProductsStatus::dispatch($packing, collect($products)->pluck('product_id')->all(), ProductStatus::ON_CONSIGNMENT_STATUS);

        return $payroll;
    }

    /**
     * @param  \Modules\Sales\Models\Payroll  $payroll
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Payroll $payroll)
    {
        $packing = $payroll->visit->packing;

        abort_if(!is_null($packing->checked_out_at), 400, 'Já foi dado baixa no romaneio.');

        UpdateProductsStatus::dispatch($packing, $payroll->products->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);

        return $payroll->delete();
    }
}
