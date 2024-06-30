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
    // Route::get('/', function () {
    //     return view('frontend.index');
    //     // return redirect()->route('login');
    // })->name('frontend');
    Route::prefix('/')->group(function () {
        Route::get('/', [App\Http\Controllers\FrontendController::class, 'index'])->name('frontend');
        Route::get('product/{category}', [App\Http\Controllers\FrontendController::class, 'product'])->name('frontend.product');
        Route::get('product/{category}/{category_id}', [App\Http\Controllers\FrontendController::class, 'product_category'])->name('frontend.product_category');
        Route::get('product/{category}/{category_id}/{slug}', [App\Http\Controllers\FrontendController::class, 'product_detail'])->name('frontend.product_detail');
        Route::get('product/{category}/{category_id}/{slug}/checkout', [App\Http\Controllers\FrontendController::class, 'product_checkout'])->name('frontend.product_checkout');
        Route::post('product/{category}/{category_id}/{slug}/checkout/buy', [App\Http\Controllers\FrontendController::class, 'product_checkout_buy'])->name('frontend.product_checkout_buy');
        // Route::get('product/{category}/{slug}/detail', [App\Http\Controllers\FrontendController::class, 'product_detail'])->name('frontend.product_detail');
        // Route::get('product/{category}/{slug}/checkout', [App\Http\Controllers\FrontendController::class, 'product_checkout'])->name('frontend.product_checkout');
        // Route::post('product/{category}/{slug}/checkout/buy', [App\Http\Controllers\FrontendController::class, 'product_checkout_buy'])->name('frontend.product_checkout_buy');

        // Route::get('product/{slug}/detail', [App\Http\Controllers\FrontendController::class, 'product_detail'])->name('frontend.product_detail');
    });
    Route::group(['middleware' => ['auth','verified']], function () {
        Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');
        Route::resource('roles', App\Http\Controllers\RoleController::class)->middleware('verified');
        Route::resource('users', App\Http\Controllers\UserController::class)->middleware('verified');

        Route::prefix('b/category')->group(function () {
            Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('category')->middleware('verified');
            Route::get('create', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create')->middleware('verified');
            Route::post('simpan', [App\Http\Controllers\CategoryController::class, 'simpan'])->name('category.simpan')->middleware('verified');
            Route::get('{id}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('category.edit')->middleware('verified');
            Route::post('{id}/update', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update')->middleware('verified');
            Route::post('{id}/delete', [App\Http\Controllers\CategoryController::class, 'delete'])->name('category.delete')->middleware('verified');
            Route::get('{id}/detail/{slug}', [App\Http\Controllers\CategoryController::class, 'category_detail'])->name('category.category_detail')->middleware('verified');
            Route::get('{id}/detail/{slug}/create', [App\Http\Controllers\CategoryController::class, 'category_detail_create'])->name('category.category_detail_create')->middleware('verified');
            Route::post('{id}/detail/{slug}/simpan', [App\Http\Controllers\CategoryController::class, 'category_detail_simpan'])->name('category.category_detail_simpan')->middleware('verified');
            Route::get('{id}/detail/{slug}/edit/{id_category}', [App\Http\Controllers\CategoryController::class, 'category_detail_edit'])->name('category.category_detail_edit')->middleware('verified');
            Route::post('{id}/detail/{slug}/edit/{id_category}/update', [App\Http\Controllers\CategoryController::class, 'category_detail_update'])->name('category.category_detail_update')->middleware('verified');
        });

        Route::prefix('b/products')->group(function () {
            Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('products')->middleware('verified');
            Route::get('create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create')->middleware('verified');
            Route::get('search', [App\Http\Controllers\ProductController::class, 'search'])->name('products.search')->middleware('verified');
            Route::post('simpan', [App\Http\Controllers\ProductController::class, 'simpan'])->name('products.simpan')->middleware('verified');
            Route::get('category_detail/{category_id}', [App\Http\Controllers\ProductController::class, 'category_detail'])->name('products.category_detail')->middleware('verified');
            Route::get('{slug}/{id}', [App\Http\Controllers\ProductController::class, 'detail'])->name('products.detail')->middleware('verified');
            Route::get('{slug}/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit')->middleware('verified');
            Route::post('{slug}/{id}/update', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update')->middleware('verified');
            Route::post('{slug}/{id}/delete', [App\Http\Controllers\ProductController::class, 'delete'])->name('products.delete')->middleware('verified');
            Route::get('{slug}/{id}/checkout', [App\Http\Controllers\ProductController::class, 'checkout'])->name('products.checkout')->middleware('verified');
            Route::post('{slug}/{id}/checkout/buy', [App\Http\Controllers\ProductController::class, 'checkout_buy'])->name('products.checkout_buy')->middleware('verified');
        });

        Route::prefix('b/orders')->group(function () {
            Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('orders');
            Route::get('{order_code}/{id}', [App\Http\Controllers\OrderController::class, 'detail'])->name('orders.detail')->middleware('verified');
            Route::post('{order_code}/{id}/license/simpan', [App\Http\Controllers\OrderController::class, 'detail_input_license_simpan'])->name('orders.detail_input_license_simpan')->middleware('verified');
        });

        Route::prefix('b/transactions')->group(function () {
            Route::get('/', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
            Route::get('{order_code}', [App\Http\Controllers\TransactionController::class, 'detail'])->name('transactions.detail')->middleware('verified');
            Route::get('{order_code}/{order_reference}/validate', [App\Http\Controllers\TransactionController::class, 'check_transaction'])->name('transactions.check_transaction')->middleware('verified');
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/', [App\Http\Controllers\PermissionsController::class, 'index'])->name('permissions')->middleware('verified');
            Route::get('create', [App\Http\Controllers\PermissionsController::class, 'create'])->name('permissions.create')->middleware('verified');
            Route::post('simpan', [App\Http\Controllers\PermissionsController::class, 'simpan'])->name('permissions.simpan')->middleware('verified');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile')->middleware('verified');
            Route::post('update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('verified');
        });

        Route::prefix('tripay')->group(function () {
            Route::get('/', [App\Http\Controllers\Payment\TripayController::class, 'getPayment'])->middleware('verified');
        });
        // Route::get('test_notif', function(){            // new App\Notifications\NotificationNotif;
        //     $notif = [
        //         'id' => 1,
        //         'url' => 'http://localhost:8000',
        //         'title' => 'Notif Baru',
        //         'message' => 'Pesanan Baru - Sedang Melakukan Pembayaran',
        //         'color_icon' => 'warning',
        //         'icon' => 'uil-clipboard-alt',
        //         'publish' => \Carbon\Carbon::now(),
        //     ];
        //     // $user = auth()->user();
        //     $user = \App\Models\User::where('id',1)->orWhere('id',auth()->user()->id)->get();
        //     Notification::send($user,new App\Notifications\NotificationNotif($notif));
        //     event(new App\Events\NotificationEvent($notif['id'],$notif['url'],$notif['title'],$notif['message'],$notif['color_icon'],$notif['icon'],$notif['publish']));
        // });

        // Route::get('test_telegram', function(){
        //     $user = \App\Models\User::where('id',1)->orWhere('id',auth()->user()->id)->get();
        //     // return $user;
        //     Notification::send($user, new App\Notifications\TelegramNotif(752617291));
        // });

        Route::get('testing_email', [App\Http\Controllers\TestingController::class, 'testing_email']);

        // Route::post('mark-as-read', 'NotifikasiController@markNotification')->name('markNotification');
        Route::post('mark-as-read', [App\Http\Controllers\NotifikasiController::class, 'markNotification'])->name('markNotification');
    });
});

