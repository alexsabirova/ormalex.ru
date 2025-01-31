<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\CatalogViewMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/catalog/{category:slug?}', CatalogController::class)
    ->middleware(CatalogViewMiddleware::class)
    ->name('catalog');

Route::get('/product/{product:slug}', ProductController::class)
    ->name('product');

Route::controller(\App\Http\Controllers\ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile');
    Route::post('/profile', 'handle')->name('profile.handle');
});

Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
    ->where('method', 'resize|crop|fit')
    ->where('size', '\d+x\d+')
    ->where('file', '.+\.(jpg|jpeg|png|gif|bmp)$')
    ->name('thumbnail');

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'page')->name('login');
    Route::post('/login', 'handle')->name('login.handle');
    Route::delete('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'page')->name('register');
    Route::post('/register', 'handle')->name('register.handle');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'page')
        ->middleware('guest')
        ->name('forgot');

    Route::post('/forgot-password', 'handle')
        ->middleware('guest')
        ->name('forgot.handle');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/reset-password/{token}', 'page')
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', 'handle')
        ->middleware('guest')
        ->name('password-reset.handle');
});

Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/auth/socialite/{driver}', 'redirect')
        ->name('socialite.redirect');
    Route::get('/auth/socialite/{driver}/callback', 'callback')
        ->name('socialite.callback');
});

Route::controller(CartController::class)
    ->prefix('cart')
    ->group(function () {
        Route::get('/', 'index')->name('cart');
        Route::post('/{product}/add', 'add')->name('cart.add');
        Route::patch('/{item}/quantity', 'quantity')->name('cart.quantity');
        Route::delete('/{item}/delete', 'delete')->name('cart.delete');
        Route::delete('/truncate', 'truncate')->name('cart.truncate');
    });

Route::controller(WishlistController::class)
    ->prefix('wishlist')
    ->group(function () {
        Route::get('/', 'index')->name('wishlist');
        Route::post('/{product}/add', 'add')->name('wishlist.add');
        Route::delete('/{item}/delete', 'delete')->name('wishlist.delete');
    });

Route::controller(OrderController::class)
    ->prefix('order')
    ->group(function () {
        Route::get('/', 'index')->name('order');
        Route::post('/', 'handle')->name('order.handle');

        Route::get('/orders-list', 'list')->name('orders.list');
        Route::get('/item-{order}', 'show')->name('order.item');
    });





