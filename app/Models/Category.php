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

    public function category_detail_list()
    {
        return $this->hasMany(\App\Models\CategoryDetail::class, 'category_id');
    }

    public function category_detail()
    {
        return $this->belongsTo(\App\Models\CategoryDetail::class, 'id');
    }
}
