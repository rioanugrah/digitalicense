<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'product_detail';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'product_id',
        'product_license',
        'status',
        'user_generate',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_generate', 'generate');
    }
}
