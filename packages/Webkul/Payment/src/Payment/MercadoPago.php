<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class MercadoPago extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'mercadopago';

    /**
     * Get redirect url
     */
    public function getRedirectUrl()
    {
        return route('mercadopago.redirect');
    }

    /**
     * Is method available?
     */
    public function isAvailable()
    {
        return $this->getConfigData('active') &&
               $this->getConfigData('access_token');
    }

    /**
     * Get payment method image
     */
    public function getImage()
    {
        // Intenta usar la imagen configurada primero
        $configuredImage = $this->getConfigData('image');
        if ($configuredImage && Storage::exists($configuredImage)) {
            return Storage::url($configuredImage);
        }

        // Si no hay imagen configurada, usa la imagen por defecto
        $defaultImage = 'vendor/webkul/shop/assets/images/mercadopago.png';
        return asset($defaultImage);
    }

    /**
     * Additional payment method information
     */
    public function getAdditionalDetails()
    {
        if (empty($this->getConfigData('instructions'))) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.index.sales.payment-methods.instructions'),
            'value' => $this->getConfigData('instructions'),
        ];
    }
}
