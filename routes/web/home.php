<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index']);

Route::get('/test', function () {
   $module = \Module::find('Discount');
   $module->enable();
});
//Route::get('/', function () {
//    //signature route
////    return \Illuminate\Support\Facades\URL::temporarySignedRoute('download.file', now()->addMinutes(30), ['user' => auth()->user()->id, 'path' => '/files/IMG_20200509_125148.jpg']);
//
//    return view('welcome');
//});

Auth::routes(['verify' => true]);
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleAuthController::class, 'callback']);
Route::get('/auth/token', [App\Http\Controllers\Auth\AuthTokenController::class, 'getToken'])->name('2fa.token');
Route::post('/auth/token', [App\Http\Controllers\Auth\AuthTokenController::class, 'postToken']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/secret', function (){
    return 'secret';
})->middleware(['auth', 'password.confirm']);
Route::get('download/{user}/file', function ($file) {
    return \Illuminate\Support\Facades\Storage::download(request('path'));
})->name('download.file')->middleware('signed');

Route::middleware('auth')->group(function() {
    Route::prefix('Profile')->group(function() {
        Route::get('/', [App\Http\Controllers\Profile\IndexController::class, 'index'])->name('Profile');
        Route::get('twofactor', [App\Http\Controllers\Profile\TwoFactorAuthController::class, 'manageTwoFactor'])->name('Profile.2fa.manage');
        Route::post('twofactor', [App\Http\Controllers\Profile\TwoFactorAuthController::class, 'postManageTwoFactor']);

        Route::get('twofacto/phone', [App\Http\Controllers\Profile\TokenAuthController::class, 'getPhoneVerify'])->name('Profile.2fa.phone');
        Route::post('twofacto/phone', [App\Http\Controllers\Profile\TokenAuthController::class, 'postPhoneVerify']);

        Route::get('orders', [\App\Http\Controllers\Profile\OrderController::class, 'index'])->name('Profile.orders');
        Route::get('orders/{order}', [\App\Http\Controllers\Profile\OrderController::class, 'showDetails'])->name('Profile.orders.detail');
        Route::get('orders/{order}/payment', [\App\Http\Controllers\Profile\OrderController::class, 'payment'])->name('Profile.orders.payment');
    });
    Route::post('comments', [\App\Http\Controllers\HomeController::class, 'comment'])->name('send.comment');
    Route::post('payment', [\App\Http\Controllers\PaymentController::class, 'payment'])->name('cart.payment');
    Route::get('payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
});

Route::get('products', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('products/{product}',[App\Http\Controllers\ProductController::class, 'single']);

