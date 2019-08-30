<?php

namespace Modules\Customer\Repositories;

use App\Models\Address;
use App\Models\Phone;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\Owner;

class CustomerRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Modules\Customer\Models\Customer[]
     */
    public function all()
    {
        if (!empty(\Request::query()) && NULL !== \Request::query()['search']) return $this->search();

        return Customer::take(30)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search()
    {
        $query = \Request::query()['search'];

        return Customer::search($query)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Customer\Models\Customer
     */
    public function create(array $data): Customer
    {
        $customer = new Customer($data);

        $customer->seller()->associate($data['seller']);
        $customer->address()->associate($this->createAddress($data));
        $this->createPhones($data, $customer);
        $this->createOwners($data, $customer);

        $customer->save();

        return $customer;
    }

    /**
     * @param  array                              $data
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return \Modules\Customer\Models\Customer
     */
    public function update(array $data, Customer $customer): Customer
    {
        $customer->seller()->associate($data['seller']);
        $customer->address()->associate($this->createAddress($data));

        $customer->owners()->delete();
        $customer->phones()->delete();

        $this->createPhones($data, $customer);
        $this->createOwners($data, $customer);

        $customer->update($data);

        return $customer;
    }

    /**
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Customer $customer)
    {
        return $customer->delete();
    }

    /**
     * @param  array                              $data
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return $this
     */
    private function createPhones(array $data, Customer &$customer)
    {
        foreach ($data['phones'] as $phone) {
            $customer->phones()->associate($this->createPhone($phone));
        }

        return $this;
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

    /**
     * @param  array                              $data
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return $this
     */
    private function createOwners(array $data, Customer &$customer)
    {
        foreach ($data['owners'] as $owner) {
            if (isset($owner)) {
                $owner['birth_date'] = Carbon::createFromFormat('d/m/Y', $owner['birth_date']);
            } else {
                unset($owner['birth_date']);
            }

            if (!isset($owner['document'])) {
                unset($owner['document']);
            }

            $aux = new Owner($owner);
            $aux->phone()->associate($this->createPhone($owner['phone']));

            $customer->owners()->associate($aux);
        }

        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Address
     */
    private function createAddress(array $data): Address
    {
        return new Address($data['address']);
    }
}
