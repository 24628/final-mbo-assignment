<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
         'App\Event' => 'App\Policies\EventPolicy',
         'App\EventSettings' => 'App\Policies\EventSettingsPolicy',
         'App\Profile' => 'App\Policies\ProfilePolicy',
         'App\Role' => 'App\Policies\RolePolicy',
         'App\Map' => 'App\Policies\MapPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
