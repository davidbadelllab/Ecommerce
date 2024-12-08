<?php

namespace Webkul\MercadoPago\Payment;

use Webkul\Payment\Payment\Payment;

class MercadoPago extends Payment
{
    /**
     * Payment method code
     */
    protected $code = 'mercadopago';

    public function getRedirectUrl()
    {
        return route('mercadopago.redirect');
    }
}
