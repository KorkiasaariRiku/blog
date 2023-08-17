<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'body', 'category_id'];

    /**
     * Define a relationship where a post belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relationship where a post belongs to a category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get a shortened version of the post's body.
     *
     * @return string
     */
    public function getShortBodyAttribute(): string
    {
        return Str::limit($this->body, 15, '...');
    }

    /**
     * Scope a query to search for posts based on a keyword.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeWhereSearch($query, ?string $keyword)
    {
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                      ->orWhere('body', 'like', '%' . $keyword . '%');
            });
        }
    }
    
    /**
     * Scope a query to filter posts by a specific category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $category
     * @return void
     */
    public function scopeWhereCategory($query, ?string $category)
    {
        if ($category) {
            $query->where('category_id', $category);
        }
    }    
    
    /**
     * Define a relationship where a post has many comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
