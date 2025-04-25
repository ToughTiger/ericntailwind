<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'meta_description',
        'keywords',
        'content',
        'image',
        'alt',
        'is_featured',
        'is_published',
        'is_verified',
        'published_at',
    ];


    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Comment::class)->whereNull('parent_comment'  );
    // }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');
    }

    // public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Like::class, 'post_id');
    // }

//    casting Enums
    protected $casts = [
       //
        ];

}