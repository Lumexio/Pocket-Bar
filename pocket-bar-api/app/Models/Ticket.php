<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ["*"];

    public function details()
    {
        return $this->hasMany(TicketDetail::class, 'ticket_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function barTender()
    {
        return $this->belongsTo(User::class, 'barTender_id');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function workshift()
    {
        return $this->belongsTo(Workshift::class, 'workshift_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'mesa_id');
    }
}
