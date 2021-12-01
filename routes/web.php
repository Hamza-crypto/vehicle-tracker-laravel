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

//cd /giftcash && php artisan schedule:run >> /dev/null 2>&1
//php artisan schedule:run >> /dev/null 2>&1
Route::redirect('/', '/dashboard');

Route::get('test', function (){

});


Route::group(['middleware' => ['auth']], function () {

    Route::group([
        'prefix' => 'paxful',
    ], function () {

        // Balance
        Route::get('/balance', [PaxfulTrades::class, 'get_paxful_balance'])->name('api.paxful.balanceget_trade_chat');

        // Offers
        Route::get('/offers/all', [PaxfulTrades::class, 'getAllOffers'])->name('api.offers_all');
        Route::get('/offer-edit/{hash}', [PaxfulTrades::class, 'editOffer'])->name('api.offer.edit');
        Route::put('/offer-update/{hash}', [PaxfulTrades::class, 'updateOffer'])->name('api.offer.update');
        Route::get('/offer-activate/{hash}', [PaxfulTrades::class, 'activateOffer'])->name('api.offer.activate');
        Route::get('/offer-deactivate/{hash}', [PaxfulTrades::class, 'dectivateOffer'])->name('api.offer.deactivate');

        // Trades
        Route::get('/trades/active', [PaxfulTrades::class, 'getActiveTrades'])->name('api.trades_active');
        Route::get('/trades/active2/{load_images}', [PaxfulTrades::class, 'getActiveTrades2'])->name('api.trades_active2');
        Route::get('/trades/completed', [PaxfulTrades::class, 'getCompletedTrades'])->name('api.trades_completed');
        Route::get('/trade/chat/{hash}/{author}', [PaxfulTrades::class, 'get_trade_chat_view'])->name('api.trade_chat');
        Route::get('/trade/chat/vue/{hash}/{author}/{load_images}', [PaxfulTrades::class, 'get_trade_chat'])->name('api.trade_chat.vue');
        Route::get('/getimages/{hash}', [PaxfulTrades::class, 'get_images'])->name('api.trade_chat.image');

        //  Trade messages/images
        Route::get('/trade/chat/send/{hash}/{msg}', [PaxfulTrades::class, 'set_trade_chat'])->name('api.trade_chat.msg.post');
        Route::post('/trade/chat/img/upload', [PaxfulTrades::class, 'upload_img'])->name('api.trade_chat.img.post');
        Route::post('/trade/chat/img/upload2', [PaxfulTrades::class, 'upload_img_with_public_url'])->name('api.trade_chat.img.post2');
        Route::get('/trade/release/payment/{hash}', [PaxfulTrades::class, 'release_coin'])->name('api.trade.payment.release');
        Route::get('/trade/cancel/{hash}', [PaxfulTrades::class, 'cancel_trade'])->name('api.trade.cancel');
        Route::get('/trade/dispute/{hash}/{reason}', [PaxfulTrades::class, 'dispute'])->name('api.trade.dispute');
        Route::post('/trade/balance/check', [PaxfulTrades::class, 'get_balance_screenshot'])->name('api.trade.balance.check');
        Route::get('/trade/card_status/{card_number}', [PaxfulTrades::class, 'check_card_status'])->name('api.trade.card.status');

        Route::post('/trade/card/add', [PaxfulTrades::class, 'add_card'])->name('api.trade.card.add');

        Route::get('/users', [ZohoController::class, 'users'])->name('zoho.test');
        Route::get('/orders/{id}', [ZohoController::class, 'orders'])->name('zoho.test');



    });

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

        Route::get('logs', [LogViewerController::class, 'index']);
        Route::resource('bins', BinController::class);
        Route::resource('messages', MessageController::class);
        Route::resource('groups', GroupController::class);
        Route::resource('post_messages', PostMessageController::class);
        Route::resource('gateways', GatewayController::class);
        Route::resource('order_categories', OrderCategoryController::class);
        Route::resource('users', UsersController::class);
        Route::post('password/{user}', [UsersController::class, 'password_update'])->name('user.password_update');
//
    });

});
