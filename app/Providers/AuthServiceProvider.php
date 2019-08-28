<?php

namespace App\Providers;

use App\Auth\Guards\JwtGuard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Catalog\Models\Template;
use Modules\Catalog\Policies\TemplatePolicy;
use Modules\Customer\Models\Customer;
use Modules\Customer\Policies\CustomerPolicy;
use Modules\Employee\Policies\EmployeePolicy;
use Modules\Stock\Models\Product;
use Modules\Stock\Policies\ProductPolicy;
use Modules\User\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Template::class => TemplatePolicy::class,
        Customer::class => CustomerPolicy::class,
        User::class     => EmployeePolicy::class,
        Product::class  => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Auth::extend('jwt', function ($app, $name, array $config) {

            return new JwtGuard(\Auth::createUserProvider($config['provider']), $app->request);
        });
    }
}
