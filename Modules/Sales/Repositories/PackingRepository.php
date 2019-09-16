<?php

namespace Modules\Sales\Repositories;

use Illuminate\Validation\ValidationException;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\CheckOutProducts;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\PaymentMethods;
use Modules\Sales\Models\Sale;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;

class PackingRepository
{
    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if (\Auth::user()->type === EmployeeTypes::TYPE_ADMIN) {
            return Packing::latest()->take(30)->get();
        }

        return Packing::where('seller_id', \Auth::id())->latest()->take(30)->get();
    }

    /**
     * @return \Modules\Sales\Models\Packing
     * @throws \Illuminate\Validation\ValidationException
     */
    public function current(): Packing
    {
        if (!\Request::filled('seller')) {
            throw ValidationException::withMessages([
                'seller' => [
                    'Vendedor não especificado.',
                ],
            ]);
        }

        $packing = Packing::where('seller_id', \Request::query('seller'))
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })->first();

        abort_if(is_null($packing), 404);

        return $packing;
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Sales\Models\Packing
     */
    public function create(array $data): Packing
    {
        if (Packing::where('seller_id', $data['seller'])
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })->exists()) {
            abort(400, 'Já existe um romaneio em aberto para esse vendedor.');
        }

        foreach ($data['products'] as $key => $item) {
            $data['products'][$key]['amount'] = $item['amount'] = intval($item['amount']);
            if (Product::where('reference', $item['reference'])->where('status', ProductStatus::AVAILABLE_STATUS)->count() < $item['amount']) {
                abort(400, "Não há peças suficientes do produto {$item['reference']}.");
            }
        }

        $packing = new Packing();

        foreach ($this->createProducts($data['products']) as $product) {
            $packing->products()->associate($product);
        }
        $packing->seller()->associate($data['seller']);

        $packing->save();

        return $packing;
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return \Modules\Sales\Models\Packing
     */
    public function update(array $data, Packing $packing): Packing
    {
        abort_if($packing->visits()->exists(), 400, 'Já existem visitas cadastradas para esse romaneio.');

        if (Packing::where('_id', '<>', $packing->id)
            ->where('seller_id', $data['seller'])
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })->exists()) {
            abort(400, 'Já existe um romaneio em aberto para esse vendedor.');
        }

        foreach ($data['products'] as $key => $item) {
            $data['products'][$key]['amount'] = $item['amount'] = intval($item['amount']);
            $available = Product::where('reference', $item['reference'])->where('status', ProductStatus::AVAILABLE_STATUS)->count();
            $available += $packing->products()->where('reference', $item['reference'])->count();
            if ($available < $item['amount']) {
                abort(400, "Não há peças suficientes do produto {$item['reference']}.");
            }
        }

        $this->releaseProducts($packing);

        $packing->seller()->associate($data['seller']);
        foreach ($this->createProducts($data['products']) as $product) {
            $packing->products()->associate($product);
        }

        $packing->save();

        return $packing;
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Packing $packing)
    {
        abort_if($packing->visits()->exists(), 400, 'Já existem visitas cadastradas para esse romaneio.');

        $this->releaseProducts($packing);

        return $packing->delete();
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return \Modules\Sales\Models\Packing
     */
    public function checkOut(array $data, Packing $packing)
    {
        $data[PaymentMethods::MONEY] = (int) ($data[PaymentMethods::MONEY] * 100);
        $data[PaymentMethods::CHECK] = (int) ($data[PaymentMethods::CHECK] * 100);

        $references = $packing->products()->pluck('reference')->unique()->all();
        $checked_references = data_get($data['checked'], '*.reference');

        foreach ($references as $reference) {
            abort_if(!in_array($reference, $checked_references), 400, "Falta a baixa do produto $reference.");
        }

        foreach ($checked_references as $checked_reference) {
            abort_if(!in_array($checked_reference, $references), 400, "O produto $checked_reference não deveria ter retornado.");
        }

        foreach ($data['checked'] as $checked) {
            $expected = $packing->products()
                ->where('reference', $checked['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])
                ->count();
            if ($expected !== intval($checked['amount'])) {
                abort(400, "A quantidade informada do produto {$checked['reference']} é diferente da esperada.");
            }
        }

        $result = $this->toReceive($packing);

        abort_if($result[PaymentMethods::MONEY] !== $data[PaymentMethods::MONEY], 400, 'O valor recebido em dinheiro é diferente do esperado.');

        abort_if($result[PaymentMethods::CHECK] !== $data[PaymentMethods::CHECK], 400, 'O valor recebido em cheque é diferente do esperado.');

        $packing->checked_out_at = now();
        $packing->save();

        CheckOutProducts::dispatch($packing);

        return $packing;
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return array
     */
    public function toReceiveFloat(Packing $packing): array
    {
        $result = $this->toReceive($packing);

        $result[PaymentMethods::MONEY] = (float) ($result[PaymentMethods::MONEY] / 100.0);
        $result[PaymentMethods::CHECK] = (float) ($result[PaymentMethods::CHECK] / 100.0);

        return $result;
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return array
     */
    public function excel(Packing $packing): array
    {
        $products = [];
        foreach ($packing->products()->pluck('reference')->unique()->all() as $reference) {
            /** @var \Modules\Sales\Models\Product $product */
            $product = $packing->products()->where('reference', $reference)->first();
            $amount = $packing->products()->where('reference', $reference)->count();
            $products[] = [
                $reference,
                $product->size,
                $product->color,
                'R$'.number_format((float) ($product->price / 100.0), 2, ',', '.'),
                $amount,
                'R$'.number_format((float) ($product->price * $amount / 100.0), 2, ',', '.'),
            ];
        }

        return $products;
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    private function createProducts(array $data): array
    {
        $products = [];
        foreach ($data as $item) {
            $product = Product::where('reference', $item['reference'])
                ->where('status', ProductStatus::AVAILABLE_STATUS)
                ->latest()->take($item['amount'])->get();

            $product->each(function (Product $product, int $key) use (&$products) {
                $products[] = new \Modules\Sales\Models\Product([
                    'product_id' => $product->id,
                    'reference'  => $product->reference,
                    'thumbnail'  => $product->thumbnail,
                    'size'       => $product->size,
                    'color'      => $product->color,
                    'price'      => $product->price->price,
                ]);
            });

            Product::whereIn('_id', $product->modelKeys())->update(['status' => ProductStatus::IN_TRANSIT_STATUS]);
        }

        return $products;
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     */
    private function releaseProducts(Packing $packing)
    {
        Product::whereIn('_id', $packing->products->pluck('product_id')->all())
            ->update(['status' => ProductStatus::AVAILABLE_STATUS]);

        $packing->products()->dissociate($packing->products->modelKeys());
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return array
     */
    private function toReceive(Packing $packing): array
    {
        $visits = $packing->visits->modelKeys();

        $sales = Sale::whereIn('visit_id', $visits)->whereIn('payment_methods.method', [PaymentMethods::MONEY, PaymentMethods::CHECK])->get();

        $result = [
            PaymentMethods::MONEY => 0,
            PaymentMethods::CHECK => 0,
        ];

        $sales->each(function (Sale $sale, int $key) use (&$result) {
            $result[PaymentMethods::MONEY] += $sale->payment_methods()->where('method', PaymentMethods::MONEY)->sum('value');
            $result[PaymentMethods::CHECK] += $sale->payment_methods()->where('method', PaymentMethods::CHECK)->sum('value');
        });

        return $result;
    }
}
