<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLicense extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'order_license';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'order_id',
        'product_id',
        'product_license',
        'status',
        'user_generate',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_generate', 'generate');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }
}
