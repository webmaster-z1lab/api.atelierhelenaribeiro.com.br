<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use App\Models\Phone;
use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Company;
use Illuminate\Support\Arr;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerStatus;
use Modules\Customer\Models\Owner;
use Modules\User\Models\User;

$faker = new Faker();
$faker->addProvider(new Company($faker));

$status = [
    CustomerStatus::ACTIVE,
    CustomerStatus::INACTIVE,
    CustomerStatus::STANDBY,
];

$factory->define(Customer::class, function (Faker $faker) use ($status) {
    return [
        'company_name'           => $faker->company,
        'trading_name'           => $faker->companySuffix,
        'document'               => $faker->cnpj(FALSE),
        'state_registration'     => (string)$faker->numberBetween(1000000, 9999999),
        'municipal_registration' => (string)$faker->numberBetween(1000000, 9999999),
        'email'                  => $faker->safeEmail,
        'contact'                => $faker->name,
        'annotation'             => $faker->realText(),
        'status'                 => Arr::random($status),
    ];
});

$factory->afterMaking(Customer::class, function (Customer $customer) {
    $customer->address()->associate(factory(Address::class)->make());
    $customer->seller()->associate(factory(User::class)->make(['type' => 'seller']));

    $phones = factory(Phone::class, 2)->make();
    $owners = factory(Owner::class, 2)->make();

    foreach ($phones as $phone) {
        $customer->phones()->associate($phone);
    }

    foreach ($owners as $owner) {
        $customer->owners()->associate($owner);
    }
});
