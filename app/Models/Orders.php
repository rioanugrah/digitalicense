<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'orders';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'order_code',
        'order_reference',
        'billing_order',
        'price',
        'status',
        'user_generate',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_generate','generate');
    }

    public function order_license()
    {
        return $this->belongsTo(\App\Models\OrderLicense::class, 'id','order_id');
    }

    public function order_detail()
    {
        return $this->hasMany(\App\Models\OrdersDetail::class, 'orders_id','id');
    }
}
