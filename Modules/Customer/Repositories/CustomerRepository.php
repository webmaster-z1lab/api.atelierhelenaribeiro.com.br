<?php

namespace Modules\Customer\Repositories;

use App\Models\Address;
use App\Models\Phone;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;

class CustomerRepository
{
    /**
     * @param  int   $items
     * @param  bool  $paginate
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Modules\Customer\Models\Customer[]
     */
    public function all(int $items = 10, bool $paginate = TRUE)
    {
        if ($paginate) return Customer::paginate($items);

        return Customer::all();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Customer\Models\Customer
     */
    public function create(array $data): Customer
    {
        $customer = new Customer($data);

        $customer->address()->associate($this->createAddress($data['address']));
        foreach ($data['phones'] as $phone) {
            $customer->phones()->save($this->createPhone($phone));
        }
        foreach ($data['contacts'] as $contact) {
            $customer->contacts()->save($this->createContact($contact));
        }

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
        $customer->address()->associate($this->createAddress($data['address']));
        if (array_key_exists('phones', $data) && filled($data['phones'])) {
            foreach ($data['phones'] as $phone) {
                $customer->phones()->save($this->createPhone($phone));
            }
        }
        if (array_key_exists('contacts', $data) && filled($data['contacts'])) {
            foreach ($data['contacts'] as $contact) {
                $customer->contacts()->save($this->createContact($contact));
            }
        }

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
     * @param  array  $data
     *
     * @return \App\Models\Phone
     */
    private function createPhone(array $data): Phone
    {
        return new Phone($data);
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Address
     */
    private function createAddress(array $data): Address
    {
        return new Address($data);
    }

    /**
     * @param  string  $name
     *
     * @return \Modules\Customer\Models\Contact
     */
    private function createContact(string $name): Contact
    {
        return new Contact(compact('name'));
    }
}
