<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('Gestore', function ($user) {
            return $user->mansione === "Gestore";
        });
        
        Gate::define('Cuoco', function ($user) {
            return $user->mansione === "Cuoco";
        });

        Gate::define('Fattorino', function ($user) {
            return $user->mansione === "Fattorino";
        });

    }
}
