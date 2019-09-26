<?php

namespace Modules\Sales\Repositories;

use App\Traits\AggregateProducts;
use App\Traits\PrepareProducts;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class PayrollRepository
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

        $products = $this->prepareProducts($visit->packing, $data['products']);

        $this->createPayrolls($products, $visit);

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

        $products = $this->updateProducts(Payroll::class, $visit, $data['products']);

        $this->createPayrolls($products, $visit);

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

        $visit->payroll->fill([
            'amount' => 0,
            'price'  => 0,
        ]);

        $visit->payroll->save();

        $visit->save();

        UpdateProductsStatus::dispatch($visit->packing, Payroll::where('visit_id', $visit->id)->get()->pluck('product_id')->all(),
            ProductStatus::IN_TRANSIT_STATUS);

        return Payroll::where('visit_id', $visit->id)->delete();
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  string                       $status
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByStatus(Visit $visit, string $status)
    {
        if ($status === 'available') {
            return $this->aggregateProductsByStatus(Payroll::class, [
                'visit_id'    => ['$ne' => $visit->id],
                'customer_id' => $visit->customer_id,
                'status'      => ProductStatus::ON_CONSIGNMENT_STATUS,
                '$or'         => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
            ]);
        }

        return $this->aggregateProductsByStatus(Payroll::class, [
            'completion_visit_id' => $visit->id,
            'status'              => $status === 'sold' ? ProductStatus::SOLD_STATUS : ProductStatus::RETURNED_STATUS,
            '$or'                 => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
        ]);
    }

    /**
     * @param  array                        $products
     * @param  \Modules\Sales\Models\Visit  $visit
     */
    private function createPayrolls(array $products, Visit &$visit): void
    {
        foreach ($products as $product) {
            $product['date'] = $visit->date;

            $payroll = new Payroll($product);

            $payroll->product()->associate($product['product_id']);
            $payroll->visit()->associate($visit);
            $payroll->seller()->associate($visit->seller_id);
            $payroll->customer()->associate($visit->customer_id);

            $payroll->save();
        }

        $visit->payroll->fill([
            'amount' => count($products),
            'price'  => $this->total_price,
        ]);

        $visit->payroll->save();

        $visit->save();

        UpdateProductsStatus::dispatch($visit->packing, collect($products)->pluck('product_id')->all(), ProductStatus::ON_CONSIGNMENT_STATUS);
    }
}
