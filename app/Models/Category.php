<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name . '-' . time());
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
