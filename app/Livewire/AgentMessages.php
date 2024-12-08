<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WhatsAppMessage;
use Livewire\WithPagination;
use Carbon\Carbon;

class AgentMessages extends Component
{
    use WithPagination;

    public $selectedNumber = null;
    public $pendingMessages = [];
    public $attendedMessages = [];
    public $currentChat = [];
    public $newMessage = '';
    public $searchTerm = '';
    public $lastMessageTime = null;

    protected $listeners = [
        'messageReceived',
        'messageSent',
        'refreshMessages' => '$refresh',
        'echo:whatsapp,MessageReceived' => 'handleNewMessage'
    ];

    protected $rules = [
        'newMessage' => 'required|min:1'
    ];

    public function mount()
    {
        $this->loadMessages();
    }

    private function loadMessages()
    {
        // Cargar mensajes pendientes agrupados por número
        $this->pendingMessages = WhatsAppMessage::where('agent_status', 'pending')
            ->whereNotNull('phone_number')
            ->select('phone_number')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('phone_number');

        // Cargar mensajes atendidos
        $this->attendedMessages = WhatsAppMessage::where('status', 'attended')
            ->whereNotNull('phone_number')
            ->select('phone_number')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('phone_number');

        // Si hay un chat seleccionado, actualizar mensajes
        if ($this->selectedNumber) {
            $this->updateCurrentChat();
        }
    }

    public function updateCurrentChat()
    {
        $this->currentChat = WhatsAppMessage::where('phone_number', $this->selectedNumber)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->lastMessageTime = $this->currentChat->last()?->created_at;
    }

    public function selectChat($number)
    {
        $this->selectedNumber = $number;
        $this->updateCurrentChat();
        $this->dispatch('scrollToBottom');
    }

    public function sendMessage()
    {
        $this->validate();

        if (!$this->selectedNumber) {
            return;
        }

        $message = WhatsAppMessage::create([
            'phone_number' => $this->selectedNumber,
            'message' => $this->newMessage,
            'is_from_me' => true,
            'status' => 'sent',
            'agent_status' => 'pending',
            'agent_id' => auth()->guard('admin')->user()->id
        ]);

        $this->newMessage = '';
        $this->updateCurrentChat();
        $this->dispatch('messageSent');
        $this->dispatch('scrollToBottom');
    }

    public function messageReceived($data)
    {
        if ($this->selectedNumber === $data['phone_number']) {
            $this->updateCurrentChat();
            $this->dispatch('newMessage');
        }
        $this->loadMessages();
    }

    public function markAsAttended($number)
    {
        $adminId = auth()->guard('admin')->user()->id;

        WhatsAppMessage::where('phone_number', $number)
            ->update([
                'status' => 'attended',
                'agent_status' => 'resolved',
                'agent_id' => $adminId
            ]);

        $this->loadMessages();
    }

    public function markAsPending($number)
    {
        WhatsAppMessage::where('phone_number', $number)
            ->update([
                'status' => 'pending',
                'agent_status' => 'pending',
                'attended_at' => null
            ]);

        $this->loadMessages();
    }


    public function messageSent($data)
    {
        if ($this->selectedNumber === $data['phone_number']) {
            $this->updateCurrentChat();
        }
    }

    public function handleNewMessage($event)
    {
        $this->loadMessages();
        if ($this->selectedNumber === $event['phone_number']) {
            $this->updateCurrentChat();
            $this->dispatchBrowserEvent('newMessage');
        }
    }

    public function getMessageStatusCount()
    {
        return [
            'pending' => WhatsAppMessage::where('agent_status', 'pending')->distinct('phone_number')->count(),
            'attended' => WhatsAppMessage::where('status', 'attended')->distinct('phone_number')->count()
        ];
    }

    public function render()
    {
        $stats = $this->getMessageStatusCount();

        return view('livewire.agent-messages', [
            'messageStats' => $stats,
            'messages' => WhatsAppMessage::when($this->searchTerm, function($query) {
                $query->where('message', 'like', "%{$this->searchTerm}%")
                    ->orWhere('phone_number', 'like', "%{$this->searchTerm}%");
            })->get()
        ]);
    }

    public function getFormattedTimestamp($timestamp)
    {
        $carbon = Carbon::parse($timestamp);
        if ($carbon->isToday()) {
            return $carbon->format('H:i');
        } elseif ($carbon->isYesterday()) {
            return 'Ayer ' . $carbon->format('H:i');
        } else {
            return $carbon->format('d/m/Y H:i');
        }
    }

    protected function getListeners()
    {
        return array_merge($this->listeners, [
            "echo:whatsapp.{$this->selectedNumber},MessageSent" => 'messageSent',
            "echo:whatsapp.{$this->selectedNumber},MessageReceived" => 'messageReceived',
        ]);
    }
}
