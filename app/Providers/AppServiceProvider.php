<?php

namespace App\Providers;

use App\Models\LegalCase;
use App\Policies\CasePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Pemetaan eksplisit karena model LegalCase tidak mengikuti
         * konvensi penamaan Policy otomatis Laravel ({Model}Policy).
         * Model sengaja bernama LegalCase (bukan Case) karena `Case`
         * adalah reserved keyword PHP — lihat Phase 1 poin 10.
         * Policy tetap dinamai CasePolicy agar konsisten dengan
         * CaseController/CaseService/StoreCaseRequest di seluruh project.
         */
        Gate::policy(LegalCase::class, CasePolicy::class);
    }
}