<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WhatsAppMessage;
use Livewire\WithPagination;

class WhatsAppChat extends Component
{
    use WithPagination;

    public $selectedNumber = null;
    public $newMessage = '';
    public $contacts = [];
    public $messages = [];

    protected $listeners = ['messageReceived', 'messageSent', 'refreshMessages'];

    public function mount()
    {
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $this->contacts = WhatsAppMessage::select('phone_number')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('phone_number');
    }

    public function selectContact($number)
    {
        $this->selectedNumber = $number;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->selectedNumber) {
            $this->messages = WhatsAppMessage::where('phone_number', $this->selectedNumber)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $this->messages = [];
        }
    }

    public function sendMessage()
    {
        if (empty($this->newMessage)) {
            return;
        }

        WhatsAppMessage::create([
            'phone_number' => $this->selectedNumber,
            'message' => $this->newMessage,
            'is_from_me' => true,
        ]);

        $this->emit('messageSent', [
            'to' => $this->selectedNumber,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
    }

    public function messageReceived($data)
    {
        WhatsAppMessage::create([
            'phone_number' => $data['from'],
            'message' => $data['message'],
            'is_from_me' => false,
            'message_id' => $data['id']
        ]);

        if ($this->selectedNumber === $data['from']) {
            $this->loadMessages();
        }

        $this->loadContacts();
    }

    public function messageSent($data)
    {
        if ($this->selectedNumber === $data['to']) {
            $this->messages[] = WhatsAppMessage::create([
                'phone_number' => $data['to'],
                'message' => $data['message'],
                'is_from_me' => true,
            ]);
        }

        $this->loadContacts();
    }

    public function refreshMessages()
    {
        $this->loadMessages();
        $this->loadContacts();
    }

    public function render()
    {
        return view('livewire.whatsapp-chat', [
            'contacts' => $this->contacts,
            'messages' => $this->messages,
        ]);
    }
}
