<?php

namespace Modules\Sales\Repositories;

use App\Traits\PrepareProducts;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Packing;
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
        $products = $this->prepareProducts($visit, $data['products']);

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

        UpdateProductsStatus::dispatch($this->packing, collect($products)->pluck('product_id')->all(), ProductStatus::SOLD_STATUS);

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
        $packing = Packing::where('seller_id', $sale->seller_id)
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })
            ->first();

        abort_if(is_null($packing), 400, 'Não existe um romaneio em aberto para o vendedor.');

        UpdateProductsStatus::dispatch($packing, $sale->products->pluck('product_id')->all(), ProductStatus::IN_TRANSIT_STATUS);

        return $sale->delete();
    }
}
