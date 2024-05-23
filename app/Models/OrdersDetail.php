<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'order_detail';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'orders_id',
        'order_name',
        'product_id',
        'qty',
        'price',
        'product_license',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id','id');
    }
}
