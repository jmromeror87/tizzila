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


/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Base de Rutas Web
 * Archivo             : web.php
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Global\CompanyController;
use App\Http\Controllers\Global\CompanyAddressController;
use App\Http\Controllers\Global\CompanySettingController;
use App\Http\Controllers\Global\CompanyTaxProfileController;
use App\Http\Controllers\Global\SetupWizardController;
use App\Http\Controllers\Global\GlobalConfigurationController;
use App\Http\Middleware\EnsureGlobalSetupCompleted;
use App\Http\Controllers\Poultry\ProviderController;
use App\Http\Controllers\Poultry\PoultryOrderScheduleController;
use App\Livewire\Poultry\ProviderDocumentIndex;
use App\Http\Controllers\Poultry\PoultryDispatchController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Driver\PoultryDriverController;
use App\Http\Controllers\Dispatch\DispatchRouteController;
use App\Http\Controllers\Dispatch\DispatchConfirmationController;
use App\Http\Controllers\Customer\CustomerDeliveryController;
use App\Http\Controllers\Poultry\PoultryTypeController;
use App\Http\Controllers\Driver\DriverRouteController;
use App\Http\Controllers\Poultry\PoultryProviderDocumentBatchController;
use App\Http\Controllers\Poultry\PurchaseInvoiceController;
use App\Http\Controllers\Poultry\ProjectionController;
use App\Http\Controllers\Poultry\PlannedDistributionController;
use App\Http\Controllers\Global\TaxCategoryController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Invoice\InvoicePaymentController;

use App\Http\Controllers\Claim\SupplierClaimController;

use App\Http\Controllers\Expenses\ExpenseController;

use App\Http\Controllers\Cartera\CarteraController;

use App\Http\Controllers\Accounting\AccountingSettingController;
use App\Http\Controllers\Accounting\ChartOfAccountController;
use App\Http\Controllers\WhatsApp\WhatsAppConnectionController;


Route::middleware(['auth'])->group(function () {

Route::get('/dashboard/payments', [InvoicePaymentController::class, 'dashboardPayments'])
    ->name('dashboard.payments');

Route::prefix('accounting/settings')->group(function () {

    Route::get('/', [AccountingSettingController::class, 'index'])
        ->name('accounting.settings.index');

    Route::post('/', [AccountingSettingController::class, 'store'])
        ->name('accounting.settings.store');

    Route::delete('/{setting}', [AccountingSettingController::class, 'destroy'])
        ->name('accounting.settings.destroy');
});

    // Plan Único de Cuentas (PUC)
    Route::prefix('accounting/chart')->name('accounting.chart.')->group(function () {
        Route::get('/', [ChartOfAccountController::class, 'index'])->name('index');
        Route::post('/', [ChartOfAccountController::class, 'store'])->name('store');
        Route::put('/{account}', [ChartOfAccountController::class, 'update'])->name('update');
        Route::delete('/{account}', [ChartOfAccountController::class, 'destroy'])->name('destroy');
    });

}); // end middleware auth (accounting settings + dashboard payments)



use App\Http\Controllers\Accounting\JournalEntryController;

Route::middleware(['auth'])->group(function () {

Route::get('accounting/ledger', [JournalEntryController::class, 'ledger'])->name('ledger.index');

Route::prefix('accounting')->group(function () {

    Route::get('journal', [JournalEntryController::class, 'index'])->name('journal.index');

    Route::get('journal/create', [JournalEntryController::class, 'create'])->name('journal.create');

    Route::post('journal', [JournalEntryController::class, 'store'])->name('journal.store');

    Route::get('journal/{id}', [JournalEntryController::class, 'show'])->name('journal.show');

    Route::delete('journal/{id}', [JournalEntryController::class, 'destroy'])->name('journal.destroy');

    Route::get('accounting/trial-balance', [JournalEntryController::class, 'trialBalance'])
    ->name('trial.balance');

    Route::get('accounting/income-statement', [JournalEntryController::class, 'incomeStatement'])
    ->name('income.statement');

    Route::get('accounting/balance-sheet', [JournalEntryController::class, 'balanceSheet'])
    ->name('balance.sheet');


    Route::get('accounting/trial-balance/pdf', [JournalEntryController::class, 'trialBalancePdf'])
    ->name('trial.balance.pdf');

Route::get('accounting/income-statement/pdf', [JournalEntryController::class, 'incomeStatementPdf'])
    ->name('income.statement.pdf');

Route::get('accounting/balance-sheet/pdf', [JournalEntryController::class, 'balanceSheetPdf'])
    ->name('balance.sheet.pdf');

    Route::get('accounting/dashboard', [JournalEntryController::class, 'dashboard'])
    ->name('accounting.dashboard');
    Route::post('accounting/journal/{id}/reverse', [JournalEntryController::class, 'reverse'])
    ->name('journal.reverse');

    Route::post('accounting/periods/close', [\App\Http\Controllers\Accounting\AccountingPeriodController::class, 'close'])
    ->name('accounting.periods.close');
    
Route::post('accounting/periods/reopen', 
    [\App\Http\Controllers\Accounting\AccountingPeriodController::class, 'reopen']
)->name('accounting.periods.reopen');


Route::get('accounting/journal', [JournalEntryController::class, 'index'])
    ->name('accounting.journal.index');


});

}); // end middleware auth (accounting ledger + journal)


// 1. Ruta para el botón de la interfaz (la que dispara el envío)
Route::middleware(['auth'])->post('/payments/{payment}/whatsapp', [CarteraController::class, 'sendReceiptWhatsApp'])
    ->name('payments.whatsapp.send');

// 2. Ruta PÚBLICA para el PDF (Twilio la requiere para adjuntar el archivo)
// Esta ruta debe generar el stream del PDF directamente
Route::get('/public/receipt/{payment}/download', [CarteraController::class, 'downloadPublicPdf'])
    ->name('payments.pdf.public');


Route::middleware(['auth'])->get('/payments/{id}/pdf', [InvoicePaymentController::class, 'receiptPdf'])
    ->name('payments.pdf');


use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\Expenses\RecurringExpenseController;
Route::middleware(['auth'])->get('/expenses/calendar', [RecurringExpenseController::class, 'calendar'])
    ->name('expenses.calendar');

/*
|--------------------------------------------------------------------------
| WEB ROUTES - TIZZILA ERP
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | GASTOS RECURRENTES (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::resource('recurring-expenses', RecurringExpenseController::class);
   

});


/*
|--------------------------------------------------------------------------
| CRON (OPCIONAL - PARA HOSTING COMPARTIDO)
|--------------------------------------------------------------------------
*/
Route::get('/cron/run', function () {

    if (request('key') !== config('app.cron_key')) {
        abort(403);
    }

    Artisan::call('schedule:run');

    return 'Cron ejecutado correctamente';
});

Route::middleware(['auth', 'role:admin,finanzas,gerencia'])->prefix('cartera')
    ->name('cartera.')
    ->group(function () {

        Route::get('/', [CarteraController::class, 'index'])
            ->name('index');

        Route::get('/dashboard', [CarteraController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/customer/{id}', [CarteraController::class, 'showCustomer'])
            ->name('customer');

        Route::get('/aging', [CarteraController::class, 'aging'])
            ->name('aging');

        // 🔥 CORRECTO (SIN repetir cartera)
        Route::post('/pagar/{invoice}', [CarteraController::class, 'pay'])
            ->name('pay');

        Route::get('/recibo/{payment}', [CarteraController::class, 'receipt'])
            ->name('receipt');

        Route::post('/customer/{customer}/whatsapp-reminder', [CarteraController::class, 'sendCollectionReminder'])
            ->name('whatsapp-reminder');
    });

Route::middleware(['auth', 'role:admin,operaciones,comercial'])->prefix('claims')->group(function () {

    Route::get('/', [SupplierClaimController::class, 'index'])->name('claims.index');

    Route::get('/{id}', [SupplierClaimController::class, 'show'])->name('claims.show');

    Route::post('/{id}/submit', [SupplierClaimController::class, 'submit'])->name('claims.submit');

    Route::post('/{id}/approve', [SupplierClaimController::class, 'approve'])->name('claims.approve');

    Route::post('/{id}/reject', [SupplierClaimController::class, 'reject'])->name('claims.reject');

    Route::post('/{id}/credited', [SupplierClaimController::class, 'credited'])->name('claims.credited');
    Route::post('/generate/{confirmation}', [SupplierClaimController::class, 'generate'])->name('claims.generate');
    Route::get('/{id}/pdf', [SupplierClaimController::class, 'downloadPdf'])->name('claims.pdf');

});
  

Route::middleware(['auth'])->group(function () {
    // Rutas para Categorías de Impuestos DIAN
    Route::resource('tax-categories', TaxCategoryController::class)
        ->parameters(['tax-categories' => 'tax'])
        ->names('taxes');
});

Route::middleware(['auth'])->get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
    ->name('invoices.pdf');

Route::middleware(['auth', 'role:admin,finanzas,gerencia,comercial'])->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
  
});
Route::post('/dispatch/routes/{route}/notify-driver',
    [DispatchRouteController::class, 'sendDriverWhatsApp']
)->name('dispatch.routes.notifyDriver');




Route::get('/driver/stop/send-wa/{stop}',
    [DriverRouteController::class, 'sendWhatsAppLink']
)->name('driver.stop.send-wa');


Route::get('/driver/session-closed', function () {
    return view('driver.session-closed');
})->name('driver.session.closed');



Route::prefix('cliente')->group(function () {

    Route::get('/entrega/{token}', 
        [CustomerDeliveryController::class, 'show']
    )->name('cliente.entrega.show');

    Route::post('/entrega/{token}', 
        [CustomerDeliveryController::class, 'confirm']
    )->name('customer.delivery.confirm');

    Route::get('/entrega/{token}/gracias', [CustomerDeliveryController::class, 'thanks'])
    ->name('customer.delivery.thanks');

});
Route::middleware(['auth'])
    ->prefix('poultry')
    ->name('poultry.')
    ->group(function () {

        Route::resource('types', PoultryTypeController::class)
            ->names('types');

    });


Route::middleware(['auth'])
    ->prefix('poultry')
    ->name('poultry.')
    ->group(function () {

        Route::get(
            '/provider-documents',
            fn() => view('poultry.providers.documents')
        )->name('provider-documents.index');

        Route::get(
            'poultry/documents/{document}/preview',
            [PoultryOrderScheduleController::class, 'previewDocument']
        )->name('poultry.documents.preview');
    });


/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| GLOBAL – CRUD TÉCNICO (NO WIZARD)
|--------------------------------------------------------------------------
*/

Route::prefix('global')
    ->name('global.')
    ->middleware(['auth'])
    ->group(function () {

        // Empresa
        Route::get('company', [CompanyController::class, 'show'])->name('company.show');
        Route::get('company/edit', [CompanyController::class, 'edit'])->name('company.edit');
        Route::put('company', [CompanyController::class, 'update'])->name('company.update');


        // Direcciones
        Route::get('company-addresses', [CompanyAddressController::class, 'index'])->name('company_addresses.index');
        Route::get('company-addresses/create', [CompanyAddressController::class, 'create'])->name('company_addresses.create');
        Route::post('company-addresses', [CompanyAddressController::class, 'store'])->name('company_addresses.store');
        Route::get('company-addresses/{companyAddress}/edit', [CompanyAddressController::class, 'edit'])->name('company_addresses.edit');
        Route::put('company-addresses/{companyAddress}', [CompanyAddressController::class, 'update'])->name('company_addresses.update');

        // Configuración Operativa
        Route::get('company-settings', [CompanySettingController::class, 'show'])->name('company_settings.show');
        Route::get('company-settings/edit', [CompanySettingController::class, 'edit'])->name('company_settings.edit');
        Route::put('company-settings', [CompanySettingController::class, 'update'])->name('company_settings.update');

        // Perfil Tributario
        Route::get('company-tax-profiles', [CompanyTaxProfileController::class, 'index'])->name('company_tax_profiles.index');
        Route::get('company-tax-profiles/create', [CompanyTaxProfileController::class, 'create'])->name('company_tax_profiles.create');
        Route::post('company-tax-profiles', [CompanyTaxProfileController::class, 'store'])->name('company_tax_profiles.store');
        Route::get('company-tax-profiles/{companyTaxProfile}/edit', [CompanyTaxProfileController::class, 'edit'])->name('company_tax_profiles.edit');
        Route::put('company-tax-profiles/{companyTaxProfile}', [CompanyTaxProfileController::class, 'update'])->name('company_tax_profiles.update');
    });

    //gastos. 

Route::middleware(['auth', 'role:admin,finanzas,gerencia'])->prefix('expenses')
    ->name('expenses.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [ExpenseController::class, 'dashboard'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | LISTADO
        |--------------------------------------------------------------------------
        */
        Route::get('/', [ExpenseController::class, 'index'])
            ->name('index');

        /*
        |--------------------------------------------------------------------------
        | CREAR
        |--------------------------------------------------------------------------
        */
        Route::get('/create', [ExpenseController::class, 'create'])
            ->name('create');

        Route::post('/', [ExpenseController::class, 'store'])
            ->name('store');

        /*
        |--------------------------------------------------------------------------
        | DETALLE
        |--------------------------------------------------------------------------
        */
        Route::get('/{expense}', [ExpenseController::class, 'show'])
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | EDITAR
        |--------------------------------------------------------------------------
        */
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])
            ->name('edit');

        Route::put('/{expense}', [ExpenseController::class, 'update'])
            ->name('update');

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR
        |--------------------------------------------------------------------------
        */
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])
            ->name('destroy');

    });





   /*
|--------------------------------------------------------------------------
| DRIVER ROUTES 
|--------------------------------------------------------------------------
*/


Route::prefix('driver')->group(function () {

    Route::get('/route/{token}', [DriverRouteController::class, 'show']);
    Route::post('/route/{token}/start', [DriverRouteController::class, 'start']);
    Route::post('/route/{token}/finish', [DriverRouteController::class, 'finish']);

    Route::post('/stop/{stop}/arrive', [DriverRouteController::class, 'arrive']);
    Route::post('/stop/{stop}/complete', [DriverRouteController::class, 'complete']);

    Route::get('/route/{token}/summary', [DriverRouteController::class, 'summary']);
});

Route::post('/driver/tracking', function (\Illuminate\Http\Request $request) {

    \App\Models\Dispatch\PoultryDispatchRouteLocation::create([
        'dispatch_route_id' => $request->route_id,
        'latitude' => $request->lat,
        'longitude' => $request->lng,
        'recorded_at' => now(),
    ]);

    return response()->json(['ok' => true]);
});



    

/*
|--------------------------------------------------------------------------
| SETUP WIZARD (ONBOARDING)
|--------------------------------------------------------------------------
*/

Route::prefix('setup')
    ->name('setup.')
    ->middleware(['auth'])
    ->group(function () {

        // STEP 1 – COMPANY
        Route::get('company', [SetupWizardController::class, 'step1'])
            ->name('company');
        Route::post('company', [SetupWizardController::class, 'storeStep1'])
            ->name('company.store');

        // STEP 2 – ADDRESS
        Route::get('address', [SetupWizardController::class, 'step2'])
            ->name('address');
        Route::post('address', [SetupWizardController::class, 'storeStep2'])
            ->name('address.store');

        // STEP 3 – SETTINGS
        Route::get('settings', [SetupWizardController::class, 'step3'])
            ->name('settings');
        Route::post('settings', [SetupWizardController::class, 'storeStep3'])
            ->name('settings.store');

        // STEP 4 – TAX
        Route::get('tax', [SetupWizardController::class, 'step4'])
            ->name('tax');
        Route::post('tax', [SetupWizardController::class, 'storeStep4'])
            ->name('tax.store');

        Route::get('complete', [SetupWizardController::class, 'complete'])
            ->name('complete');
    });

/*|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS – DESPACHOS (RUTAS DE DESPACHO)
|--------------------------------------------------------------------------*/



Route::middleware(['auth', 'role:admin,operaciones'])
    ->prefix('dispatch/routes')
    ->name('dispatch.routes.')
    ->group(function () {

        Route::get('/', [DispatchRouteController::class, 'index'])
            ->name('index');

        Route::get('{route}', [DispatchRouteController::class, 'show'])
            ->name('show');

        Route::post('{route}/start', [DispatchRouteController::class, 'start'])
            ->name('start');

        Route::post('{route}/finish', [DispatchRouteController::class, 'finish'])
            ->name('finish');
    });


Route::middleware(['auth', 'role:admin,operaciones'])->prefix('dispatch')->group(function () {

    Route::get(
        '/stops/{stop}/confirm',
        [DispatchConfirmationController::class, 'create']
    )->name('dispatch.stops.confirm');

    Route::post(
        '/stops/{stop}/confirm',
        [DispatchConfirmationController::class, 'store']
    )->name('dispatch.stops.confirm.store');
});



Route::middleware(['auth'])->get('/dispatch/confirmations/{confirmation}',
    [DispatchConfirmationController::class, 'show']
)->name('dispatch.confirmations.show');



/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN GLOBAL (POST-WIZARD – PANEL FUNCIONAL)
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', EnsureGlobalSetupCompleted::class])
    ->prefix('configuration')
    ->name('configuration.')
    ->group(function () {
        Route::get('/', [GlobalConfigurationController::class, 'show'])
            ->name('show');
    });

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS – Poultry y sus recursos
|--------------------------------------------------------------------------
*/
Route::prefix('poultry')
    ->name('poultry.')
    ->middleware(['auth'])
    ->group(function () {

        // 📅 CALENDARIO
        Route::get(
            'orders/calendar',
            [PoultryOrderScheduleController::class, 'calendar']
        )->name('orders.calendar');

        Route::get(
            'orders/calendar/events',
            [PoultryOrderScheduleController::class, 'calendarEvents']
        )->name('orders.calendar.events');

        // 📦 PEDIDOS
        Route::resource('orders', PoultryOrderScheduleController::class)
            ->except(['show']);

        // 🏢 PROVEEDORES  ✅ ESTO FALTABA
        Route::resource('providers', ProviderController::class);
    });

Route::middleware(['auth'])->group(function () {
    Route::resource('providers', ProviderController::class)
        ->only(['index', 'show']);
    Route::post(
        '/dispatch/routes/generate',
        [DispatchRouteController::class, 'generate']
    )->name('dispatch.routes.generate');
});



/*|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS – CLIENTES
|--------------------------------------------------------------------------*/

Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('customers/check-exists', [CustomerController::class, 'checkExists'])
        ->name('customers.check-exists');
});
/*|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS – DESPACHOS DE AVES
|--------------------------------------------------------------------------*/

Route::middleware(['auth'])->prefix('poultry/dispatches')
    ->name('poultry.dispatches.')
    ->group(function () {

        Route::get('/', [PoultryDispatchController::class, 'index'])
            ->name('index');



        Route::get('/create/{order}', [PoultryDispatchController::class, 'create'])
            ->name('create');

        Route::post('/', [PoultryDispatchController::class, 'store'])
            ->name('store');

        Route::get('/{dispatch}', [PoultryDispatchController::class, 'show'])
            ->name('show');

        Route::post(
            'stops/{stop}/deliver',
            [DispatchRouteController::class, 'deliverStop']
        )->name('stops.deliver');

        Route::post(
            'stops/{stop}/fail',
            [DispatchRouteController::class, 'failStop']
        )->name('stops.fail');
    });

Route::middleware(['auth'])->post(
    '/poultry/batch/{id}/update',
    [PoultryProviderDocumentBatchController::class, 'update']
)->name('poultry.batch.update');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS – PEDIDOS DE AVES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('poultry/orders')
    ->name('poultry.orders.')
    ->group(function () {

        Route::get('{order}/confront', [
            PoultryOrderScheduleController::class,
            'confront'
        ])->name('confront');

        Route::post('{order}/confirm', [
            PoultryOrderScheduleController::class,
            'confirm'
        ])->name('confirm');

        Route::post('{order}/incident', [
            PoultryOrderScheduleController::class,
            'incident'
        ])->name('incident');

        Route::post('{order}/cancel', [
            PoultryOrderScheduleController::class,
            'cancel'
        ])->name('cancel');

        Route::get('{order}/dss-surplus', [
            PoultryOrderScheduleController::class,
            'dssSurplus'
        ])->name('dss-surplus');
    });

    // 📋 DISTRIBUCIÓN PROGRAMADA (Parte 1)
    Route::prefix('orders/{order}/distribution')->name('poultry.distribution.')->middleware('auth')->group(function () {
        Route::get('/', [PlannedDistributionController::class, 'show'])->name('show');
        Route::post('/auto-fixed', [PlannedDistributionController::class, 'autoLoadFixed'])->name('auto-fixed');
        Route::post('/', [PlannedDistributionController::class, 'store'])->name('store');
        Route::put('/{distribution}', [PlannedDistributionController::class, 'update'])->name('update');
        Route::delete('/{distribution}', [PlannedDistributionController::class, 'destroy'])->name('destroy');
    });

    // 📊 PROYECCIÓN COMERCIAL
    Route::get('projection', [ProjectionController::class, 'index'])
        ->middleware('auth')
        ->name('poultry.projection');

    // 🧾 FACTURAS DE COMPRA (PRONAVICOLA)
    Route::prefix('purchase-invoices')->name('purchase-invoices.')->middleware(['auth'])->group(function () {
        Route::get('/', [PurchaseInvoiceController::class, 'index'])->name('index');
        Route::get('orders/{order}/create', [PurchaseInvoiceController::class, 'create'])->name('create');
        Route::post('orders/{order}', [PurchaseInvoiceController::class, 'store'])->name('store');
        Route::get('{purchaseInvoice}', [PurchaseInvoiceController::class, 'show'])->name('show');
        Route::post('{purchaseInvoice}/pay', [PurchaseInvoiceController::class, 'pay'])->name('pay');
        Route::post('{purchaseInvoice}/upload', [PurchaseInvoiceController::class, 'uploadFile'])->name('upload');
    });
/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS GENERALES
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketEventController;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Novedades de Mercado
    Route::prefix('market-events')->name('market-events.')->group(function () {
        Route::get('/',                    [MarketEventController::class, 'index'])->name('index');
        Route::post('/',                   [MarketEventController::class, 'store'])->name('store');
        Route::patch('/{event}/toggle',    [MarketEventController::class, 'toggle'])->name('toggle');
        Route::delete('/{event}',          [MarketEventController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
/*|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS – CONDUCTORES DE AVES
|--------------------------------------------------------------------------*/

Route::middleware(['auth'])
    ->prefix('poultry/drivers')
    ->name('poultry.drivers.')
    ->group(function () {

        Route::get('/', [PoultryDriverController::class, 'index'])->name('index');
        Route::get('/create', [PoultryDriverController::class, 'create'])->name('create');
        Route::post('/', [PoultryDriverController::class, 'store'])->name('store');
        Route::get('{driver}', [PoultryDriverController::class, 'show'])->name('show');
        Route::get('{driver}/edit', [PoultryDriverController::class, 'edit'])->name('edit');
        Route::put('{driver}', [PoultryDriverController::class, 'update'])->name('update');
        Route::delete('{driver}', [PoultryDriverController::class, 'destroy'])->name('destroy');
    });


Route::middleware(['auth'])->get('/payments/{id}/send-whatsapp', [CarteraController::class, 'sendWhatsappReceipt'])
    ->name('payments.whatsapp');


Route::prefix('invoices')->middleware(['auth'])->group(function () {

    Route::get(
        '/{invoice}/payments',
        [InvoicePaymentController::class, 'index']
    )->name('invoices.payments.index');

    Route::get(
        '/{invoice}/payments/create',
        [InvoicePaymentController::class, 'create']
    )->name('invoices.payments.create');

    Route::get(
        '/{invoice}/payments/{payment}',
        [InvoicePaymentController::class, 'show']
    )->name('invoices.payments.show');

    Route::get('/invoices/{invoice}/payments', [InvoicePaymentController::class,'dashboard'])
    ->name('payments.dashboard');
    

    Route::post(
        '/{invoice}/payments',
        [InvoicePaymentController::class, 'store']
    )->name('invoices.payments.store');

    Route::post('/invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])
    ->name('payments.store');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/payments/{payment}', [InvoicePaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments', [InvoicePaymentController::class, 'all'])->name('payments.all');
    Route::get('/payments/{invoice}', [InvoicePaymentController::class, 'index'])->name('payments.index');
    Route::delete('/payments/{payment}', [InvoicePaymentController::class, 'destroy'])->name('payments.destroy');
});


/*
|--------------------------------------------------------------------------
| ADMIN — Gestión de Usuarios (solo admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users',           [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users',          [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',    [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Permisos de roles
        Route::get('/permissions',                    [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions',                   [\App\Http\Controllers\Admin\RolePermissionController::class, 'update'])->name('permissions.update');
        Route::post('/permissions/modules',           [\App\Http\Controllers\Admin\RolePermissionController::class, 'storeModule'])->name('permissions.modules.store');
        Route::delete('/permissions/modules/{module}',[\App\Http\Controllers\Admin\RolePermissionController::class, 'destroyModule'])->name('permissions.modules.destroy');
    });

/*
|--------------------------------------------------------------------------
| WHATSAPP — Conexión y estado
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('whatsapp')->name('whatsapp.')->group(function () {
    Route::get('/',            [WhatsAppConnectionController::class, 'index'])->name('index');
    Route::get('/status',      [WhatsAppConnectionController::class, 'status'])->name('status');
    Route::post('/disconnect', [WhatsAppConnectionController::class, 'disconnect'])->name('disconnect');
});

require __DIR__ . '/auth.php';
