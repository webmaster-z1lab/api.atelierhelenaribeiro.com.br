<?php

namespace Modules\Sales\Repositories;

use App\Traits\AggregateProducts;
use App\Traits\PrepareProducts;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Information;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class PayrollSaleRepository
{
    use PrepareProducts, AggregateProducts;

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(Visit $visit)
    {
        return $this->aggregateProductsByVisit(Payroll::class, [
            'completion_visit_id' => $visit->id,
            'status'              => ProductStatus::SOLD_STATUS,
            '$or'                 => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
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

        $products = $this->prepareProductsFromPayroll($visit, $data['products']);

        $this->sellPayrolls($products, $visit);

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

        $products = $this->updateProductsFromPayroll($visit, $data['products'], ProductStatus::SOLD_STATUS);

        $this->sellPayrolls($products, $visit);

        return $visit;
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return int
     */
    public function delete(Visit $visit)
    {
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'Essa visita já  foi finalizada.');

        $this->updatePrices($visit, 0, 0);

        $payrolls = Payroll::where('completion_visit_id', $visit->id)
            ->where('status', ProductStatus::SOLD_STATUS)->get();

        $updated = 0;

        $payrolls->each(function (Payroll $payroll) use (&$updated) {
            $payroll->completion_visit()->dissociate();
            $payroll->unset('completion_date');
            if ($payroll->update([
                'status'          => ProductStatus::ON_CONSIGNMENT_STATUS,
            ])) $updated++;
        });

        UpdateProductsStatus::dispatch($visit->packing, $payrolls->pluck('product_id')->all(), ProductStatus::ON_CONSIGNMENT_STATUS, FALSE);

        return $updated;
    }

    /**
     * @param  array                        $products
     * @param  \Modules\Sales\Models\Visit  $visit
     */
    private function sellPayrolls(array $products, Visit &$visit): void
    {
        $payrolls = Payroll::whereKey($products)->get();

        $payrolls->each(function (Payroll $payroll) use ($visit) {
            $payroll->completion_visit()->associate($visit);
            $payroll->update([
                'status'          => ProductStatus::SOLD_STATUS,
                'completion_date' => $visit->date,
            ]);
        });

        $this->updatePrices($visit, $payrolls->count(), $payrolls->sum('price'));

        UpdateProductsStatus::dispatch($visit->packing, $payrolls->pluck('product_id')->all(), ProductStatus::SOLD_STATUS, FALSE);
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  int                          $amount
     * @param  int                          $sale_total
     */
    private function updatePrices(Visit &$visit, int $amount, int $sale_total): void
    {
        $total = $visit->total_price + $sale_total - $visit->payroll_sale->price;

        $total_amount = $visit->total_amount + $amount - $visit->payroll_sale->amount;

        $visit->payroll_sale()->associate(new Information([
            'amount' => $amount,
            'price'  => $sale_total,
        ]));

        $data = [
            'total_price'  => $total,
            'total_amount' => $total_amount,
        ];

        if ($visit->status === Visit::CLOSED_STATUS) {
            $data['status'] = Visit::OPENED_STATUS;
        }

        $visit->update($data);
    }
}
