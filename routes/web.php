<?php


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

use App\Http\Controllers\ProductController;

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
Route::get('/test', function (){

    dd(strpos('Hamza', 'zaa') );

});

Route::redirect('/', '/dashboard');


Route::get('/reset', function (){
    DB::table('vehicles')->truncate();
    DB::table('vehicle_metas')->truncate();
    dd('Database cleared');
});


Route::group(['middleware' => ['auth']], function () {

    Route::group([
        'prefix' => 'profile',
    ], function () {
        Route::get('/{tab?}', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('account', [ProfileController::class, 'account'])->name('profile.account');

    });



//    Route::post('images', [ImageController::class, 'store'])->name('images.store');
//    Route::post('images/feedback', [ImageController::class, 'store_feedback'])->name('images.store_feedback');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');




    Route::get('api/v1/vehicles', [DatatableController::class, 'vehicles'])->name('vehicles.ajax');

    Route::impersonate();


    Route::group(
        ['middleware' => 'admin',
        ], function () {
        Route::get('logs', [LogViewerController::class, 'index']);

        Route::get('vehicles/upload/buy', [VehicleController::class, 'create_upload_buy'])->name('upload.create.buy');
        Route::get('vehicles/upload/inventory', [VehicleController::class, 'create_upload_inventory'])->name('upload.create.inventory');
        Route::get('vehicles/upload/sold', [VehicleController::class, 'create_upload_sold'])->name('upload.create.sold');


        Route::post('buy/copart', [VehicleController::class, 'import_buy_copart_csv'])->name('buy.copart');
        Route::post('buy/iaai', [VehicleController::class, 'import_buy_iaai_csv'])->name('buy.iaai');
        Route::post('inventory/copart', [VehicleController::class, 'import_inventory_copart_csv'])->name('inventory.copart');
        Route::post('sold/copart', [VehicleController::class, 'import_sold_copart_csv'])->name('sold.copart');

        Route::post('csv/sell', [VehicleController::class, 'import_sale_csv'])->name('csv.sale');
        Route::post('csv/inventory', [VehicleController::class, 'import_inventory_csv'])->name('csv.inventory');

        Route::get('vehicle/location/{location}', [LocationsController::class, 'add_new_location'])->name('location.add');

        Route::resource('vehicles', VehicleController::class)->except('index');
        Route::resource('locations', LocationsController::class);


        Route::resource('users', UsersController::class);
        Route::post('password/{user}', [UsersController::class, 'password_update'])->name('user.password_update');

    });

    Route::resource('vehicles', VehicleController::class)->only('index');

});

//Elections
Route::get('elections', [ElectionController::class, 'index']);
Route::get('update_election', [ElectionController::class, 'update_election']);

