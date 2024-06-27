<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryDetail extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'category_detail';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'category_id',
        'name',
    ];

}
