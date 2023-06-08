<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshift extends Model
{
    use HasFactory;

    protected $table = "workshifts";

    public function generalIncoming()
    {
        return $this->hasMany(GeneralIncoming::class);
    }
}
