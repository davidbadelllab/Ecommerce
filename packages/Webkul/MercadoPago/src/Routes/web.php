<?php

use Webkul\Payment\Http\Controllers\MercadoPagoController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::prefix('mercadopago')->group(function () {
        Route::get('redirect', [MercadoPagoController::class, 'redirect'])->name('mercadopago.redirect');
        Route::get('success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
        Route::get('failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');
        Route::get('pending', [MercadoPagoController::class, 'pending'])->name('mercadopago.pending');
    });
});
