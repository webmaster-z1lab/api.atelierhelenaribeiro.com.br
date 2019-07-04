<?php

use Illuminate\Database\Seeder;
use Modules\User\Models\User;

class UsersCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create();
    }
}
