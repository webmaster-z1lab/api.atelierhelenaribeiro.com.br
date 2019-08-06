<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('customers')) Schema::create('customers');

        Schema::table('customers', function (Blueprint $collection) {
            $collection->index(
                [
                    'company_name' => 'text',
                    'trading_name' => 'text',
                    'document'     => 'text',
                    'email'        => 'text',
                ],
                'full-text-customers'
                , NULL,
                [
                    'weights' => [
                        'company_name' => 32,
                        'trading_name' => 16,
                        'document'     => 8,
                        'email'        => 4,
                    ],
                    'name'    => 'full-text-customers',
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $collection) {
            $collection->dropIndex('full-text-customers');
        });
    }
}
