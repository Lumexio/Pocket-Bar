<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralIncoming extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workshift_id',
        'amount',
        'description',
    ];

    protected $table = 'general_incomings';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workshift()
    {
        return $this->belongsTo(Workshift::class);
    }
}
