<?php

use App\Http\Controllers\Api\ZohoController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ManualFeedbackController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaxfulTrades;
use App\Http\Controllers\PostMessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VehicleController;
use App\Models\Order;
use App\Models\PostMessage;
use App\Models\Settings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

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


Route::redirect('/', '/dashboard');

Route::get('test', function (){

});


Route::group(['middleware' => ['auth']], function () {

    Route::group([
        'prefix' => 'profile',
    ], function () {
        Route::get('/{tab?}', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('account', [ProfileController::class, 'account'])->name('profile.account');

    });

    Route::get('orders/export', [ExportController::class, 'export_with_cvc'])->name('orders.export');
    Route::get('orders/full_export', [ExportController::class, 'full_export_with_cvc'])->name('orders.export.full');
    Route::get('users/export', [ExportController::class, 'export_users'])->name('users.export');

    Route::post('images', [ImageController::class, 'store'])->name('images.store');
    Route::post('images/feedback', [ImageController::class, 'store_feedback'])->name('images.store_feedback');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('feedbacks', ManualFeedbackController::class);

    Route::resource('orders', OrderController::class);
    Route::get('order/accept/{order}', [OrderController::class, 'accept_card_status'])->name('order.accept');
    Route::get('order/reject/{order}', [OrderController::class, 'reject_card_status'])->name('order.reject');
    Route::get('order/void/{order}', [OrderController::class, 'void_card_status'])->name('order.void');
    Route::get('order/paid/{array}{action}', [OrderController::class, 'mark_as_paid'])->name('orders.paid');
    Route::get('order/used/{order}', [OrderController::class, 'change_used_status'])->name('order.used');
    Route::get('api/v1/orders_update', [DatatableController::class, 'orders'])->name('orders.ajax');

    Route::patch('user/wallet/{user}', [UsersController::class, 'update_wallet_info'])->name('wallet.update');
    Route::patch('user/gateway/{user}', [UsersController::class, 'update_gateway'])->name('gateway.update');
    Route::patch('user/parent/{user}', [UsersController::class, 'update_parent'])->name('parent.update');
    Route::patch('user/paxful_api/{user}', [ProfileController::class, 'update_paxful_api'])->name('api.store');
    Route::patch('user/availability_status', [ProfileController::class, 'update_availability_status'])->name('available.status');
    Route::patch('user/payable_visibility_status/{user}', [UsersController::class, 'update_payable_section'])->name('payable.visibility');
    Route::patch('user/assign_category/{user}', [UsersController::class, 'update_order_category'])->name('user.category.assign');

    // Report
    Route::get('reports/payable', [ReportController::class, 'payable'])->name('report.payable');
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('report.daily');
    Route::get('manager/subuser/{id}', [ReportController::class, 'get_sub_users'])->name('manager.users');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update_settings'])->name('settings.update');

    Route::group([], function () {

        Route::resource('tags', TagController::class);
    });

    Route::impersonate();

    Route::group(
        ['middleware' => 'admin',
        ], function () {

        Route::get('vehicles/upload/buy', [VehicleController::class, 'create_upload_buy'])->name('upload.create.buy');
        Route::get('vehicles/upload/inventory', [VehicleController::class, 'create_upload_inventory'])->name('upload.create.inventory');
        Route::get('vehicles/upload/sold', [VehicleController::class, 'create_upload_sold'])->name('upload.create.sold');

        Route::resource('vehicles', VehicleController::class);

        Route::post('buy/copart', [VehicleController::class, 'import_buy_copart_csv'])->name('buy.copart');
        Route::post('buy/iaai', [VehicleController::class, 'import_buy_iaai_csv'])->name('buy.iaai');
        Route::post('inventory/copart', [VehicleController::class, 'import_inventory_copart_csv'])->name('inventory.copart');
        Route::post('sold/copart', [VehicleController::class, 'import_sold_copart_csv'])->name('sold.copart');

        Route::post('csv/sell', [VehicleController::class, 'import_sale_csv'])->name('csv.sale');
        Route::post('csv/inventory', [VehicleController::class, 'import_inventory_csv'])->name('csv.inventory');


        Route::resource('users', UsersController::class);
        Route::post('password/{user}', [UsersController::class, 'password_update'])->name('user.password_update');

    });

});
