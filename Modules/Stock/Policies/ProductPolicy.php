<?php

namespace Modules\Stock\Policies;

use Modules\Employee\Models\EmployeeTypes;
use Modules\Stock\Models\Product;
use Modules\User\Models\User;

class ProductPolicy
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
     * @param  \Modules\User\Models\User      $user
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return bool
     */
    public function view(User $user, Product $product)
    {
        return TRUE;
    }

    /**
     * @param  \Modules\User\Models\User      $user
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }

    /**
     * @param  \Modules\User\Models\User      $user
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return bool
     */
    public function delete(User $user, Product $product)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }

    /**
     * @param  \Modules\User\Models\User      $user
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return bool
     */
    public function destroyImage(User $user, Product $product)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }
}
