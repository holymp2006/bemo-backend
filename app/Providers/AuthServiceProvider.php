<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Sanctum::getAccessTokenFromRequestUsing(
            fn (Request $request): ?string => $request->access_token
        );
    }
}
