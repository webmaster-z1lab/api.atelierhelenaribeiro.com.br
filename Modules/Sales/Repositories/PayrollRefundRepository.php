<?php

namespace Modules\Sales\Repositories;

use App\Traits\AggregateProducts;
use App\Traits\PrepareProducts;
use Modules\Sales\Jobs\AddProductsToPacking;
use Modules\Sales\Jobs\RemoveProductsFromPacking;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Information;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class PayrollRefundRepository
{
    use PrepareProducts, AggregateProducts, \App\Traits\RemoveProductsFromPacking;

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(Visit $visit)
    {
        return $this->aggregateProductsByVisit(Payroll::class, [
            'completion_visit_id' => $visit->id,
            'status'              => ProductStatus::RETURNED_STATUS,
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

        $this->createPayrollRefunds($data['products'], $visit);

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

        $productsToRemove = Payroll::where('completion_visit_id', $visit->id)
            ->where('status', ProductStatus::RETURNED_STATUS)->get();

        $this->removeProducts($visit->packing, $productsToRemove->pluck('product_id')->all(), FALSE);

        $productsToRemove->each(function (Payroll $payroll) {
            $payroll->unset('completion_date');
            $payroll->completion_visit()->dissociate();
            $payroll->update([
                'status' => ProductStatus::ON_CONSIGNMENT_STATUS,
            ]);
        });

        $this->createPayrollRefunds($data['products'], $visit);

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

        $productsToRemove = Payroll::where('completion_visit_id', $visit->id)
            ->where('status', ProductStatus::RETURNED_STATUS)->get();

        RemoveProductsFromPacking::dispatch($visit->packing, $productsToRemove->pluck('product_id')->all(), TRUE);

        $productsToRemove->each(function (Payroll $payroll) {
            $payroll->unset('completion_date');
            $payroll->completion_visit()->dissociate();
            $payroll->update([
                'status' => ProductStatus::ON_CONSIGNMENT_STATUS,
            ]);
        });

        return $productsToRemove->count();
    }

    /**
     * @param  array                        $products
     * @param  \Modules\Sales\Models\Visit  $visit
     */
    private function createPayrollRefunds(array $products, Visit &$visit): void
    {
        $refunds = collect([]);
        foreach ($products as $product) {
            $payrolls = Payroll::where('customer_id', $visit->customer_id)
                ->where('status', ProductStatus::ON_CONSIGNMENT_STATUS)
                ->where('reference', $product['reference'])
                ->orderBy('date')
                ->take($product['amount'])
                ->get();

            $refunds = $refunds->merge($payrolls);

            if ($payrolls->count() < $product['amount']) {
                abort(400, 'Não há quantidade suficiente do produto em consignado com o cliente.');
            }
        }

        $refunds->each(function (Payroll $payroll) use ($visit) {
            $payroll->completion_visit()->associate($visit);
            $payroll->update([
                'completion_date' => $visit->date,
                'status'          => ProductStatus::RETURNED_STATUS,
            ]);
        });

        $this->updatePrices($visit, $refunds->count(), $refunds->sum('price'));

        AddProductsToPacking::dispatch($visit->packing, $refunds->map(function (Payroll $payroll) {
            return [
                'reference'  => $payroll->reference,
                'thumbnail'  => $payroll->thumbnail,
                'size'       => $payroll->size,
                'color'      => $payroll->color,
                'price'      => $payroll->price,
                'product_id' => $payroll->product_id,
            ];
        })->all());
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  int                          $amount
     * @param  int                          $payroll_refund_total
     */
    private function updatePrices(Visit &$visit, int $amount, int $payroll_refund_total): void
    {
        $visit->payroll_refund()->associate(new Information([
            'amount' => $amount,
            'price'  => $payroll_refund_total,
        ]));

        $visit->save();
    }
}
