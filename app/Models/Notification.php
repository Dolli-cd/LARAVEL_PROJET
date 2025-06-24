<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable=[
        'client_id',
        'pharmacie_id',
        'title',
        'message',
    ];
    public function markAsRead()
    {
        $this->update(['read' => true]);
    }

    public function markAsUnread()
    {
        $this->update(['read' => false]);
    }

     //Une notification est destinée à un client ou à une pharmacie
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }
}
