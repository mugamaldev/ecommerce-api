<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'out_of_stock'
    ];
    protected $casts = [
        'out_of_stock' => 'boolean'
    ];

    public static function boot(){
        parent::boot();
        static::saving(function($model){
            $model->out_of_stock = $model->stock == 0;
        });
    }
}
