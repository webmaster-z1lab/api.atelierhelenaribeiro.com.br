<?php

namespace Modules\Catalog\Policies;

use Modules\Catalog\Models\Template;
use Modules\Employee\Models\EmployeeTypes;
use Modules\User\Models\User;

class TemplatePolicy
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
     * @param  \Modules\User\Models\User         $user
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool
     */
    public function view(User $user, Template $template)
    {
        return TRUE;
    }

    /**
     * @param  \Modules\User\Models\User         $user
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool
     */
    public function update(User $user, Template $template)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }

    /**
     * @param  \Modules\User\Models\User         $user
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool
     */
    public function delete(User $user, Template $template)
    {
        return !$template->products()->exists() && $user->type === EmployeeTypes::TYPE_ADMIN;
    }

    /**
     * @param  \Modules\User\Models\User         $user
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool
     */
    public function destroyImage(User $user, Template $template)
    {
        return $user->type === EmployeeTypes::TYPE_ADMIN;
    }
}
