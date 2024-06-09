<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\User;

class Product extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     * @var array
     *Aqui se especifica los campos de entrada o permitidos para llenar la tabla artículos con los campos de las tablas *foraneas
     */
    protected $fillable = [
        '*'
    ];

    //ACtivity log system
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Product::class, 'ingredients', 'base_product_id', 'ingredient_product_id')
            ->withPivot('quantity', 'unit')
            ->withTimestamps();
    }
}
