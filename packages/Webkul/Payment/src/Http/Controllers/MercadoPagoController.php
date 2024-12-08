<?php

namespace Webkul\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function redirect()
    {
        try {
            $cart = Cart::getCart();
            $items = [];

            foreach ($cart->items as $item) {
                $items[] = [
                    'title' => $item->name,
                    'quantity' => $item->quantity,
                    'currency_id' => core()->getCurrentCurrency()->code,
                    'unit_price' => (float) $item->price
                ];
            }

            $preference = [
                'items' => $items,
                'back_urls' => [
                    'success' => route('mercadopago.success'),
                    'failure' => route('mercadopago.failure'),
                    'pending' => route('mercadopago.pending')
                ],
                'auto_return' => 'approved',
                'binary_mode' => true
            ];

            $accessToken = core()->getConfigData('sales.payment_methods.mercadopago.access_token');

            // Agregar log para depuración
            \Log::info('MercadoPago Request:', [
                'access_token' => substr($accessToken, 0, 10) . '...',  // Solo mostramos parte del token por seguridad
                'preference' => $preference
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post('https://api.mercadopago.com/checkout/preferences', $preference);

            // Agregar log de la respuesta
            \Log::info('MercadoPago Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['init_point'])) {
                    return redirect()->to($data['init_point']);
                }
            }

            throw new \Exception('Could not create MercadoPago preference. Response: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('MercadoPago Error: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handles the response from MercadoPago for successful payments
     */
    public function success(Request $request)
    {
        try {
            if ($request->get('status') === 'approved') {
                $order = $this->orderRepository->create(Cart::prepareDataForOrder());

                Cart::deActivateCart();

                session()->flash('order', $order);

                return redirect()->route('shop.checkout.success');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handles the response from MercadoPago for failed payments
     */
    public function failure()
    {
        session()->flash('error', 'MercadoPago payment failed!');

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handles the response from MercadoPago for pending payments
     */
    public function pending()
    {
        session()->flash('warning', 'Payment is pending.');

        return redirect()->route('shop.checkout.cart.index');
    }
}
