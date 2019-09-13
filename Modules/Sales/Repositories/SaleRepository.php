<?php

namespace Modules\Sales\Repositories;

use App\Traits\PrepareProducts;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class SaleRepository
{
    use PrepareProducts;

    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if (\Auth::user()->type === EmployeeTypes::TYPE_ADMIN) {
            return Sale::orderBy('date', 'desc')->take(30)->get();
        }

        return Sale::where('seller_id', \Auth::id())->orderBy('date', 'desc')->take(30)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Sales\Models\Sale
     */
    public function create(array $data): Sale
    {
        $data['discount'] = intval(floatval($data['discount']) * 100);
        $visit = Visit::find($data['visit']);

        abort_if($visit->sale()->exists(), 400, 'Já existe uma venda para essa visita.');

        $products = $this->prepareProducts($visit->packing, $data['products']);

        abort_if($data['discount'] > $this->total_price, 400, 'O desconto é maior do que o total da venda.');

        $sale = new Sale([
            'date'         => $visit->date,
            'discount'     => $data['discount'],
            'total_amount' => count($products),
            'total_price'  => $this->total_price,
        ]);

        $sale->visit()->associate($visit);
        $sale->seller()->associate($visit->seller_id);
        $sale->customer()->associate($visit->customer_id);
        foreach ($products as $product) {
            $sale->products()->associate($product);
        }

        $sale->save();

        UpdateProductsStatus::dispatch($visit->packing, collect($products)->pluck('product_id')->all(), ProductStatus::SOLD_STATUS);

        return $sale;
    }

    /**
     * @param  array                       $data
     * @param  \Modules\Sales\Models\Sale  $sale
     *
     * @return \Modules\Sales\Models\Sale
     */
    public function update(array $data, Sale $sale): Sale
    {
        $data['discount'] = intval(floatval($data['discount']) * 100);

        $packing = $sale->visit->packing;

        foreach ($data['products'] as $item) {
            $packing_amount = $packing->products()->where('reference', $item['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])->count();
            $sale_amount = $sale->products()->where('reference', $item['reference'])->count();
            if ($packing_amount + $sale_amount < (int) $item['amount']) {
                abort(400, "A quantidade do produto {$item['reference']} é maior do que a disponível.");
            }
        }

        UpdateProductsStatus::dispatchNow($packing, $sale->products->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);
        $sale->products()->dissociate();

        $products = $this->prepareProducts($packing->fresh(), $data['products']);

        abort_if($data['discount'] > $this->total_price, 400, 'O desconto é maior do que o total da venda.');

        foreach ($products as $product) {
            $sale->products()->associate($product);
        }

        $sale->update([
            'discount'     => $data['discount'],
            'total_amount' => count($products),
            'total_price'  => $this->total_price,
        ]);

        UpdateProductsStatus::dispatch($packing, collect($products)->pluck('product_id')->all(), ProductStatus::SOLD_STATUS);

        return $sale;
    }

    /**
     * @param  \Modules\Sales\Models\Sale  $sale
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Sale $sale)
    {
        $packing = $sale->visit->packing;

        abort_if(!is_null($packing->checked_out_at), 400, 'Já foi dado baixa no romaneio.');

        UpdateProductsStatus::dispatch($packing, $sale->products->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);

        return $sale->delete();
    }
}
