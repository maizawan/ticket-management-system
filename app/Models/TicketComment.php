<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
use App\Models\User;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment'
    ];

    // 👇 Each comment belongs to a ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // 👇 Each comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}