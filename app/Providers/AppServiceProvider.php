<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Invoice\Invoice;
use App\Models\Expenses\Expense;
use App\Models\Invoice\InvoicePayment;
use App\Models\Claim\SupplierClaim;

use App\Observers\InvoiceObserver;
use App\Observers\ExpenseObserver;
use App\Observers\InvoicePaymentObserver;

use App\Policies\InvoicePolicy;
use App\Policies\InvoicePaymentPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\SupplierClaimPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Poultry\VisionService::class, function () {
            return new \App\Services\Poultry\VisionService();
        });

        $this->app->singleton(\App\Services\WhatsApp\WhatsAppService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invoice::observe(InvoiceObserver::class);
        Expense::observe(ExpenseObserver::class);
        InvoicePayment::observe(InvoicePaymentObserver::class);

        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(InvoicePayment::class, InvoicePaymentPolicy::class);
        Gate::policy(Expense::class, ExpensePolicy::class);
        Gate::policy(SupplierClaim::class, SupplierClaimPolicy::class);
    }
}