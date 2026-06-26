<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PdfController;
use App\Livewire\AccountBoxes\AccountBoxComponent;
use App\Livewire\AccountBoxes\BoxFormComponent;
use App\Livewire\Accounts\AccountComponent;
use App\Livewire\AccountStatements\AccountStatementComponent;
use App\Livewire\AccountStatements\AccountStatementView;
use App\Livewire\Cooperatives\CooperativeComponent;
use App\Livewire\Dashboard;
use App\Livewire\Liquidations\LiquidationForm;
use App\Livewire\Permissions\PermissionComponent;
use App\Livewire\Retentions\RetentionComponent;
use App\Livewire\Retentions\RetentionComponentForm;
use App\Livewire\Roles\RoleComponent;
use App\Livewire\Suppliers\SupplierComponent;
use App\Livewire\Taxe\TaxeComponent;
use App\Livewire\Transactions\TransactionComponent;
use App\Livewire\Transactions\TransactionFormComponent;
use App\Livewire\Transactions\ViewComponent;
use App\Livewire\Users\UserComponent;
use App\Services\AccountStatementService;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::middleware('permission:Ver impuestos')->group(function () {
        Route::get('taxes', TaxeComponent::class)->name('taxes');
    });

    Route::middleware('permission:Ver cuentas')->group(function () {
        Route::get('accounts', AccountComponent::class)->name('accounts');
    });

    Route::middleware('permission:Ver estados de cuenta')->group(function () {
        Route::get('accounts-statement', AccountStatementComponent::class)->name('accounts.statement');
        Route::get('accounts-statement/{type}/{id}', AccountStatementView::class)
            ->whereIn('type', AccountStatementService::TYPES)
            ->name('accounts.statement.view');
    });

    Route::middleware('permission:PDF estados de cuenta')->group(function () {
        Route::get('account/statement/pdf/{type}/{id}', [PdfController::class, 'accountStatement'])
            ->whereIn('type', AccountStatementService::TYPES)
            ->name('account.statement.pdf');
    });

    Route::get('accounts-boxes', AccountBoxComponent::class)->name('accounts.box');
    Route::get('accounts-boxes/form/{id?}', BoxFormComponent::class)->name('accounts.box.form');
    Route::get('movement/box/pdf/{id}', [PdfController::class, 'receiptBox'])->name('receipt.box.pdf');
    Route::get('account/box/pdf', [PdfController::class, 'accountBox'])->name('account.box.pdf');

    Route::middleware('permission:Ver retenciones')->group(function () {
        Route::get('retentions', RetentionComponent::class)->name('retentions');
        Route::get('retention/pdf/form/{id}', [PdfController::class, 'retentionForm'])->name('retention.pdf.form');
    });

    Route::middleware('permission:Crear retenciones')->group(function () {
        Route::get('retentions/form/{id?}', RetentionComponentForm::class)->name('retention.form');
    });

    Route::middleware('permission:Exportar retenciones')->group(function () {
        Route::get('retention-month/excel/{date}/{type}', [ExcelController::class, 'retentionMonth'])->name('retention.month.excel');
    });

    Route::get('suppliers', SupplierComponent::class)->name('suppliers');
    Route::get('cooperatives', CooperativeComponent::class)->name('cooperatives');
    Route::get('liquidations/form', LiquidationForm::class)->name('liquidation.form');

    Route::middleware('permission:Ver usuarios')->group(function () {
        Route::get('users', UserComponent::class)->name('users');
    });

    Route::middleware('permission:Ver roles')->group(function () {
        Route::get('roles', RoleComponent::class)->name('roles');
    });

    Route::middleware('permission:Asignación de permisos')->group(function () {
        Route::get('permissions', PermissionComponent::class)->name('permissions');
    });

    Route::middleware('permission:Ver libro de bancos')->group(function () {
        Route::get('transactions', TransactionComponent::class)->name('transactions');
        Route::get('transactions/{id}/{date}', ViewComponent::class)->name('transactions.view');
    });

    Route::middleware('permission:Crear movimientos de libro de bancos')->group(function () {
        Route::get('transactions/form/{date_account}/{account_id}/{id?}', TransactionFormComponent::class)->name('transactions.form');
    });

    Route::middleware('permission:PDF libro de bancos')->group(function () {
        Route::get('transaction/pdf/form/{start}/{end}/{id}', [PdfController::class, 'transactionAccount'])->name('transaction.account.pdf');
        Route::get('transaction/pdf/{id}', [PdfController::class, 'receiptTransaction'])->name('receipt.transaction.pdf');
    });
});

require __DIR__.'/settings.php';
