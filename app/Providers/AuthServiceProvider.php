<?php

namespace App\Providers;

use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Policies\CompanyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Company::class => CompanyPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
