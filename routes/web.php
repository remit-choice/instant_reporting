<?php

use App\Http\Controllers\accounts\receiving\ProfitLossController as ReceivingProfitLossController;
use App\Http\Controllers\accounts\receiving\RevenueController as ReceivingRevenueController;
use App\Http\Controllers\accounts\sending\ProfitLossController;
use App\Http\Controllers\accounts\sending\RevenueController;
use App\Http\Controllers\accounts\TransactionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\BuyerPaymentMethodController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleGroupsController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\ModuleUrlController;
use App\Http\Controllers\OnlineCustomersController;
use App\Http\Controllers\operations\CustomerTransactionsController;
use App\Http\Controllers\operations\TransactionsController as OperationsTransactionsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
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
        return redirect()->route('admin.dashboard.index');
    } else {
        return view('admin.auth.login');
    }
});
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::match(['get', 'post'], '/', [LoginController::class, 'admin_login'])->name('login');
    Route::middleware('Routing')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::view('/forget', 'user.auth.forget');
            Route::view('/reset', 'user.auth.reset');
            Route::post('/forget', [ForgotPasswordController::class, 'user_forget_password']);
            Route::post('/reset', [ResetPasswordController::class, 'user_reset_password']);
        });
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
        });
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
        });
        Route::prefix('currencies')->name('currencies.')->group(function () {
            Route::get('/', [CurrenciesController::class, 'index'])->name('index');
            Route::post('/edit', [CurrenciesController::class, 'update'])->name('update');
            // Route::post('/tests', [CurrenciesController::class, 'index'])->name('currencies.tests');
            Route::prefix('rates')->name('rates.')->group(function () {
                Route::get('/', [CurrenciesController::class, 'rate_index'])->name('index');
                Route::post('/create', [CurrenciesController::class, 'rate_create'])->name('create');
                Route::post('/edit', [CurrenciesController::class, 'rate_update'])->name('update');
                Route::post('/filter', [CurrenciesController::class, 'rate_filter'])->name('filter');
            });
        });
        Route::prefix('buyer')->name('buyer.')->group(function () {
            Route::get('/', [BuyerController::class, 'index'])->name('index');
            Route::post('/create', [BuyerController::class, 'create'])->name('create');
            Route::post('/update', [BuyerController::class, 'update'])->name('update');
            Route::post('/delete', [BuyerController::class, 'delete'])->name('delete');

            Route::prefix('/{id}/pay_method')->name('pay_method.')->group(function () {
                Route::get('/', [BuyerPaymentMethodController::class, 'index'])->name('index');
                Route::post('/create', [BuyerPaymentMethodController::class, 'create'])->name('create');
                Route::post('/update', [BuyerPaymentMethodController::class, 'update'])->name('update');
                Route::post('/delete', [BuyerPaymentMethodController::class, 'delete'])->name('delete');
            });
            Route::prefix('/{id}/report')->name('report.')->group(function () {
                Route::match(['get', 'post'], '/', [BuyerController::class, 'filter'])->name('index');
            });
        });

        Route::prefix('upload_data')->name('upload_data.')->group(function () {
            Route::prefix('transactions')->name('transactions.')->group(function () {
                Route::match(['get', 'post'], '/', [TransactionDataController::class, 'index'])->name('index');
                Route::match(['get', 'post'], '/create', [TransactionDataController::class, 'create'])->name('create');
            });
            Route::prefix('online_customers')->name('online_customers.')->group(function () {
                Route::match(['get', 'post'], '/', [OnlineCustomersController::class, 'index'])->name('index');
                Route::match(['get', 'post'], '/create', [OnlineCustomersController::class, 'create'])->name('create');
            });
        });
        Route::prefix('setting')->group(function () {
            Route::prefix('role')->name('role.')->group(function () {
                Route::get('/', [RolesController::class, 'roles_list'])->name('index');
                Route::match(['get', 'post'], '/create', [RolesController::class, 'add_role'])->name('create');
                Route::match(['get', 'post'], '/edit', [RolesController::class, 'edit_role'])->name('edit');
                Route::post('/delete', [RolesController::class, 'delete_role'])->name('delete');
                Route::prefix('/{id}/permission')->name('permission.')->group(function () {
                    Route::get('/', [PermissionController::class, 'admin_modules_permissions_list'])->name('index');
                    Route::match(['get', 'post'], '/create', [PermissionController::class, 'add_permissions'])->name('create');
                    Route::match(['get', 'post'], '/edit', [PermissionController::class, 'edit_permissions'])->name('edit');
                });
            });
            Route::prefix('module')->name('module.')->group(function () {
                Route::get('/', [ModulesController::class, 'index'])->name('index');
                Route::match(['get', 'post'], '/create', [ModulesController::class, 'create'])->name('create');
                Route::match(['get', 'post'], '/edit', [ModulesController::class, 'edit'])->name('edit');
                Route::post('/delete', [ModulesController::class, 'delete'])->name('delete');
                // Route::prefix('test')->group(function () {
                //     Route::get('/', [ModulesController::class, 'admin_module_test_list'])->name('test.index');
                //     Route::match(['get', 'post'], '/edit', [ModulesController::class, 'edit_admin_test_module'])->name('test.edit');
                // });

                Route::prefix('/{id}/url')->name('url.')->group(function () {
                    Route::get('/', [ModuleUrlController::class, 'index'])->name('index');
                    Route::match(['get', 'post'], '/create', [ModuleUrlController::class, 'create'])->name('create');
                    Route::match(['get', 'post'], '/edit', [ModuleUrlController::class, 'edit'])->name('edit');
                    Route::post('/delete', [ModuleUrlController::class, 'delete'])->name('delete');
                });
                Route::prefix('group')->name('group.')->group(function () {
                    Route::get('/', [ModuleGroupsController::class, 'index'])->name('index');
                    Route::match(['get', 'post'], '/create', [ModuleGroupsController::class, 'create'])->name('create');
                    Route::match(['get', 'post'], '/edit', [ModuleGroupsController::class, 'edit'])->name('edit');
                    Route::post('/delete', [ModuleGroupsController::class, 'delete'])->name('delete');
                });
            });
            Route::prefix('user')->name('user.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::match(['get', 'post'], '/create', [UserController::class, 'create'])->name('create');
                Route::match(['get', 'post'], '/edit', [UserController::class, 'edit'])->name('edit');
                Route::post('/delete', [UserController::class, 'delete'])->name('delete');
            });
        });
        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::prefix('transactions')->name('transactions.')->group(function () {
                //sending sides
                //revenue
                Route::prefix('sending')->name('sending.')->group(function () {
                    Route::prefix('revenue')->name('revenue.')->group(function () {
                        Route::match(['get', 'post'], '/', [RevenueController::class, 'index'])->name('index');
                    });
                    // profit & loss
                    Route::prefix('profit_loss')->name('profit_loss.')->group(function () {
                        Route::match(['get', 'post'], '/', [ProfitLossController::class, 'index'])->name('index');
                    });
                });
                //receiving side 
                //revenue
                Route::prefix('receiving')->name('receiving.')->group(function () {
                    Route::prefix('revenue')->name('revenue.')->group(function () {
                        Route::match(['get', 'post'], '/', [ReceivingRevenueController::class, 'index'])->name('index');
                    });
                    // profit & loss
                    Route::prefix('profit_loss')->name('profit_loss.')->group(function () {
                        Route::match(['get', 'post'], '/', [ReceivingProfitLossController::class, 'index'])->name('index');
                    });
                });
            });
        });
        Route::prefix('operations')->name('operations.')->group(function () {
            Route::prefix('transactions')->name('transactions.')->group(function () {
                //Sending wise hourly transait report
                Route::prefix('hourly')->name('hourly.')->group(function () {
                    Route::match(['get', 'post'], '/', [OperationsTransactionsController::class, 'index'])->name('index');
                });
                Route::prefix('customers')->name('customers.')->group(function () {
                    Route::match(['get', 'post'], '/', [CustomerTransactionsController::class, 'index'])->name('index');
                });
            });
        });
    });
});


//Live Search


//For Server Side Start
Route::get('/cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/storage', function () {
    Artisan::call('storage:link');
    return "Storage is linked";
});
Route::get('/route', function () {
    Artisan::call('route:clear');
    return "Route is Cleared";
});
Route::get('/migrate', function () {
    Artisan::call('migrate_in_order');
    return "Database Migrated Successfully";
});
Route::get('/seeding', function () {
    Artisan::call('db:seed');
    return "Database Seeding Successfully";
});
Route::get('/queue-clear', function () {
    Artisan::call('queue:clear', [
        '--force' => true
    ]);
    return "Queue Successfully Clear";
});
Route::get('/cleareverything', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";
});
//For Server Side End
