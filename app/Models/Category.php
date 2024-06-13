<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'category';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'slug',
        'name',
    ];

    public function product()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }

    public function product_detail()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }
}
