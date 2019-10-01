<?php

namespace Modules\Order\Repositories;

use Carbon\Carbon;
use Modules\Catalog\Models\Template;
use Modules\Order\Jobs\ShipProducts;
use Modules\Order\Models\Order;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\ProductStatus;
use Modules\Stock\Models\Size;
use Modules\Stock\Repositories\ColorRepository;
use Modules\Stock\Repositories\ProductRepository;

class OrderRepository
{
    /**
     * @param  bool  $paginate
     * @param  int   $itens
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function all(bool $paginate = TRUE, int $itens = 10)
    {
        return Order::latest()->take(30)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Order\Models\Order
     */
    public function create(array $data): Order
    {
        $products = $this->prepareOrder($data);

        $order = new Order($data);

        $order->customer()->associate($data['customer']);
        $order->products()->saveMany($products);

        $order->save();

        return $order;
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Order\Models\Order  $order
     *
     * @return \Modules\Order\Models\Order
     */
    public function update(array $data, Order $order): Order
    {
        if ($order->status === Order::SHIPPED_STATUS) {
            abort(400, 'O pedido já foi enviado.');
        }

        $products = $this->prepareOrder($data);

        $order->products()->delete();
        $order->products()->saveMany($products);
        $order->customer()->associate($data['customer']);

        $order->update($data);

        return $order;
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Order $order)
    {
        if ($order->status === Order::SHIPPED_STATUS) {
            abort(400, 'O pedido já foi enviado.');
        }

        if ($order->status === Order::AWAITING_STATUS) {
            $order->products()->delete();
        }

        return $order->delete();
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Order\Models\Order  $order
     *
     * @return \Modules\Order\Models\Order
     */
    public function ship(array $data, Order $order): Order
    {
        $data = \Arr::where($data, function ($value) {
            return filled($value);
        });

        $data['freight'] = (int) ((float) $data['freight'] * 100);
        $data['shipped_at'] = Carbon::createFromFormat('d/m/Y', $data['shipped_at']);

        $data['status'] = Order::SHIPPED_STATUS;

        $order->update($data);

        ShipProducts::dispatch($order->products->modelKeys());

        return $order;
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    protected function prepareOrder(array &$data): array
    {
        $data = \Arr::where($data, function ($value) {
            return filled($value);
        });

        $data['event_date'] = Carbon::createFromFormat('d/m/Y', $data['event_date']);
        $data['ship_until'] = Carbon::createFromFormat('d/m/Y', $data['ship_until']);
        if (isset($data['shipped_at'])) {
            $data['shipped_at'] = Carbon::createFromFormat('d/m/Y', $data['shipped_at']);
        }

        $products = $this->createProducts($data['products']);

        $data['total_price'] = $products['total'];

        return $products['products'];
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    protected function createProducts(array $data): array
    {
        $productRepository = new ProductRepository();

        $products = [
            'products' => [],
            'total'    => 0,
        ];
        foreach ($data as $product) {
            $product = \Arr::where($product, function ($value) {
                return filled($value);
            });

            $template = Template::find($product['template']);
            $size = $this->getSize($product['mannequin']);
            $color = $this->getColor($product['color']);

            $product['reference'] = implode('-', [$template->reference, $color->reference, $size->reference]);
            $product['size'] = $size->name;
            $product['status'] = ProductStatus::AWAITING_STATUS;

            $products['products'][] = $productRepository->createProduct($product, $template, $template->price);
            $products['total'] += $template->price->price;
        }

        return $products;
    }

    /**
     * @param  string  $mannequin
     *
     * @return \Modules\Stock\Models\Size
     */
    protected function getSize(string $mannequin): Size
    {
        return Size::first();
    }

    /**
     * @param  string  $color
     *
     * @return \Modules\Stock\Models\Color
     */
    protected function getColor(string $color): Color
    {
        if (Color::where('name', $color)->exists()) {
            return Color::where('name', $color)->first();
        }

        $colorRepository = new ColorRepository();

        return $colorRepository->create(['name' => $color]);
    }
}
