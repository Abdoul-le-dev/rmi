<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class CommentPost extends Model
{
    use HasFactory;

    protected $table = 'comment_posts'; 

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'likes_count',
    ];

    protected $casts = [
        'likes_count' => 'integer',
    ];

    // Relations
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function parent()
    {
        return $this->belongsTo(CommentPost::class, 'parent_id');
    }

    // Réponses
    public function replies()
    {
        return $this->hasMany(CommentPost::class, 'parent_id');
    }
}
