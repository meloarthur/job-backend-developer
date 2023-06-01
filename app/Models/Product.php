<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'price',
        'description',
        'category',
        'image_url',
    ];

    public static function getProducts(array $filters = [])
    {
        return Product::
            when(isset($filters['nameCategory']), function ($w) use ($filters) {
                $w->where('name', 'like', '%'. $filters['nameCategory'] .'%')->orWhere('category', 'like', '%'. $filters['nameCategory'] .'%');
            })
            ->when(isset($filters['category']), function ($w) use ($filters) {
                $w->where('category', 'like', '%' . $filters['category'] . '%');
            })
            ->when(isset($filters['with_image']), function ($w) use ($filters) {
                $filters['with_image'] === 'true' ? $w->whereNotNull('image_url') : $w->whereNull('image_url');
            })
            ->get();
    }
}
