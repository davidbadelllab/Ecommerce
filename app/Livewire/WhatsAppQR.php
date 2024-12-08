<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class WhatsAppQR extends Component
{
    public $qrCode = '';

    protected $listeners = ['updateQrCode'];

    public function mount() {}


    public function updateQrCode($qr)
    {
        Log::debug('QR recibido en Livewire:', [
            'longitud' => strlen($qr),
            'muestra' => substr($qr, 0, 50),
        ]);

        $this->qrCode = $qr;
    }


    public function index()
    {
        return view('admin::whatsapp.templates.index');
    }
}
