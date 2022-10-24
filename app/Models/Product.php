<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'price',
        'thumbnail',
    ];

//    Если заместо пеоля 'title' у нас поле 'name'. Тогда логика слага будет сториться на основе колонки 'name'
//    public static function slugFrom(): string
//    {
//        return 'name';
//    }



    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
