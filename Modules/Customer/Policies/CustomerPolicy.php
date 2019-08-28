<?php

namespace Modules\Customer\Policies;

use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\User\Models\User;

class CustomerPolicy
{
    /**
     * @param  \Modules\User\Models\User  $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return in_array($user->type, [EmployeeTypes::TYPE_ADMIN, EmployeeTypes::TYPE_SELLER]);
    }

    /**
     * @param  \Modules\User\Models\User          $user
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return bool
     */
    public function view(User $user, Customer $customer)
    {
        return in_array($user->type, [EmployeeTypes::TYPE_ADMIN, EmployeeTypes::TYPE_SELLER]);
    }

    /**
     * @param  \Modules\User\Models\User          $user
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return bool
     */
    public function update(User $user, Customer $customer)
    {
        return in_array($user->type, [EmployeeTypes::TYPE_ADMIN, EmployeeTypes::TYPE_SELLER]);
    }

    /**
     * @param  \Modules\User\Models\User          $user
     * @param  \Modules\Customer\Models\Customer  $customer
     *
     * @return bool
     */
    public function delete(User $user, Customer $customer)
    {
        return in_array($user->type, [EmployeeTypes::TYPE_ADMIN, EmployeeTypes::TYPE_SELLER]);
    }
}
