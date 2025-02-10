<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PayPalinController;

Route::get('checkout', [PayPalinController::class, 'checkout'])->name('checkout');
Route::get('paypal', [PayPalinController::class, 'index'])->name('paypal');
Route::post('paypal/payment', [PayPalinController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [PayPalinController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('/order-done', function () {
    return view('order_done');
})->name('order.done');
Route::get('paypal/payment/cancel', [PayPalinController::class, 'paymentCancel'])->name('paypal.payment.cancel');


Route::get('/', [ProductController::class, 'fetchProducts'])->name('products.fetch');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');  // List of products

Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [ProductController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/update/{id}', [ProductController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove/{id}', [ProductController::class, 'removeItem'])->name('cart.remove');
