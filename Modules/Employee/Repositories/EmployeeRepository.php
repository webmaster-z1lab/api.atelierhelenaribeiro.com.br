<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 30/07/2019
 * Time: 14:24
 */

namespace Modules\Employee\Repositories;

use App\Models\Address;
use App\Models\Phone;
use Illuminate\Support\Arr;
use Modules\User\Models\User;

class EmployeeRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Modules\User\Models\User[]
     */
    public function all()
    {
        if (!empty(\Request::query()) && NULL !== \Request::query()['search']) return $this->search();

        return User::all()->take(30);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search()
    {
        $query = \Request::query()['search'];

        if($query === 'seller') return User::seller()->get();

        return User::search($query)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Database\Eloquent\Model|\Modules\User\Models\User
     */
    public function create(array $data)
    {
        $user = new User($data);

        $user->phone()->associate($this->createPhone($data));
        $user->address()->associate($this->createAddress($data));

        $user->save();

        return $user;
    }

    /**
     * @param  array                      $data
     * @param  \Modules\User\Models\User  $employee
     *
     * @return \Modules\User\Models\User
     */
    public function update(array $data, User $employee): User
    {
        $employee->phone()->associate($this->createPhone($data));
        $employee->address()->associate($this->createAddress($data));

        $employee->update($data);

        return $employee;
    }

    /**
     * @param  \Modules\User\Models\User  $user
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(User $user)
    {
        return $user->delete();
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Phone
     */
    private function createPhone(array $data): Phone
    {
        return new Phone($data['phone']);
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
