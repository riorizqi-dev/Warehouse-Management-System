<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\DataBarang::class => \App\Policies\DataBarangPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
