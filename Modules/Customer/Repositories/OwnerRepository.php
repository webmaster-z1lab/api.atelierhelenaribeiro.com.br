<?php

namespace Modules\Customer\Repositories;

use App\Models\Phone;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\Owner;

class OwnerRepository
{
    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function all(Customer $customer)
    {
        return $customer->owners()->latest()->take(30)->get();
    }

    /**
     * @param  array                              $data
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return \Modules\Customer\Models\Owner
     */
    public function create(array $data, Customer $customer): Owner
    {
        $owner = new Owner($data);

        $owner->customer()->associate($customer);
        foreach ($data['phones'] as $phone) {
            $owner->phones()->associate($this->createPhone($phone));
        }

        $owner->save();

        return $owner;
    }

    /**
     * @param  array                              $data
     * @param  \Modules\Customer\Models\Owner  $owner
     *
     * @return \Modules\Customer\Models\Owner
     */
    public function update(array $data, Owner $owner): Owner
    {
        if (array_key_exists('phones', $data) && filled($data['phones'])) {
            foreach ($data['phones'] as $phone) {
                $owner->phones()->associate($this->createPhone($phone));
            }
        }

        $owner->update($data);

        return $owner;
    }

    /**
     * @param  \Modules\Customer\Models\Owner  $owner
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Owner $owner)
    {
        return $owner->delete();
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Phone
     */
    private function createPhone(array $data): Phone
    {
        return new Phone($data);
    }
}
