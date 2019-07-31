<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('users')) Schema::create('users');

        Schema::table('users', function (Blueprint $collection) {
            $collection->index(
                [
                    'name'     => 'text',
                    'document' => 'text',
                    'type'     => 'text',
                ],
                'full-text-users'
                ,NULL,
                [
                    'weights' => [
                        'name'     => 32,
                        'document' => 8,
                        'type'     => 4,
                    ],
                    'name' => 'full-text-users',
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
        Schema::table('users', function (Blueprint $collection) {
            $collection->dropIndex('full-text-users');
        });
    }
}
