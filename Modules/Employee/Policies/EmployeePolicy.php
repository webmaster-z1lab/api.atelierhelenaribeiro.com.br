<?php

namespace Modules\Employee\Policies;

use Modules\Employee\Models\EmployeeTypes;
use Modules\User\Models\User;

class EmployeePolicy
{
    /**
     * @param  \Modules\User\Models\User  $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }

    /**
     * @param  \Modules\User\Models\User  $user
     * @param  \Modules\User\Models\User  $employee
     *
     * @return bool
     */
    public function view(User $user, User $employee)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN || $user->id === $employee->id;
    }

    /**
     * @param  \Modules\User\Models\User  $user
     * @param  \Modules\User\Models\User  $employee
     *
     * @return bool
     */
    public function update(User $user, User $employee)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }

    /**
     * @param  \Modules\User\Models\User  $user
     * @param  \Modules\User\Models\User  $employee
     *
     * @return bool
     */
    public function delete(User $user, User $employee)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN && $user->id !== $employee->id;
    }
}
