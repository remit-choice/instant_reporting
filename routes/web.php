<?php

use App\Http\Controllers\accounts\TransactionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleGroupsController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\OnlineCustomersController;
use App\Http\Controllers\operations\TransactionsController as OperationsTransactionsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TransactionDataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Session::has('r_id') && Session::get('status') == 1) {
        return redirect('/admin/dashboard');
    } else {
        return view('admin.auth.login');
    }
});
Route::post('/logout', [LogoutController::class, 'logout']);

Route::prefix('admin')->group(function () {
    Route::match(['get', 'post'], '/', [LoginController::class, 'admin_login'])->name('admin');

    Route::middleware('Routing')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::view('/forget', 'user.auth.forget');
            Route::view('/reset', 'user.auth.reset');
            Route::post('/forget', [ForgotPasswordController::class, 'user_forget_password']);
            Route::post('/reset', [ResetPasswordController::class, 'user_reset_password']);
        });
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'admin_dashboard'])->name("admin.dashboard");
        });
        Route::prefix('currencies')->group(function () {
            Route::get('/', [CurrenciesController::class, 'index'])->name('admin.currencies');
            Route::post('/edit', [CurrenciesController::class, 'update'])->name('admin.currencies.update');
            // Route::post('/tests', [CurrenciesController::class, 'index'])->name('admin.currencies.tests');
            Route::prefix('rates')->group(function () {
                Route::get('/', [CurrenciesController::class, 'rate_index'])->name('admin.currencies.rates');
                Route::post('/create', [CurrenciesController::class, 'rate_create'])->name('admin.currencies.rates.create');
                Route::post('/edit', [CurrenciesController::class, 'rate_update'])->name('admin.currencies.rates.update');
                Route::post('/filter', [CurrenciesController::class, 'rate_filter'])->name('admin.currencies.rates.filter');
            });
        });
        Route::prefix('upload_data')->group(function () {
            Route::prefix('transactions')->group(function () {
                Route::get('/', [TransactionDataController::class, 'index'])->name('admin.upload_data.transactions');
                Route::post('/', [TransactionDataController::class, 'filter'])->name('admin.upload_data.transactions');
                Route::match(['get', 'post'], '/create', [TransactionDataController::class, 'create'])->name('admin.upload_data.transactions.create');
            });
            Route::prefix('online_customers')->group(function () {
                Route::get('/', [OnlineCustomersController::class, 'index'])->name('admin.upload_data.online_customers');
                Route::post('/', [OnlineCustomersController::class, 'filter'])->name('admin.upload_data.online_customers');
                Route::match(['get', 'post'], '/create', [OnlineCustomersController::class, 'create'])->name('admin.upload_data.online_customers.create');
            });
        });
        Route::prefix('setting')->group(function () {
            Route::prefix('role')->group(function () {
                Route::get('/', [RolesController::class, 'roles_list'])->name('admin.role.index');
                Route::match(['get', 'post'], '/create', [RolesController::class, 'add_role'])->name('admin.role.create');
                Route::match(['get', 'post'], '/edit', [RolesController::class, 'edit_role'])->name('admin.role.edit');
                Route::post('/delete', [RolesController::class, 'delete_role'])->name('admin.role.delete');
                Route::prefix('/{id}/permission')->group(function () {
                    Route::get('/', [PermissionController::class, 'admin_modules_permissions_list'])->name('admin.role.permission.index');
                    Route::match(['get', 'post'], '/create', [PermissionController::class, 'add_permissions'])->name('admin.role.permission.create');
                    Route::match(['get', 'post'], '/edit', [PermissionController::class, 'edit_permissions'])->name('admin.role.permission.edit');
                });
            });
            Route::prefix('module')->group(function () {
                Route::get('/', [ModulesController::class, 'admin_module_list'])->name('admin.module.index');
                Route::match(['get', 'post'], '/create', [ModulesController::class, 'add_admin_module'])->name('admin.module.create');
                Route::match(['get', 'post'], '/edit', [ModulesController::class, 'edit_admin_module'])->name('admin.module.edit');
                Route::post('/delete', [ModulesController::class, 'delete_admin_module'])->name('admin.module.delete');

                // Route::prefix('test')->group(function () {
                //     Route::get('/', [ModulesController::class, 'admin_module_test_list'])->name('admin.module.test.index');
                //     Route::match(['get', 'post'], '/edit', [ModulesController::class, 'edit_admin_test_module'])->name('admin.module.test.edit');
                // });


                Route::prefix('/{id}/url')->group(function () {
                    Route::get('/', [ModulesController::class, 'admin_module_url_list'])->name('admin.module.url.index');
                    Route::match(['get', 'post'], '/create', [ModulesController::class, 'add_admin_module_url'])->name('admin.module.url.create');
                    Route::match(['get', 'post'], '/edit', [ModulesController::class, 'edit_admin_module_url'])->name('admin.module.url.edit');
                    Route::post('/delete', [ModulesController::class, 'delete_admin_module_url'])->name('admin.module.url.delete');
                });
                Route::prefix('group')->group(function () {
                    Route::get('/', [ModuleGroupsController::class, 'admin_module_group_list'])->name('admin.modules_groups.index');
                    Route::match(['get', 'post'], '/create', [ModuleGroupsController::class, 'add_admin_module_group'])->name('admin.modules_groups.create');
                    Route::match(['get', 'post'], '/edit', [ModuleGroupsController::class, 'edit_admin_module_group'])->name('admin.modules_groups.edit');
                    Route::post('/delete', [ModuleGroupsController::class, 'delete_admin_module_group'])->name('admin.modules_groups.delete');
                });
            });
            Route::prefix('user')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
                Route::match(['get', 'post'], '/create', [UserController::class, 'create'])->name('admin.user.create');
                Route::match(['get', 'post'], '/edit', [UserController::class, 'edit'])->name('admin.user.edit');
                Route::post('/delete', [UserController::class, 'delete'])->name('admin.user.delete');
            });
        });
        Route::prefix('accounts')->group(function () {
            Route::prefix('transactions')->group(function () {
                //sending side revenue
                Route::prefix('sending')->group(function () {
                    Route::get('/', [TransactionsController::class, 'sending_index'])->name('admin.accounts.transactions.sending_side_revenue');
                    Route::post('/', [TransactionsController::class, 'sending_filter'])->name('admin.accounts.transactions.sending_side_revenue');
                });
                //receiving side revenue
                Route::prefix('receiving')->group(function () {
                    Route::get('/', [TransactionsController::class, 'receiving_index'])->name('admin.accounts.transactions.receiving_side_revenue');
                    Route::post('/', [TransactionsController::class, 'receiving_filter'])->name('admin.accounts.transactions.receiving_side_revenue');
                });
            });
        });
        Route::prefix('operations')->group(function () {
            Route::prefix('transactions')->group(function () {
                //Sending wise hourly transait report
                Route::prefix('hourly')->group(function () {
                    Route::get('/', [OperationsTransactionsController::class, 'sending_index'])->name('admin.operations.transactions.hourly');
                    Route::post('/', [OperationsTransactionsController::class, 'sending_filter'])->name('admin.operations.transactions.hourly');
                });
            });
        });
    });
});



Route::get('/cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/storage', function () {
    Artisan::call('storage:link');
    return "Storage is linked";
});
Route::get('/migrate', function () {
    Artisan::call('migrate_in_order');
    return "Database Migrated Successfully";
});
