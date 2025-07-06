<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;

Route::get('/', [TransactionController::class, 'chartPendapatanBulanan'])->name('grafik.pendapatan');

Route::get('/items/search', [ItemController::class, 'search']);

Route::resource('items', ItemController::class);

Route::patch('/items/{item}/update-stok', [ItemController::class, 'updateStok'])->name('items.update-stok');

Route::get('/items/{id}/checkout', [TransactionController::class, 'checkoutForm'])->name('items.checkout');

Route::post('/items/{id}/checkout', [TransactionController::class, 'processCheckout'])->name('items.checkout.process');

Route::get('/nota/{id}/cetak', [TransactionController::class, 'cetakNota'])->name('nota.cetak');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{item}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/nota/{transaction}', [CartController::class, 'printNota'])->name('cart.nota');
