<?php

use App\Http\Controllers\WhatsAppMessageController;
use Webkul\Payment\Http\Controllers\MercadoPagoController;
use Webkul\Admin\Http\Controllers\WhatsappController;

Route::group(['middleware' => ['web']], function () {
    Route::get('mercadopago/redirect', [MercadoPagoController::class, 'redirect'])->name('mercadopago.redirect');
    Route::get('mercadopago/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
    Route::get('mercadopago/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');
    Route::get('mercadopago/pending', [MercadoPagoController::class, 'pending'])->name('mercadopago.pending');

    Route::get('/admin/whatsapp', [WhatsappController::class, 'index'])
        ->name('admin.whatsapp.index');

    Route::get('/admin/whatsapp/templates', [WhatsappController::class, 'templates'])
        ->name('admin.whatsapp.templates.index');

    Route::get('/admin/whatsapp/messages', [WhatsappController::class, 'messages'])
        ->name('admin.whatsapp.messages.index');

    Route::get('/admin/whatsapp/columns-atention', [WhatsappController::class, 'columnsAttention'])
        ->name('admin.whatsapp.columns.atention');
});
