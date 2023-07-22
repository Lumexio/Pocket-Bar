<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ["product_id", "branch_id", "units"];

    public function product()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
