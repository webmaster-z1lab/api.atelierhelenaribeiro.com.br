<?php

namespace Modules\Stock\Tests\Feature\Jobs;

use Illuminate\Support\Facades\Bus;
use Modules\Catalog\Models\Template;
use Modules\Stock\Jobs\CreateColor;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;
use Tests\TestCase;

class CreateColorTest extends TestCase
{
    /**
     * @test
     */
    public function test_create_color(): void
    {
        Bus::fake();

        $product = $this->persist();

        Bus::assertDispatched(CreateColor::class, function (CreateColor $job) use ($product) {
            $job->handle();

            return $job->color === $product->color;
        });

        $this->assertDatabaseHas('colors', [
            'name' => $product->color,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Template::truncate();
        Product::truncate();
        Color::truncate();

        parent::tearDown();
    }

    /**
     * @return \Modules\Stock\Models\Product
     */
    private function persist(): Product
    {
        return factory(Product::class)->create();
    }
}
