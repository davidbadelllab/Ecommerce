<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'phone_number',
        'message',
        'is_from_me',
        'message_id',
        'status',
        'agent_status',
    ];

    protected $casts = [
        'is_from_me' => 'boolean'
    ];

    public function agent()
    {
        return $this->belongsTo(\Webkul\User\Models\Admin::class, 'agent_id');
    }

    public function scopeForNumber($query, $phoneNumber)
    {
        return $query->where('phone_number', $phoneNumber);
    }
}
