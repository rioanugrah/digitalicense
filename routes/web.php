<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes(['verify' => true]);
Route::domain(parse_url(env('APP_URL'), PHP_URL_HOST))->group(function () {
    Route::get('/', function () {
        // return view('welcome');
        return redirect()->route('login');
    });
    Route::group(['middleware' => ['auth','verified']], function () {
        Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('users', App\Http\Controllers\UserController::class);

        Route::prefix('category')->group(function () {
            Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('category');
            Route::get('create', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
            Route::post('simpan', [App\Http\Controllers\CategoryController::class, 'simpan'])->name('category.simpan');
            Route::get('{id}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('category.edit');
            Route::post('{id}/update', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update');
            Route::post('{id}/delete', [App\Http\Controllers\CategoryController::class, 'delete'])->name('category.delete');
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
            Route::get('create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
            Route::post('simpan', [App\Http\Controllers\ProductController::class, 'simpan'])->name('products.simpan');
            Route::get('{slug}/{id}', [App\Http\Controllers\ProductController::class, 'detail'])->name('products.detail');
            Route::get('{slug}/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
            Route::post('{slug}/{id}/update', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
            Route::get('{slug}/{id}/checkout', [App\Http\Controllers\ProductController::class, 'checkout'])->name('products.checkout');
            Route::post('{slug}/{id}/checkout/buy', [App\Http\Controllers\ProductController::class, 'checkout_buy'])->name('products.checkout_buy');
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('orders');
            Route::get('{order_code}/{id}', [App\Http\Controllers\OrderController::class, 'detail'])->name('orders.detail');
        });

        Route::prefix('transactions')->group(function () {
            Route::get('/', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
            Route::get('{order_code}', [App\Http\Controllers\TransactionController::class, 'detail'])->name('transactions.detail');
            Route::get('{order_code}/{order_reference}/validate', [App\Http\Controllers\TransactionController::class, 'check_transaction'])->name('transactions.check_transaction');
        });

        Route::get('invoice',function(){
            return 'invoice';
        })->name('invoice');

        Route::prefix('permissions')->group(function () {
            Route::get('/', [App\Http\Controllers\PermissionsController::class, 'index'])->name('permissions');
            Route::get('create', [App\Http\Controllers\PermissionsController::class, 'create'])->name('permissions.create');
            Route::post('simpan', [App\Http\Controllers\PermissionsController::class, 'simpan'])->name('permissions.simpan');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
            Route::post('update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        });

        // Route::prefix('tripay')->group(function () {
        //     Route::get('/', [App\Http\Controllers\Payment\TripayController::class, 'getPayment']);
        // });
        Route::get('test_notif', function(){            // new App\Notifications\NotificationNotif;
            $notif = [
                'id' => 1,
                'url' => 'http://localhost:8000',
                'title' => 'Notif Baru',
                'message' => 'Pesanan Baru - Sedang Melakukan Pembayaran',
                'color_icon' => 'warning',
                'icon' => 'uil-clipboard-alt',
                'publish' => \Carbon\Carbon::now(),
            ];
            // $user = auth()->user();
            $user = \App\Models\User::where('id',1)->orWhere('id',auth()->user()->id)->get();
            Notification::send($user,new App\Notifications\NotificationNotif($notif));
            event(new App\Events\NotificationEvent($notif['id'],$notif['url'],$notif['title'],$notif['message'],$notif['color_icon'],$notif['icon'],$notif['publish']));
        });

        // Route::post('mark-as-read', 'NotifikasiController@markNotification')->name('markNotification');
        Route::post('mark-as-read', [App\Http\Controllers\NotifikasiController::class, 'markNotification'])->name('markNotification');
    });
});

