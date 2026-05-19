<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TicketComment;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'status'
    ];

 
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }
}