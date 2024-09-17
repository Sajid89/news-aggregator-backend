<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'url',
        'published_at',
        'author',
        'source_id',
        'category_id',
    ];

    // Relationship with the Source
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    // Relationship with the Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with User Favorites
    public function favoritedByUsers()
    {
        return $this->hasMany(UserFavorite::class);
    }
}
