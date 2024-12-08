<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppMessage;
use Illuminate\Http\Request;

class WhatsAppMessageController extends Controller
{
    public function index()
    {
        $messages = WhatsAppMessage::orderBy('created_at', 'desc')
            ->paginate(50);

        return view('whatsapp.messages.index', compact('messages'));
    }

    public function show($phoneNumber)
    {
        $messages = WhatsAppMessage::where('phone_number', $phoneNumber)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('whatsapp.messages.show', compact('messages', 'phoneNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
            'is_from_me' => 'required|boolean',
            'message_id' => 'nullable|string',
        ]);

        $message = WhatsAppMessage::create($validated);

        return response()->json($message, 201);
    }
}
