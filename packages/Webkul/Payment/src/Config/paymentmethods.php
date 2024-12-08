<?php

return [
    'cashondelivery'  => [
        'code'        => 'cashondelivery',
        'title'       => 'Cash On Delivery',
        'description' => 'Cash On Delivery',
        'class'       => 'Webkul\Payment\Payment\CashOnDelivery',
        'active'      => true,
        'sort'        => 1,
    ],

    'moneytransfer'   => [
        'code'        => 'moneytransfer',
        'title'       => 'Money Transfer',
        'description' => 'Money Transfer',
        'class'       => 'Webkul\Payment\Payment\MoneyTransfer',
        'active'      => true,
        'sort'        => 2,
    ],


    'mercadopago' => [
        'code'        => 'mercadopago',
        'title'       => 'Mercado Pago',
        'description' => 'Pague con Mercado Pago',
        'class'       => 'Webkul\Payment\Payment\MercadoPago',
        'active'      => true,
        'sort'        => 3,
    ],
];
