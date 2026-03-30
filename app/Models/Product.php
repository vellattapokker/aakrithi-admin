<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'category',
        'badge',
        'image',
        'sizes',
        'description',
        'meta_title',
        'meta_description',
        'og_image',
        'is_noindex',
    ];

    protected $casts = [
        'sizes' => 'array',
    ];
}
